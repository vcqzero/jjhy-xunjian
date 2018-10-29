<?php
namespace Api\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\View\Helper\PointHelper;
use Api\Service\PointManager;
use Api\Service\ShiftManager;

/**
 * This is the factory for Access view helper. Its purpose is to instantiate the helper
 * and inject dependencies into its constructor.
 */
class PointHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        return new PointHelper(
            $container->get(PointManager::class),
            $container->get(ShiftManager::class)
            );
    }
}


