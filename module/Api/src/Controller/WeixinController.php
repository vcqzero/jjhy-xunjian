<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Service\Server\Weixiner;

class WeixinController extends AbstractActionController
{
    private $Weixiner;
    const STATE_REGISTER = 'REGISTER';
    
    public function __construct(
        Weixiner $Weixiner
        )
    {
        $this->Weixiner = $Weixiner;
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
    
    public function getWxConfigAction()
    {
        $url = $this->params()->fromPost('url');
        $config = $this->Weixiner->getWxConfig($url);
        echo json_encode($config);
        exit();
    }
    
    public function oauthAction()
    {
        $code = $this->params()->fromQuery('code');
        $state= $this->params()->fromQuery('state');
        $openid = $this->Weixiner->getOpenid($code);
        $host = $_SERVER['HTTP_HOST'];
        switch ($state)
        {
            case self::STATE_REGISTER :
                $route = "$host/register";
                break;
            default:
                // DEBUG INFORMATION START
                echo '------debug start------<br/>';
                echo "<pre>";
                var_dump(__METHOD__ . ' on line: ' . __LINE__);
                var_dump($openid);
                echo "</pre>";
                exit('------debug end------');
                // DEBUG INFORMATION END
        }
        
        $this->redirect()->toRoute($route, ['action'=>'index'], [
            'query'=>[
                'openid'=>$openid
            ],
            ]);
        return $this->getResponse();//disable return view 
    }
}
