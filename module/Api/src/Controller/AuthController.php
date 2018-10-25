<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Controller\Plugin\AuthPlugin;
use Api\Controller\Plugin\AjaxPlugin;

class AuthController extends AbstractActionController
{
    private $AuthPlugin;
    private $AjaxPlugin;
    public function __construct(
        AuthPlugin $AuthPlugin,
        AjaxPlugin $AjaxPlugin
        )
    {
        $this->AuthPlugin = $AuthPlugin;
        $this->AjaxPlugin = $AjaxPlugin;
    }
    /**
     * We override the parent class' onDispatch() method to
     * set an alternative layout for all actions in this controller.
     */
    public function onDispatch(MvcEvent $e)
    {
        // Call the base class' onDispatch() first and grab the response
        $response = parent::onDispatch($e);
        
        // Set alternative layout
        $this->layout()->setTemplate('layout/blank.phtml');
        
        // Return the response
        return $response;
    }
    
    //login
    public function loginAction()
    {
//         $tokenJson  = $this->params()->fromPost('tokenJson');
//         if (!$this->token()->isValid($tokenJson))
//         {
//             $this->AjaxPlugin->success(false);
//         }
        //进行登录验证
        $username = $this->params()->fromPost('username');
        $password = $this->params()->fromPost('password');
        
        $isLogin  = $this->AuthPlugin->login($username, $password);
        $error    = $this->AuthPlugin->getError();
        $this->AjaxPlugin->success($isLogin, ['error'=>$error]);
    }
    
    //logout
    public function logoutAction()
    {
        $this->AuthPlugin->logout();
        echo "<script>location='/'</script>";
        exit();
    }
}
