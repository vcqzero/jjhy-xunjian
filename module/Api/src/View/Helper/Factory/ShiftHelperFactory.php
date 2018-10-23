<?php
namespace Api\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\View\Helper\ShiftHelper;
use Api\Service\ShiftManager;
use Api\Service\UserManager;
use Api\Service\ShiftGuardManager;

/**
 * This is the factory for Access view helper. Its purpose is to instantiate the helper
 * and inject dependencies into its constructor.
 */
class ShiftHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        return new ShiftHelper(
            $container->get(ShiftManager::class),
            $container->get(ShiftGuardManager::class),
            $container->get(UserManager::class)
            );
    }
}


