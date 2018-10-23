<?php
namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\PushController;
use Admin\Service\PushManager;
use Admin\Service\TradePushManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class PushControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $logger=$container->get('MyLoggerDebug');
        $push  =new PushManager($logger);
        $trade =$container->get(TradePushManager::class);
        
        return new PushController($push, $trade);
    }
}