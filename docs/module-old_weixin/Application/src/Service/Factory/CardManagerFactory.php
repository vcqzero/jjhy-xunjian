<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\CardManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class CardManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $commonModle= $container->get(\Application\Model\CommonModel::class);
        $mySession  = $container->get('mySession');
        $logger     = $container->get('MyLoggerDebug');
        return new CardManager($commonModle, $logger);
    }
}