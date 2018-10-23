<?php
/**
 * @link      http://github.com/zendframework/Web for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Guard;

use Zend\Mvc\MvcEvent;
use Interop\Container\ContainerInterface;

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
    public function onBootstrap(MvcEvent $e)
    {
        $config = $e->getApplication()->getServiceManager()->get('config');
        
//         // DEBUG INFORMATION START
//         echo '------debug start------<br/>';
//         echo "<pre>";
//         var_dump(__METHOD__ . ' on line: ' . __LINE__);
//         var_dump($config['view_manager']);
//         echo "</pre>";
//         exit('------debug end------');
//         // DEBUG INFORMATION END
        
    }
}
