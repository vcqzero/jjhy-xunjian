<?php
namespace Api\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Service\ShiftManager;
use Api\Service\UserManager;
use Api\Service\ShiftGuardManager;
use Api\View\Helper\ShiftGuardHelper;

/**
 * This is the factory for Access view helper. Its purpose is to instantiate the helper
 * and inject dependencies into its constructor.
 */
class ShiftGuardHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        return new ShiftGuardHelper(
            $container->get(ShiftManager::class),
            $container->get(ShiftGuardManager::class),
            $container->get(UserManager::class)
            );
    }
}


