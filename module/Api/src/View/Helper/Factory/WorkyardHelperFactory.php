<?php
namespace Api\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\View\Helper\WorkyardHelper;
use Api\Service\WorkyardManager;

/**
 * This is the factory for Access view helper. Its purpose is to instantiate the helper
 * and inject dependencies into its constructor.
 */
class WorkyardHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        return new WorkyardHelper(
            $container->get(WorkyardManager::class)
            );
    }
}


