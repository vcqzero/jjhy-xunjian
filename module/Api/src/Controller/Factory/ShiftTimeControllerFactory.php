<?php
namespace Api\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Controller\IndexController;
use Api\Controller\ShiftTimeController;
use Api\Service\ShiftTimeManager;
use Api\Service\ShiftTimePointManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class ShiftTimeControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ShiftTimeController(
            $container->get(ShiftTimeManager::class),
            $container->get(ShiftTimePointManager::class)
           );
    }
}