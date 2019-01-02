<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Api\Controller\Plugin\AuthPlugin;
use Api\Controller\Plugin\AjaxPlugin;
use Api\Service\RegisterManager;
use Api\Service\WorkyardManager;
use Api\Service\UserManager;
use Api\Entity\UserEntity;

class RegisterController extends AbstractActionController
{
    private $AuthPlugin;
    private $AjaxPlugin;
    private $RegisterManager;
    private $WorkyardManager;
    private $UserManager;
    public function __construct(
        AuthPlugin $AuthPlugin,
        AjaxPlugin $AjaxPlugin,
        RegisterManager $RegisterManager,
        WorkyardManager $WorkyardManager,
        UserManager $UserManager
        )
    {
        $this->AuthPlugin = $AuthPlugin;
        $this->AjaxPlugin = $AjaxPlugin;
        $this->RegisterManager = $RegisterManager;
        $this->WorkyardManager = $WorkyardManager;
        $this->UserManager     = $UserManager;
    }
    
    public function indexAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        
        $values = $this->params()->fromPost();
        $values['created_at'] = time();
        $values['status'] = RegisterManager::STATUS_APPLIYING;
        //do filter
        $values = $this->RegisterManager->FormFilter->getFilterValues($values);
        //save
        $res = $this->RegisterManager->MyOrm->insert($values);
        if($res) {
            $id = $this->RegisterManager->MyOrm->getLastInsertId();
            $this->RegisterManager->sentMsgToAdmin($id);
        }
        $this->ajax()->success($res);
    }
    
    public function successAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        $values = $this->params()->fromPost();
        /* 1 add workyard */
        $values_workyard = $this->WorkyardManager->FormFilter->getFilterValues($values);
        $this->WorkyardManager->MyOrm->insert($values_workyard);
        $workyard_id     = $this->WorkyardManager->MyOrm->getLastInsertId();
        /* 2 add user */
        $values[UserEntity::FILED_WORKYARD_ID] = $workyard_id;
        $values[UserEntity::FILED_STATUS] = UserManager::STATUS_ENABLED;
        $values[UserEntity::FILED_ROLE] = UserManager::ROLE_WORKYARD_ADMIN;
        $values[UserEntity::FILED_PASSWORD] = $this->UserManager->password_hash(UserManager::DEFUALT_PASSWORD);
        $values_user = $this->UserManager->FormFilter->getFilterValues($values);
        $user_id = $this->UserManager->MyOrm->insert($values_user);
        /* 3 update register */
        $id = $this->params()->fromRoute('id');
        $set = [
            'admin_username' => $values_user['username'],
            'admin_password' => UserManager::DEFUALT_PASSWORD,
            'status' => RegisterManager::STATUS_SUCCESS
        ];
        //do filter
        $set= $this->RegisterManager->FormFilter->getFilterValues($set);
        //save
        $res = $this->RegisterManager->MyOrm->update($id, $set);
        //response
        $this->ajax()->close($res);
        //send
        $this->RegisterManager->sendTemplate($id, true);
        exit();
    }
    
    public function refuseAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        $id = $this->params()->fromRoute('id');
        $values = $this->params()->fromPost();
        $values['status'] = RegisterManager::STATUS_REFUSED;
        //do filter
        $values = $this->RegisterManager->FormFilter->getFilterValues($values);
        //save
        $res = $this->RegisterManager->MyOrm->update($id, $values);
        $this->ajax()->close($res);
        //send
        $this->RegisterManager->sendTemplate($id, false);
        exit();
    }
}
