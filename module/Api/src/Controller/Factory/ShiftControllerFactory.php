<?php
namespace Api\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Controller\IndexController;
use Api\Controller\ShiftController;
use Api\Service\ShiftManager;
use Api\Service\ShiftGuardManager;
use Api\Service\ShiftTypeManager;
use Api\View\Helper\ShiftGuardHelper;
use Api\View\Helper\ShiftTimeHelper;
use Api\View\Helper\UserHelper;
use Api\Service\WorkyardManager;
use Api\View\Helper\PointHelper;
use Api\View\Helper\ShiftTimePointHelper;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class ShiftControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ShiftController(
            $container->get(ShiftManager::class),
            $container->get(ShiftGuardManager::class),
            $container->get(ShiftTypeManager::class),
            $container->get(ShiftGuardHelper::class),
            $container->get(ShiftTimeHelper::class),
            $container->get(UserHelper::class),
            $container->get(WorkyardManager::class),
            $container->get(PointHelper::class),
            $container->get(ShiftTimePointHelper::class)
           );
    }
}