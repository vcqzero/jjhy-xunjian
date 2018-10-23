<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\WithdrawController;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class WithdrawControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter  = $container->get('Zend\Db\Adapter\Adapter');
        $myToken    = $container->get(\Application\Service\MyTokenManager::class);
        $mySession  = $container->get('mySession');
        $card       = $container->get(\Application\Service\CardManager::class);
        $withdraw   = $container->get(\Application\Service\WithdrawManager::class);
        return new WithdrawController($mySession, $withdraw, $card, $myToken);
    }
}