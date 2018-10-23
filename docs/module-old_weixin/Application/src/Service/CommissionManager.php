<?php
namespace Application\Service;

use Zend\Config\Config;
use Application\Model\CommonModel;
use Zend\Db\Sql\Select;

class CommissionManager
{

    protected $CommonModel      = null;
    
    protected $youzan           = null;
    protected $table_commisson  = 'record_seller_commission';
    protected $seller_config_filename   ='module/Admin/config/seller.config.php';
    protected $seller_config_data   =null;

    public function __construct(CommonModel $CommonModel)
    {
        $this->CommonModel      = $CommonModel;
        $this->seller_config_data = new Config(include $this->seller_config_filename);
    }
    
    public function getYouzanSellerCenterUrl()
    {
        return $this->seller_config_data->url->youzan_seller_center;
    }
    /**
    * 
    * 
    * @param  
    * @return        
    */
    public function fetchPaginator($openid, $page=1)
    {
        //得到分页数据
        $select =new Select($this->table_commisson);
        $select ->where(['openid'=>$openid]);
        $select ->order('created DESC');
        $this   ->CommonModel->setCountPerPage(3);
        return $this->CommonModel->paginator($select, $page);
    }
}

