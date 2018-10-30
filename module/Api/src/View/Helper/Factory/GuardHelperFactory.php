<?php
namespace Api\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Service\UserManager;
use Api\Service\ShiftGuardManager;

/**
 * This is the factory for Access view helper. Its purpose is to instantiate the helper
 * and inject dependencies into its constructor.
 */
class GuardHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        return new \Api\View\Helper\GuardHelper(
            $container->get(UserManager::class),
            $container->get(ShiftGuardManager::class)
            );
    }
}


