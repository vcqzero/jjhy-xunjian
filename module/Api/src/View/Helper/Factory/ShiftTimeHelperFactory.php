<?php
namespace Api\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\View\Helper\ShiftTimeHelper;
use Api\Service\ShiftTimeManager;

/**
 * This is the factory for Access view helper. Its purpose is to instantiate the helper
 * and inject dependencies into its constructor.
 */
class ShiftTimeHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        return new ShiftTimeHelper(
            $container->get(ShiftTimeManager::class)
            );
    }
}


