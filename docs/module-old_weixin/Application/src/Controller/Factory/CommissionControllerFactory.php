<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\CommissionController;
use Application\Service\SellerManager;
use Application\Service\CommissionManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class CommissionControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $commission = $container->get(CommissionManager::class);
        $mySession  = $container->get('mySession');
        $seller     = $container->get(SellerManager::class);
        return new CommissionController($commission, $mySession, $seller);
    }
}