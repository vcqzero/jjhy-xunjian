<?php
namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\IndexController;
use Application\Model\CommonModel;
use Admin\Api\YouzanApi;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $commonModule   = $container->get(CommonModel::class);
        $mySession      = $container->get('mySession');
        $logger         =$container->get('MyLoggerDebug');
        $youzan = YouzanApi::getInstanse($logger);
        
        return new IndexController($commonModule, $mySession, $youzan);
    }
}