<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\SellerManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class SellerManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $commonModel= $container->get(\Application\Model\CommonModel::class);
        $logger     = $container->get('MyLoggerDebug');
        $user       = $container->get(\Application\Service\UserManager::class);
        
        return new SellerManager($commonModel, $user, $logger);
    }
}