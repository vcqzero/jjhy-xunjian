<?php
namespace Api\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\View\Helper\ShiftTimePointHelper;
use Api\Service\ShiftTimePointManager;

/**
 * This is the factory for Access view helper. Its purpose is to instantiate the helper
 * and inject dependencies into its constructor.
 */
class ShiftTimePointHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        return new ShiftTimePointHelper(
            $container->get(ShiftTimePointManager::class)
            );
    }
}


