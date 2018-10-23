<?php
namespace Api\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Filter\FormFilter;
use Api\Model\MyOrm;
use Api\Service\PointManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class PointManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PointManager(
            $container->get(MyOrm::class),
            $container->get(FormFilter::class)
            );
    }
}