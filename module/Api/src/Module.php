<?php
/**
 * @link      http://github.com/zendframework/Web for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Api;

use Zend\Mvc\MvcEvent;
use Api\Service\InitServer;
use Zend\Authentication\AuthenticationService;
use Api\Service\UserManager;
use Api\Entity\UserEntity;
use Api\Controller\Plugin\AclPlugin;
use Admin\Controller\AuthController;
use Zend\Router\RouteMatch;
use Api\Service\Server\AclPermissioner;

class Module
{
    const ROUTE_NOT_LOGIN       = 'NOT_LOGIN';
    const ROUTE_NO_PERMISSION   = 'NO_PERMISSION';
    const ROUTE_CHANGE_PASSWORD = 'CHANGE_PASSWORD';
    
    //定义请求是那种类型
    const REQUET_FROM_ADMIN_PC = 'admin';//来自pc端请求
    const REQUET_FROM_GUARD_WEIXIN = 'guard';//来自微信端请求
    const REQUET_FROM_API = 'api';//请求api
    
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
    * 
    * @var UserEntity
    */
    private $UserEntity;
    
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    /**
    * 当系统启动时首先运行onBootstrap程序
    * 在其他module中不要定义onBootstrap程序了。
    * 
    * @param  
    * @return        
    */
    public function onBootstrap(MvcEvent $e)
    {
        $app        = $e->getApplication();
        $evt        = $app->getEventManager();
        $container  = $app->getServiceManager();
        $this->container = $container;
        $log_debug  = $container->get('MyLoggerDebug');
        
        //关于错误信息处理
        //处理php原生错误信息
        $this->logPhpError($log_debug);
        //处理本框架错误信息
        $evt->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'logDispatchError'), 100);
        $evt->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'logDispatchError'), 100);
        
        //程序初始化
        $evt->attach(MvcEvent::EVENT_DISPATCH, array($this, 'doInit'), 100);
        
        //判断用户是否登录，如果未登录则进入登录页面
        $evt->attach(MvcEvent::EVENT_DISPATCH,
            [$this, 'onDispatchIdentity'],
            100);
    }
    
    //将php原始的错误信息记录到debug中
    public function logPhpError($log_debug)
    {
        \Zend\Log\Logger::registerErrorHandler($log_debug);
        \Zend\Log\Logger::registerExceptionHandler($log_debug);
        \Zend\Log\Logger::registerFatalErrorShutdownFunction($log_debug);
    }
    
    //当发生404或500错误时,记录错误信息到error中
    public function logDispatchError(MvcEvent $e)
    {
        $exception      = $e->getParam('exception');
        //不记录404级别错误
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
        }
        //当发生错误时，直接显示404或500错误页面，不用layout
        $vm = $e->getViewModel();
        $vm->setTemplate('layout/blank');
    }
    
    //程序初始化
    public function doInit(MvcEvent $e)
    {
        $container  = $e->getApplication()->getServiceManager();
        $initServer = $container->get(InitServer::class);
    }
    /**
     *
     * @param
     * @return UserManager
     */
    public function getUserManager()
    {
        $container = $this->container;
        return $container->get(UserManager::class);
    }
    
    /**
     *
     * @param
     * @return AuthenticationService
     */
    public function getAuthService() {
        $container = $this->container;
        return $container->get(AuthenticationService::class);
    }
    
    /**
     *
     * @param
     * @return AclPlugin
     */
    public function getAclPlugin()
    {
        $container = $this->container;
        return $container->get(AclPermissioner::class);
    }
        
    /**
     * 判断用户是否登录
     * 未登录->登录
     * 已登录->do no thing
     */
    public function onDispatchIdentity(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        
        //获取请求来自何处
        //通过二级域名判断，如果没有则默认为api
        $request_from  = $routeMatch->getParam('subdomain', self::REQUET_FROM_API);
        
        //是否有权限
        $role       = $this->getRole();
        $controller = $routeMatch->getParam('controller');
        $isAllow = $this->isAllow($role, $controller);
        //是否登录
        $hasIdentity = $this->hasIdentity();
        
        //如果请求来自api
        //判断是否有权限，如无权限exit
        if ($request_from == self::REQUET_FROM_API)
        {
            if (!$isAllow)
            {
                exit('deny');
            }
            return ;
        }
        
        //下面判断请求来自非api的情况
        
        
        //如果没有权限且没有登录直接进入登录页面
        //进入用户登录页面
        if (!$isAllow && !$hasIdentity)
        {
            return $this->setRoutMatch($routeMatch, self::ROUTE_NOT_LOGIN);
        }
        
        //如果没有权限且登录了
        //进入权限限制页面
        if (!$isAllow && $hasIdentity)
        {
            return $this->setRoutMatch($routeMatch, self::ROUTE_NO_PERMISSION);
        }
        
        //如果有权限且未登录
        if ($isAllow && !$hasIdentity)
        {
            //进入guest可以进入的角色
            //不需操作
            return ;
        }
        
        //如果有权限且登录
        if ($isAllow && $hasIdentity)
        {
            //需要验证用户的status
            $UserEntity = $this->getUserEntity();
            $status = $UserEntity->getStatus();
            //如果用户状态异常，进入相应页面
            //进入重新修改页面
            if ($status == UserManager::STATUS_WAIT_CHANGE_PASSWORD)
            {
                return $this->setRoutMatch($routeMatch, self::ROUTE_CHANGE_PASSWORD);
            }
        }
    }
    
    private function getUserEntity()
    {
        if (empty($this->UserEntity))
        {
            $authService= $this->getAuthService();
            $identity = $authService->getIdentity();
            $UserManager = $this->getUserManager();
            $this->UserEntity  = $UserManager->findUserByIdentity($identity);
        }
        return $this->UserEntity;
    }
    private function getRole()
    {
        $UserEntity = $this->getUserEntity();
        $role = $UserEntity->getRole();
        return $role ? $role : UserManager::ROLE_GUEST;
    }
    
    private function hasIdentity()
    {
        $authService= $this->getAuthService();
        return $authService->hasIdentity();
    }
    
    private function isAllow($role, $controller)
    {
        $AclPlugin= $this->getAclPlugin();
        $Acl = $AclPlugin->getAcl();
        if (!$Acl->hasResource($controller))
        {
            return false;
        }
        if (!$Acl->hasRole($role))
        {
            return false;
        }
        return $Acl->isAllowed($role, $controller);
    }
    
    private function setRoutMatch(RouteMatch $routeMatch, $route)
    {
        //比如pc端和手机端的subdomain不同
        $subdomain  = $routeMatch->getParam('subdomain', 'api');
        
        switch ($subdomain.$route)
        {
            case self::REQUET_FROM_ADMIN_PC.self::ROUTE_CHANGE_PASSWORD:
                $controller = AuthController::class;
                $action     = 'changePassword';
                break;
            case self::REQUET_FROM_ADMIN_PC.self::ROUTE_NO_PERMISSION:
                $controller = AuthController::class;
                $action     = 'noPermission';
                break;
            case self::REQUET_FROM_ADMIN_PC.self::ROUTE_NOT_LOGIN:
                $controller = AuthController::class;
                $action     = 'index';
                break;
            default:
                exit("系统错误" . __FILE__);
        }
        
        $routeMatch->setParam('controller', $controller)
                    ->setParam('action', $action);
        
        return $routeMatch;
    }
}
