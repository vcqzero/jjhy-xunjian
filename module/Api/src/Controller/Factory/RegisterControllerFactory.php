<?php
namespace Api\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Controller\IndexController;
use Api\Controller\Plugin\AuthPlugin;
use Api\Controller\Plugin\AjaxPlugin;
use Api\Controller\RegisterController;
use Api\Service\RegisterManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class RegisterControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new RegisterController(
            $container->get(AuthPlugin::class),
            $container->get(AjaxPlugin::class),
            $container->get(RegisterManager::class)
            );
    }
}