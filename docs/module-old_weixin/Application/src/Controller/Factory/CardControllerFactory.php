<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\CardController;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class CardControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter  = $container->get('Zend\Db\Adapter\Adapter');
        $mySession  = $container->get('mySession');
        $logger     = $container->get('MyLoggerDebug');
        $myTokenManager=$container->get(\Application\Service\MyTokenManager::class);
        $card       =$container->get(\Application\Service\CardManager::class);
        
        return new CardController($dbAdapter, $mySession, $myTokenManager, $card);
    }
}