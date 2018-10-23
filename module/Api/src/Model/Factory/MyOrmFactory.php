<?php
namespace Api\Model\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Model\MyOrm;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class MyOrmFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $DbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $Logger    = $container->get('MyLoggerDebug');
        return new MyOrm($DbAdapter, $Logger);
    }
}