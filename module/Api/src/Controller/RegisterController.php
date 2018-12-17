<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Api\Controller\Plugin\AuthPlugin;
use Api\Controller\Plugin\AjaxPlugin;
use Api\Service\RegisterManager;

class RegisterController extends AbstractActionController
{
    private $AuthPlugin;
    private $AjaxPlugin;
    private $RegisterManager;
    public function __construct(
        AuthPlugin $AuthPlugin,
        AjaxPlugin $AjaxPlugin,
        RegisterManager $RegisterManager
        )
    {
        $this->AuthPlugin = $AuthPlugin;
        $this->AjaxPlugin = $AjaxPlugin;
        $this->RegisterManager = $RegisterManager;
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
        $this->ajax()->success($res);
    }
    
    public function successAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        $id = $this->params()->fromRoute('id');
        $values = $this->params()->fromPost();
        $values['status'] = RegisterManager::STATUS_SUCCESS;
        //do filter
        $values = $this->RegisterManager->FormFilter->getFilterValues($values);
        //save
        $res = $this->RegisterManager->MyOrm->update($id, $values);
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
