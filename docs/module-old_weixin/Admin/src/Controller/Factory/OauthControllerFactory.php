<?php
namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\OauthController;
use Admin\Service\OAuthManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class OauthControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $oauth      = $container->get(OAuthManager::class);
        $mySessionr = $container->get('mySession');
        return new OauthController($oauth, $mySessionr);
    }
}