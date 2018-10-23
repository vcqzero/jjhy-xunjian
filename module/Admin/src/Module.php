<?php
/**
 * @link      http://github.com/zendframework/Web for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\Mvc\MvcEvent;
use Interop\Container\ContainerInterface;
// use Api\Service\UserManager;

class Module
{
    /**
    * @var ContainerInterface
    */
    private $container;
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    /* 定义事件 */
    function onBootstrap(MvcEvent $e)
    {
//         $evt = $e->getApplication()->getEventManager();
//         $this->container  = $e->getApplication()->getServiceManager();
    }
}
