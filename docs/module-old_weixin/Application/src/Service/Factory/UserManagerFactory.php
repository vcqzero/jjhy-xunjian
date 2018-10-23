<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\UserManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class UserManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $logger     = $container->get('MyLoggerDebug');
        $card       = $container->get(\Application\Service\CardManager::class);
        $commonModle= $container->get(\Application\Model\CommonModel::class);
        return new UserManager($commonModle, $card, $logger);
    }
}