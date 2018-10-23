<?php
namespace Application\Model\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Sql\Sql;
use Application\Model\CommonModel;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class CommonModelFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter  = $container->get('Zend\Db\Adapter\Adapter');
        $sql        = new Sql($dbAdapter);
        $logger     = $container->get('MyLoggerDebug');
        return CommonModel::getInstanse($sql, $dbAdapter, $logger);
    }
}