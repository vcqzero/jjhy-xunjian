<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\RecordWithdrawController;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class RecordWithdrawControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $record     = $container->get(\Application\Service\RecordWithdrawManager::class);
        $mySession  = $container->get('mySession');
        return new RecordWithdrawController($record, $mySession);
    }
}