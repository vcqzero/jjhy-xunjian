<?php
namespace Admin\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Service\PointManager;
use Application\Model\CommonModel;
use Application\Service\UserManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class PointManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $CommonModel=$container->get(CommonModel::class);
        $user       =$container->get(UserManager::class);
        $logger     =$container->get('MyLoggerDebug');
        return new PointManager($CommonModel, $user, $logger);
    }
}