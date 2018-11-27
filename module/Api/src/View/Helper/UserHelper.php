<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\UserManager;
use Api\Entity\UserEntity;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class UserHelper extends AbstractHelper
{
    private $UserManager;
    private $mySession;
    
    public function __construct(
        UserManager $UserManager,
        $mySession
        ) 
    {
        $this->UserManager   = $UserManager;
    }
    
    public function getEntity($userID = null, $identity =null)
    {
        if(isset($userID)) {
            return $this->UserManager->MyOrm->findOne($userID);
        }
        if (isset($identity)) {
            return $this->UserManager->findUserByIdentity($identity);
        }
    }
    
    public function getEntityByIdentity($identity)
    {
        return $this->UserManager->findUserByIdentity($identity);
    }
    
    public function getRoleName($role)
    {
        if($role == UserManager::ROLE_SUPER_ADMIN){
            return '系统管理员';
        }
        
        if($role == UserManager::ROLE_WORKYARD_ADMIN){
            return '工地管理员';
        }
    }
    
    /**
    * 判断用户是否是管理员
    * 
    * @param  
    * @return  boolean      
    */
    public function renderStatusName($status)
    {
        $statusData = $this->getALLStatus();
        if (array_key_exists($status, $statusData)) 
        {
            return $statusData[$status];
        }else 
        {
            return '-';
        }
    }
    
    public function getEntities($where = [])
    {
        return $this->UserManager->MyOrm->findAll($where);
    }
    /**
    * 获取所有的用户角色
    * 
    * @param  
    * @return  boolean      
    */
    public function getAllStatus()
    {
        return [
            UserManager::STATUS_ENABLED => '正常',
            UserManager::STATUS_WAIT_CHANGE_PASSWORD_NEW_CREATED    => '新增用户-待修改密码',
            UserManager::STATUS_WAIT_CHANGE_PASSWORD_RESET_PASSWORD => '重置密码-待修改密码'
        ];
    }
    
    public function getAllRoles()
    {
        return [
            UserManager::ROLE_WORKYARD_ADMIN => '工地管理员',
            UserManager::ROLE_WORKYARD_GUARD=> '巡逻员'
        ];
    }
    
    public function getPaginator($page = 1, $where = [])
    {
        $role  = UserManager::ROLE_WORKYARD_ADMIN;
        $where[UserEntity::FILED_ROLE] = UserManager::ROLE_WORKYARD_ADMIN;
        $paginator = $this->UserManager->MyOrm->paginator($page, $where);
        return $paginator;
    }
    
    /**
    * 获取用户所辖工地id
    * 
    * @param string $identity 用户认证名
    * @return int $workyard_id 
    */
    public function getWorkyardId($identity)
    {
        return $this->UserManager->getWorkyardId($identity);
    }
    
    public function count($where)
    {
        return $this->UserManager->MyOrm->count($where);
    }
}
