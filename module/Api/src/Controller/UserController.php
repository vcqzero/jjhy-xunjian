<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Api\Service\UserManager;
use Api\Controller\Plugin\AjaxPlugin;
use Api\Entity\UserEntity;

class UserController extends AbstractActionController
{
    private $UserManager;
    private $AjaxPlugin;
    
    public function __construct(
        UserManager $UserManager,
        AjaxPlugin $AjaxPlugin
        )
    {
        $this->UserManager  = $UserManager;
        $this->AjaxPlugin   = $AjaxPlugin;
    }
    
    public function validNameAction()
    {
        $username     = $this->params()->fromPost('username');
        $where        = [
            'username'=> $username
        ];
        $count      = $this->UserManager->MyOrm->count($where);
        $this->AjaxPlugin->valid(empty($count));
    }
    
    public function validPasswordAction()
    {
        $identity     = $this->identity();
        $password     = $this->params()->fromPost('password_old');
        $UserEntiyt   = $this->UserManager->findUserByIdentity($identity);
        $password_hash= $UserEntiyt->getPassword();
        $valid        = password_verify($password, $password_hash);
        
        $this->AjaxPlugin->valid($valid);
    }
    
    //response add
    public function addAction()
    {
        //获取用户提交表单
        $values = $this->params()->fromPost();
        
        //处理表单数据
        //获取密码
        $password = $this->UserManager->buildNewPassword();
        $password_hash = $this->UserManager->password_hash($password);
        //设置基本数据
        $identity   = $this->identity();
        $UserEntity = $this->UserManager->findUserByIdentity($identity);
        $role       = $UserEntity->getRole();
        switch ($role)
        {
            case UserManager::ROLE_SUPER_ADMIN:
                $values[UserEntity::FILED_ROLE] = UserManager::ROLE_WORKYARD_ADMIN;
                break;
            case UserManager::ROLE_WORKYARD_ADMIN:
                $values[UserEntity::FILED_ROLE] = UserManager::ROLE_WORKYARD_GUARD;
                break;
        }
        $values[UserEntity::FILED_PASSWORD] = $password_hash;
        $values[UserEntity::FILED_STATUS] = UserManager::STATUS_WAIT_CHANGE_PASSWORD;
        $values[UserEntity::FILED_INITIAL_PASSWORD] = $password;
        
        //过滤表单
        $values  = $this->UserManager->FormFilter->getFilterValues($values);
        
        //执行增加操作
        $res = $this->UserManager->MyOrm->insert($values);
        $this->AjaxPlugin->success($res);
    }
    
    //do edit 
    public function editAction()
    {
        $userID = $this->params()->fromRoute('userID', 0);
        //获取用户提交表单
        $values = $this->params()->fromPost();
        //处理表单数据
        $values = $this->UserManager->FormFilter->getFilterValues($values);
        //执行增加操作
        $res = $this->UserManager->MyOrm->update($userID, $values);
        $this->AjaxPlugin->success($res);
    }
    
    //do change password
    public function changePasswordAction()
    {
        $userID    = $this->params()->fromRoute('userID', 0);
        $change_initial_password = $this->params()->fromQuery('change_initial_password');
        //获取用户提交表单
        $password  = $this->params()->fromPost('password');
        $password_hash  = $this->UserManager->password_hash($password);
        $values    = [
            UserEntity::FILED_PASSWORD  => $password_hash,
        ];
        if ($change_initial_password == 'true') {
            $values[UserEntity::FILED_STATUS] = UserManager::STATUS_ENABLED;
        }
        //执行增加操作
        $res = $this->UserManager->MyOrm->update($userID, $values);
        $this->AjaxPlugin->success($res);
    }
    
    //do change password
    public function resetPasswordAction()
    {
        $userID    = $this->params()->fromRoute('userID', 0);
        //获取密码
        $password       = $this->UserManager->buildNewPassword();
        $password_hash  = $this->UserManager->password_hash($password);
        $values = [
            UserEntity::FILED_PASSWORD  => $password_hash,
            UserEntity::FILED_INITIAL_PASSWORD => $password,
            UserEntity::FILED_STATUS    => UserManager::STATUS_WAIT_CHANGE_PASSWORD
        ];
        
        $res = $this->UserManager->MyOrm->update($userID, $values);
        $this->AjaxPlugin->success($res);
    }
    
}
