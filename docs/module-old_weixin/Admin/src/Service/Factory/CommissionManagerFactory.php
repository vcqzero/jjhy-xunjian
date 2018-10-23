<?php
namespace Admin\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Model\CommonModel;
use Application\Service\UserManager;
use Admin\Service\CommissionManager;
use Application\Service\SellerManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class CommissionManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $seller     = $container ->get(SellerManager::class);
        $CommonModel= $container->get(CommonModel::class);
        $user       = $container->get(UserManager::class);
        $logger     = $container->get('MyLoggerDebug');
        return new CommissionManager($seller, $CommonModel, $user, $logger);
    }
}