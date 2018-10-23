<?php
namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\TimingController;
use Admin\Service\PointManager;
use Admin\Service\CommissionManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class TimingControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $point  = $container->get(PointManager::class);
        $commission = $container->get(CommissionManager::class);
        return new TimingController($point, $commission);
    }
}