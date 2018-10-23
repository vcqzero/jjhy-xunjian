<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\SellerController;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class SellerControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mySession      = $container->get('mySession');
        $myTokenManager =$container->get(\Application\Service\MyTokenManager::class);
        $seller         =$container->get(\Application\Service\SellerManager::class);
        
        return new SellerController($seller, $myTokenManager, $mySession);
    }
}