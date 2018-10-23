<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\WithdrawManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class WithdrawManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $commonModel= $container->get(\Application\Model\CommonModel::class);
        $mySession  = $container->get('mySession');
        $logger     = $container->get('MyLoggerDebug');
        $user       = $container->get(\Application\Service\UserManager::class);
        $card       = $container->get(\Application\Service\CardManager::class);
        return new WithdrawManager($commonModel, $user, $card, $logger);
    }
}