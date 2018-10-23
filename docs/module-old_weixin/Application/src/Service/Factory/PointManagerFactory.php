<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\PointManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class PointManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $commonModle= $container->get(\Application\Model\CommonModel::class);
        
        return new PointManager($commonModle);
    }
}