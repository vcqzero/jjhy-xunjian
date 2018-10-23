<?php
namespace Api\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Filter\FormFilter;
use Api\Service\WorkyardManager;
use Api\Model\MyOrm;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class WorkyardManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new WorkyardManager(
            $container->get(MyOrm::class),
            $container->get(FormFilter::class)
            );
    }
}