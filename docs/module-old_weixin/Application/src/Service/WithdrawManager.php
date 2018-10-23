<?php
/**
 * @insruction:返现管理
 * @desc 从有赞服务器提取已完成订单列表，从列表中选取所有参加返现的订单列表
 *       以商品id为判断是否参加返现
 *
 * @fileName  :PointTradeManager.php
 * @author: 秦崇
 */
namespace Application\Service;

use Zend\Config\Config;
use Zend\Log\Logger;
use Application\Service\UserManager;
use Application\Service\CardManager;
use Application\Model\CommonModel;
use Zend\Db\Sql\Sql;

class WithdrawManager
{

    protected $CommonModel      = null;

    protected $withdraw_config     = null;
    protected $withdraw_config_filename = 'module/Application/config/withdraw.config.php';

    protected $user             = null;
    protected $table_record_withdraw= 'record_withdraw';
    protected $card             = null;
    protected $log              = null;
    
    const BUTTON_OUT_OF_BOUNDS ='OUT_OF_BOUNDS';
    const BUTTON_OUT_OF_TIMES  ='OUT_OF_TIMES';
    const BUTTON_ENABLE        ='ENABLE';
    
    const WITHDRAW_STATUS_WAIT_REMIT='WAIT_REMIT';// 待打款
    const WITHDRAW_STATUS_SUCCESSED='SUCCESSED';// 提现成功
    const WITHDRAW_STATUS_FAILED='FAILED';// 提现失败

    public function __construct(CommonModel $CommonModel, UserManager $user, CardManager $card, Logger $logger)
    {
        $this->CommonModel      = $CommonModel;
        $this->user             = $user;
        $this->card             = $card;
        $this->log              = $logger;
        $this->withdraw_config   = new Config(include $this->withdraw_config_filename);
    }

    /**
     *
     * @return array the $withdraw_config
     */
    public function getwithdrawConfigData()
    {
        $config = $this->withdraw_config;
        return $config->toArray();
    }

    /**
     * save withdraw
     *
     * @param float $cashback            
     * @return true or false
     */
    public function saveWithDraw($cashback = 0, $openid)
    {
        /**
        * 检查提现日期
        * 检查提现次数
        * 检查提现金额
        */
        //检查提现日期
        if ($this->checkWithdrawDate() === self::BUTTON_OUT_OF_BOUNDS)
        {
            return false;
        }
        //检查提现次数
        if ($this->checkWithdrawTimes($openid) === self::BUTTON_OUT_OF_TIMES)
        {
            return false;
        }
        
        // DEBUG INFORMATION END
        //检查提现金额
        $amount=(float)$this->user->getAmountOfAcount($openid);
        if(!$this->checkWithdrawCash($cashback, $amount))
        {
            return false;
        }
        
        //统统检查通过，下面进行保存提现数据操作
        $res=$this->saveData($cashback, $openid, $amount);
        return $res;
    }
    
    /**
    * 保存提现数据到数据库
    * 
    * @param  float     $cashback
    * @param  string    $openid
    * @param  float     $amount
    * @return boolean true or false       
    */
    private function saveData($cashback, $openid, $amount)
    {
        //calucate the cashback
        $rate           = $this->withdraw_config->service_charge->rate;
        $cashback_fee   = $cashback * $rate;
        $cashback_real  = $cashback - $cashback_fee;
        $values_withdraw = [
            'openid'            => $openid,
            'cashback_total'    => $cashback,
            'cashback_fee'      => $cashback_fee,
            'cashback_real'     => $cashback_real,
            'created'           => time(),
            'withdraw_id'       => $this->getWithdrawId(),
            'status'            => self::WITHDRAW_STATUS_WAIT_REMIT,
        ];
        
        //获取提现至银行卡信息
        $card=$this->card->fetchCardByOpenid($openid);
        unset($card['id']);
        unset($card['openid']);
        unset($card['created']);
        unset($card['card_type_code']);
        unset($card['status']);
        //生成要插入数据表中的数据
        $values_withdraw=array_merge($values_withdraw, $card);
        
        $insert     = new \Zend\Db\Sql\Insert($this->table_record_withdraw);
        $insert     ->values($values_withdraw);
        
        // update user
        $data_user = [
            'amount' => $amount - $cashback
        ];
        // begin
        $begin = $this->CommonModel->getDbAdapter()
        ->getDriver()
        ->getConnection()
        ->beginTransaction();
        
        try {
            $insert_id=$this   ->CommonModel->insertItem($insert);
            if (empty($insert_id))
            {
                throw new \Exception('提现时，插入提现记录出错');
            }
            
            $update_id=$this   ->user->updateUser($data_user, $openid);
            if (empty($update_id))
            {
                throw new \Exception('提现时，更新用户账号出错');
            }
            $begin  ->commit();
            $res    = true;
        } catch (\Exception $e) {
            $begin->rollback();
            $this->log->log(Logger::DEBUG, $e->getMessage());
            $res = false;
        }
        
        return $res;
    }
    /**
    * 从提现金额方面验证是否可提现
    * 
    * @param  
    * @return boolean true or false if illegal       
    */
    protected function checkWithdrawCash($cashback, $amount)
    {
        /**
        * 判断提现金额和配置文件中运行的提现金额是否一致
        * 判断提现金额是否不超过当前账户余额
        */
        
        //判断是否满足最低提现金额和最高提现金额
        $cashback=(float)$cashback;
        $the_lowest_cash    = $this->withdraw_config->cash_withdraw->the_lowest_cash;
        $the_highest_cash   = $this->withdraw_config->cash_withdraw->the_highest_cash;
        
        if (bccomp($the_lowest_cash, $cashback, 5) === 1)
        {
            return false;
        }
        if (bccomp($the_highest_cash, $cashback, 5) === -1)
        {
            return false;
        }
        //判断是否满足当前用户可用余额
        if (bccomp($amount, $cashback) === -1)
        {
            return false;
        }
        return true;
    }

    /**
     * get the button action status
     *
     * @param  void          
     * @return array ['status', 'button']
     */
    public function renderButtonOfWithdraw($openid)
    {
        /**
        * 首先读取提现配置文件
        * 从提现日期判断是否在提现日期内
        * 从提现次数内判断是否满足
        */
        //首先判断是否在提现日期内，从配置文件中读取提现日期
        $status_button=$this->checkWithdrawDate();
        //如果在提现日期内，则继续判断是否超过提现次数
        if ($status_button == self::BUTTON_ENABLE)
        {
            $status_button=$this->checkWithdrawTimes($openid);
        }
        return [
            'status'=>$status_button,
            'dom'   =>$this->fetchButtonDomElement($status_button),
        ];
    }
    
    /**
    * 验证是否在提现日期内
    * 
    * @param   void
    * @return  boolean true or false if the date is illegal      
    */
    protected function checkWithdrawDate()
    {
        //读取配置文件，获取每日提现开始时间和结束时间
        $start_day  = $this->withdraw_config->date->start_day;
        $end_day    = $this->withdraw_config->date->end_day;
        $day_now    = date('d');
        
        if ($day_now < $start_day || $day_now > $end_day)
        {
            $status_button=self::BUTTON_OUT_OF_BOUNDS;
        }else 
        {
            $status_button=self::BUTTON_ENABLE;
        }
        return $status_button;
    }
    
    /**
     * 检查提现次数是否符合要求
     *
     * @param  void
     * @return boolean true or false if not valid
     */
    protected function checkWithdrawTimes($openid)
    {
        //从配置文件中读取每月最多提现次数
        $max_times      = $this->withdraw_config->date->the_maximum_times;
        
        //从数据库查询本月已提现次数
        $month_start    = mktime(0, 0, 0, date('m'), 1, date('Y'));
        $month_end      = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
        $select = $this->CommonModel->getSql()->select($this->table_record_withdraw);
        $select ->where->between('created', $month_start, $month_end)->equalTo('openid', $openid);
        $res    = $this->CommonModel->fethchAll($select);
        
        if ($max_times > count($res))
        {
            $button_status=self::BUTTON_ENABLE;
        }else 
        {
            $button_status=self::BUTTON_OUT_OF_TIMES;
        }
        return $button_status;
    }

    /**
    * get the element of button
    * 
    * @param  
    * @return        
    */
    private function fetchButtonDomElement($button_status=self::BUTTON_OUT_OF_BOUNDS)
    {
        //首先设置button不同状态下的dom
        $button_eanble="<a class='weui-btn weui-btn_primary' href='javascript:;' id='button_cashback'>申请提现</a>";
        
        $start  =$this->withdraw_config->date->start_day;
        $end    =$this->withdraw_config->date->end_day;
        $button_out_of_bounds="<a href='javascript:;' class='weui-btn weui-btn_disabled weui-btn_default'>每月 $start 号~$end 号可申请提现</a>";
        
        $the_maximum_times=$this->withdraw_config->date->the_maximum_times;
        $button_out_of_times ="<a href='javascript:;'class='weui-btn weui-btn_disabled weui-btn_default'>每月可提现$the_maximum_times 次</a>";
        
        //根据当前button status返回应render的dom
        switch ($button_status)
        {
            case self::BUTTON_ENABLE:
                $dom=$button_eanble;
                break;
            case self::BUTTON_OUT_OF_TIMES:
                $dom=$button_out_of_times;
                break;
            case self::BUTTON_OUT_OF_BOUNDS:
            default:
                $dom=$button_out_of_bounds;
        }
        
        return $dom;
    }
    
    /**
     * get withdraw id
     *
     * @param            
     *
     * @return string
     */
    protected function getWithdrawId()
    {
        $mark = 'C';
        $date = date('Ymdhis');
        
        $numbers = range(10, 99);
        // shuffle 将数组顺序随即打乱
        shuffle($numbers);
        // array_slice 取该数组中的某一段
        $num = 2;
        $result = array_slice($numbers, 0, $num);
        
        $random = implode('', $result);
        
        return $mark . $date . $random;
    }
}

