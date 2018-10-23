<?php
namespace Application\Service;

use Zend\Config\Config;
// use Zend\Db\Sql\Sql;
use Application\Model\CommonModel;
use Application\Service\UserManager;
use Zend\Db\Sql\Ddl\Column\Decimal;
use Zend\Log\Logger;
use Admin\Api\YouzanApi;
use Zend\Db\Sql\Select;

class SellerManager
{

    protected $CommonModel      = null;
    
    protected $youzan           = null;
    protected $seller_config_filename    = 'module/Admin/config/seller.config.php';
    protected $seller_config_data        = null;
    protected $table_seller     ='sellers';
    protected $user             =null;
    protected $log              =null;
    
    const STATUS_SELLER_ENABLE="ENABLE";
    const STATUS_SELLER_DISABLED="DISABLED";

    public function __construct(CommonModel $commonModel, UserManager $user, Logger $logger)
    {
        $this->CommonModel      = $commonModel;
        $this->user             = $user;
        $this->log              = $logger;
        
        $this->youzan               = YouzanApi::getInstanse($logger);
        $this->seller_config_data   = new Config(include $this->seller_config_filename);
        
    }
    
    /**
     * verify the user is or not seller
     *
     * @param            
     * @return boolean
     */
    public function isSellerByOpenid($openid)
    {
        return $this->youzan->isSellerByOpenid($openid);
    }
    
    /**
    * get the url to pay
    * 
    * @param  
    * @return string       
    */
    public function getUrlToPay()
    {
        return $this->seller_config_data->pay->url;
    }
    
    /**
    * get the array config
    * 
    * @param  
    * @return array $config       
    */
    public function getArrayConfig()
    {
        return $this->seller_config_data->toArray();
    }
    
    /**
    * set user to be a seller for free
    * 
    * @param  void
    * @return boolean       
    */
    public function changeUserToBeSellerForFree($openid)
    {
        return  $this->doChangeAndStore($openid);
        
    }
    /**
    * set user to be seller not free
    * 
    * @param  
    * @return        
    */
    public function changeUserToBeSellerNotFree($openid)
    {
        $payment    =$this->seller_config_data->pay->payment;
        $points     =$this->seller_config_data->point->nums;
        $reason     =$this->seller_config_data->point->reason;
        $this->doChangeAndStore($openid, $payment, $points, $reason);
    }
    /**
    * do change and store 
    * 
    * @param  
    * @return  boolean true or false if fail      
    */
    private function doChangeAndStore($openid, $payment=0, $points=0, $reason='')
    {
        if ($this->youzan->isSellerByOpenid($openid))
        {
            return ;
        }
        
        $data=[
            'openid'    =>$openid,
            'created'   =>date('Y-m-d h:i:s'),
            'payment'   =>$payment,
            'points'    =>$points,
            'status'    =>self::STATUS_SELLER_ENABLE,
        ];
        
        // beginTransaction
        $beginTransaction   = $this->CommonModel->getDbAdapter()
                            ->getDriver()
                            ->getConnection()
                            ->beginTransaction();
        try{
            $this       ->youzan->changeUserToBeSellerByOpenid($openid);
            $this       ->createSeller($openid, $data);
            if ($points > 0)
            {
                $this   ->youzan->increasePointByOpenid($openid, $points, $reason);
            }
            $beginTransaction->commit();
            $flag    =true;
        }catch (\Exception $e ){
            $beginTransaction->rollback();
            $this->log->log(Logger::DEBUG, $e->getMessage());
            $flag=false;
        }
        
        return $flag;
    }
    /**
    * validate is free
    * 
    * @param   void
    * @return  boolean      
    */
    public function isFree()
    {
        $free_of_charge=$this->seller_config_data['pay']['free_of_charge'];
        if (empty($free_of_charge['is_free']))
        {
            return false;
        }
        
        $end_time=$free_of_charge['end_time'];
        if ($end_time < time())
        {
            return false;
        }
        
        return true;
    }
    
    /**
    * insert a seller infomation into table 
    * 
    * @param  array $data
    * @return int or false if fail       
    */
    protected function createSeller($openid, $data)
    {
        $insert=$this->CommonModel->getSql()->insert($this->table_seller);
        $insert->values($data);
        return $this->CommonModel->insertItem($insert);
    }
    /**
    * get seller's amount_history 
    * 
    * @param  string $openid
    * @return Decimal $amount_history       
    */
    public function getCommissionAmountHisotyByOpenid($openid)
    {
        $res=$this->fetchSellerDataByOpenid($openid);
        return empty($res) ? 0 : $res['commission_history_all_when_update_time'];
        
    }
    
    /**
    * 查询所有可用的销售员信息
    * 
    * @param  
    * @return        
    */
    public function fecthAllSellers()
    {
        $select=new Select($this->table_seller);
        $res=$this->CommonModel->fethchAll($select);
        return $res;
    }
    /**
    * query user 
    * 
    * @param   void
    * @return  array or false      
    */
    protected function fetchSellerDataByOpenid($openid)
    {
        $select=$this->CommonModel->getSql()->select($this->table_seller);
        $select->where(['openid' => $openid])->limit(1);
        return $this->CommonModel->fetchOne($select);
    }
    
    /**
    * update seller table by data
    * 
    * @param  string $openid
    * @param  array $data
    * @return boolean       
    */
    public function updateSeller($openid, array $data)
    {
        $updata=$this->CommonModel->getSql()->update($this->table_seller);
        $updata->where(['openid' => $openid]);
        $updata->set($data);
        return $this->CommonModel->updateOrDeleteItem($updata);
    }
}

