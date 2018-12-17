<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\RegisterManager;
use Api\Entity\RegisterEntity;
use Zend\Db\Sql\Select;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class RegisterHelper extends AbstractHelper 
{
    private $RegisterManager;
    
    public function __construct(
        RegisterManager $RegisterManager
        )
    {
        $this->RegisterManager = $RegisterManager;
    }
    
    public function getEntityByOpenid($openid)
    {
        $where = [RegisterEntity::FILED_ADMIN_OPENID => $openid];
        $order = [RegisterEntity::FILED_CREATED_AT => Select::ORDER_DESCENDING];
        
        $Register = $this->RegisterManager->MyOrm->findOne($where, $order);
        if (empty($Register->getId())) $Register = false;
        return $Register;
    }
    public function getEntityById($id)
    {
        $Register = $this->RegisterManager->MyOrm->findOne($id);
        return $Register;
    }
    
    public function getPaginator($page, $where=[])
    {
        $paginator = $this->RegisterManager->MyOrm->paginator($page, $where);
        $paginator::setDefaultItemCountPerPage(12);
        return $paginator;
    }
}
