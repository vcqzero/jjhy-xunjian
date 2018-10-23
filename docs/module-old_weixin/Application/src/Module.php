<?php
/**
* 功能如下：
* 1 先判断是否是开发者模式；
* 2 获取用户openid
*   绑定监听
* 3 程序启动时获取token
* 4 程序启动时获取用户openid
* 5 错误处理操作
*/
namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Application;
use Zend\Session\Container;
use Admin\Service\OAuthManager;
use Application\Service\UserManager;
use Admin\Service\TokenManager;

class Module
{
    public $isdebug = false;
    public $debug   = [];
    const VERSION   = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /* 定义事件 */
    function onBootstrap(MvcEvent $e)
    {
        $app        = $e->getApplication(); // 通过event获取application
        $evt        = $app->getEventManager();
        $container  = $app->getServiceManager();
        $config     = $container->get('config');
        
        //判断是否在开发者模式
        $this->checkIsDebug($config);
        
        //update token of youzan or weixin
        $evt->attach(MvcEvent::EVENT_DISPATCH, array($this,'onDispatchToken'), 100);
        
        //get user's openid
        $evt->getSharedManager()->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, [$this,'onDispatchIdentity'], 100);
        
        // 400 or 500 error use zhe blank layout
        $evt->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this,'onError'), 100);
        $evt->attach(MvcEvent::EVENT_RENDER_ERROR, array($this,'onError'), 100);
        
        
    }
    
    private function checkIsDebug(array $config)
    {
        if (array_key_exists('debug', $config))
        {
            $debug  =$config['debug'];
            $this   ->debug=$debug;
            if (array_key_exists('isdebug', $debug))
            {
                $this->isdebug=$debug['isdebug'];
            }
        }
    }
    
    // 当发生zend framework 级别错误时需要记录错误信息error日志中
    // 不包括404级别错误
    function onError(MvcEvent $e)
    {
        $exception      = $e->getParam('exception');
        if ($exception != null)
        {
            $exceptionName = $exception->getMessage();
            $file       = $exception->getFile();
            $line       = $exception->getLine();
            $stackTrace = $exception->getTraceAsString();
            
            $errorMessage   = $e->getError();
            $controllerName = $e->getController();
            
            $debug_mess = "An error occurred \r\n";
            $debug_mess = $debug_mess . "File:\r\n" . " $file : on line $line \r\n";
            $debug_mess = $debug_mess . "Message:\r\n" . $exceptionName . "\r\n";
            $debug_mess = $debug_mess . "Stack trace: \r\n" . $stackTrace;
            
            $log_debug  = $e->getApplication()->getServiceManager()->get('MyLoggerDebug');
            $log_debug  ->log(\Zend\Log\Logger::DEBUG, $debug_mess);
            $log_debug  ->log(\Zend\Log\Logger::DEBUG, "-------end--------\r\n");
            //             $log_debug  = null;
        }
    }
    
    /**
    * @类简述:程序启动时需要更新youzan和weixin token
    * @debug:
    */
    public function onDispatchToken(MvcEvent $e)
    {
        if (!empty($this->isdebug))
        {
            return ;
        }
        
        $container          = $e->getApplication()->getServiceManager();
        $tokenManager       = $container->get(TokenManager::class);
        $tokenManager       ->updateToken('weixin');
        $tokenManager       ->updateToken('youzan');
    }
    /**
     * get openid
     *
     * @param            
     * @return
     */
    public function onDispatchIdentity(MvcEvent $e)
    {
        //获取用户openid
        $openid     = $this->getOpenidfromSessionOrDebug($e);
        
        $container  = $e->getApplication()->getServiceManager();
        
        if (empty($openid))//需要从weixin服务器获取openid信息
        {
            $oautnManager       = $container->get(OAuthManager::class);
            
            if (empty($oautnManager instanceof OAuthManager))
            {
                throw new \Exception('系统错误，请稍后重试');
            }
            
            $route      = $e->getRouteMatch()->getMatchedRouteName();
            $controller = $e->getRouteMatch()->getParam('controller', '');
            $action     = $e->getRouteMatch()->getParam('action', '');
            
            $state  = $route . '0' . $action;
            //get the user's openid
            $url    = $oautnManager->getUrlForOAuth($state);
            header('HTTP/1.1 301 Moved permanently');
            header("location: $url");
            exit();
        }
        
        if (!empty($openid)) 
        {
            //create user if not exists
            $dbAdapter  = $container->get('Zend\Db\Adapter\Adapter');
            $logger     = $container->get('MyLoggerDebug');
            $user       = $container->get(\Application\Service\UserManager::class);
            $user       ->createUserIfNotExists($openid);
        }
    }

    /**
     * 读取openid，
     * 如果是开发者模式，则需要从debug中读取
     *
     * @param object MvcEvent            
     * @return $openid
     */
    protected function getOpenidfromSessionOrDebug(MvcEvent $e)
    {
        $container          = $e->getApplication()->getServiceManager();
        $mySession          = $container->get('mySession');
        
        if ($this->isdebug)
        {
            $debug =$this->debug;
            $openid= array_key_exists('openid', $debug) ? $debug['openid'] : '';
            $mySession->openid=$openid;
            return $openid;
        }else 
        {
            return $mySession->openid;
        }
    }
}
