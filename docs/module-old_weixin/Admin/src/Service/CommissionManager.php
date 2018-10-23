<?php
namespace Admin\Service;

use Zend\Config\Config;
use Application\Model\CommonModel;
use Zend\Db\Sql\Ddl\Column\Decimal;
use Application\Service\SellerManager;
use Application\Service\UserManager;
use Zend\Db\Sql\Select;
use Admin\Api\YouzanApi;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Log\Logger;

class CommissionManager
{
    protected $seller           = null;
    protected $youzan           = null;
    protected $CommonModel      = null;
    protected $user             = null;
    
    protected $table_commisson  = 'record_seller_commission';
    protected $seller_config_filename   ='module/Admin/config/seller.config.php';
    protected $seller_config_data       =null;
    protected $logger                   =null;
    
    protected $date=null;
    

    public function __construct(
        SellerManager $seller, 
        CommonModel $CommonModel,
        UserManager $user,
        Logger $logger
        )
    {
        $this->seller = $seller;
        $this->youzan = YouzanApi::getInstanse($logger);
        $this->CommonModel = $CommonModel;
        $this->user   =$user;
        $this->logger = $logger;
        $this->seller_config_data= new Config(include $this->seller_config_filename);
    }
    
    /**
    * 每月月初计算所有销售员的奖金
    * 
    * @param  
    * @return        
    */
    public function calucateCommissioPerMonth()
    {
        /**
        * 思路：
        * 从销售员数据库中读取所有销售员
        * 分别计算每个销售员的业绩（截止到上个月的业绩）
        */
        //获取所有销售员
        $sellers=$this->seller->fecthAllSellers();
        if (empty($sellers))
        {
            return ;
        }
        //分别计算每个销售员的业绩
        foreach ($sellers as $seller)
        {
            $openid=$seller['openid'];
            //判断该销售员，本月是否计算过奖金，如果没有计算过则需要计算
            if (empty($this->hasCalculatedThisMonth($openid)))
            {
                //获取该销售员历史订单
                //注意，开始时间和截止时间从配置文件中读取
                $trades=$this->fetchTradesUntilLastMonth($openid);
                if (!empty($trades))
                {
                    //开始统计截止到上月末该销售员业绩
                    $commission  =$this->calculateCommission($trades);
                    //存入数据库
                    $this->saveCommission($commission, $openid);
                }
            }
            
        }
    }
    
    /**
    * 计算上月统计奖金
    * 
    * @param  string $openid
    * @return void       
    */
    private function fetchTradesUntilLastMonth($openid)
    {
        //得到上月末时间戳
        $end_day_last_month =strtotime(date('Y-m-t 23:59:59', strtotime('-1 month', time())));
        $start_day          =$this->seller_config_data->commission->date->start_day;
        $end_day_plan       =$this->seller_config_data->commission->date->end_day;
        $end_day            =$end_day_last_month <= $end_day_plan ? $end_day_last_month : $end_day_plan;
        $this->date=[
            'start_day'=>$start_day,
            'end_day'=>$end_day,
        ];
        return $this->youzan->fetchSellerHistoryTradeByOpenid($openid, $start_day, $end_day);
    }
    /**
    * 保存得到的业绩数据 到数据库
    * 
    * @param  array   $accumulationsItem
    * @param  string  $openid
    * @param  array   $date the start day and end day 
    * @return boolean true or false if fail       
    */
    protected function saveCommission($commission, $openid)
    {
        //得到业绩总额（手动计算订单+自动计算订单）
        $commission_total=$commission['manual_settle_order_amount'] + $commission['auto_settle_order_amount'];
        //根据业绩总额获取应得奖金比例
        $percent=$this->calucatePercent($commission_total);
        //本次计算应该佣金总额
        $commission_history_all_this_time=$commission_total * $percent;
        
        //从数据表中查询该销售员历史已发佣金
        $commisson_history_all_last_time=$this->seller->getCommissionAmountHisotyByOpenid($openid);
        //计算本次应发奖金
        $commissin_this_time=$commission_history_all_this_time - $commisson_history_all_last_time;
        //开始事物操作
        $begin=$this->CommonModel->getDbAdapter()->getDriver()->getConnection()->beginTransaction();
        try{
            //向佣金记录表中增加
            $data=[
                'openid'=>$openid,
                'created'=>time(),
                'percent'=>$percent,
                'commission_history_all_this_time'=>$commission_history_all_this_time,
                'commisson_history_all_last_time'=>$commisson_history_all_last_time,
                'commission_this_time'=>$commissin_this_time,
            ];
            $insert_values=array_merge($data, $commission);
            $insert_values=array_merge($insert_values, $this->date);
            $insert=new Insert($this->table_commisson);
            $insert->values($insert_values);
            $insert_id=$this->CommonModel->insertItem($insert);
            if (empty($insert_id))
            {
                throw new \Exception('插入佣金数据出错');
            }
            
            //更新用户表 增加可提现余额
            $update_user_array=[
                'amount'=>new Expression("amount + $commissin_this_time"),
            ];
            $update_user_id=$this->user->updateUser($update_user_array, $openid);
            if (empty($update_user_id))
            {
                throw new \Exception('计算佣金 更新用户账户出错');
            }
            //更新seller表 更新历史已发佣金 和更新时间
            $update_seller=[
                'commission_history_all_when_update_time'=>$commission_history_all_this_time,
                'update_time_commission'=>time(),
            ];
            $update_seller_id=$this->seller->updateSeller($openid, $update_seller);
            if (empty($update_seller_id))
            {
                throw new \Exception('计算佣金 更新销售员表账户出错');
            }
            $begin->commit();
        }catch (\Exception $e ){
            $begin->rollback();
            $this->logger->log(Logger::DEBUG, $e->getMessage());
        }
    }
    /**
    * 计算应得佣金比例
    * 
    * @param  Decimal $total
    * @return Decimal $percent       
    */
    protected function calucatePercent($commission_total)
    {
        $percents=$this->seller_config_data->toArray()['commission']['percent'];
        $per=0;
        foreach ($percents  as $key=>$percent)
        {
            if ($percent['start'] <= $commission_total && $commission_total < $percent['end'])
            {
                $per=$key;
                break;
            }
        }
        return $per;
    }
    /**
    * 根据业绩统计列表 循环计算业绩之和
    * 
    * @param  array $res
    * @return array $amount       
    */
    protected function calculateCommission($trades)
    {
        $manual_settle_order_amount=0;//人工结算订单金额
        $manual_settle_order_num=0;//人工结算订单数
        $auto_settle_order_amount=0;//自动结算订单金额
        $auto_settle_order_num=0;//自动结算订单数
        foreach ($trades as $trade)
        {
            $manual_settle_order_amount +=$trade['manual_settle_order_amount'];
            $manual_settle_order_num    +=$trade['manual_settle_order_num'];
            $auto_settle_order_amount   +=$trade['auto_settle_order_amount'];
            $auto_settle_order_num      +=$trade['auto_settle_order_num'];
        }
        return [
            'manual_settle_order_amount'=>$manual_settle_order_amount,
            'manual_settle_order_num'   =>$manual_settle_order_num,
            'auto_settle_order_amount'  =>$auto_settle_order_amount,
            'auto_settle_order_num'     =>$auto_settle_order_num,
        ];
    }
    /**
    * 判断本月是否已完成月度统计计算
    * 
    * @param   string  $openid
    * @return  boolean true or false if not calculate this month     
    */
    protected function hasCalculatedThisMonth($openid)
    {
        $select     =new Select($this->table_commisson);
        $start_day  =strtotime(date('Y-m-1 00:00:00'));
        $end_day    =strtotime(date('Y-m-t 23:59:59'));
        $select     ->where(['openid' => $openid])->limit(1)
                    ->where->between('created', $start_day, $end_day);
        $res        =$this->CommonModel->fetchOne($select);
        return empty($res) ? false : true;
    }
}

