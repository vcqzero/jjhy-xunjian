<?php
namespace Guard\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Guard\Controller\ShiftController;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class ShiftControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ShiftController();
    }
}