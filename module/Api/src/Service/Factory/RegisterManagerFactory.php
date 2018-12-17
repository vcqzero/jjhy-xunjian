<?php
namespace Api\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Filter\FormFilter;
use Api\Model\MyOrm;
use Api\Service\RegisterManager;
use Api\Service\Server\Weixiner;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class RegisterManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new RegisterManager(
            $container->get(MyOrm::class),
            $container->get(FormFilter::class),
            $container->get(Weixiner::class)
            );
    }
}