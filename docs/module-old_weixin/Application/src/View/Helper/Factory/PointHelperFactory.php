<?php
namespace Application\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\View\Helper\PointHelper;
use Application\Model\CommonModel;

/**
 * This is the factory for Access view helper.
 * Its purpose is to instantiate the helper
 * and inject dependencies into its constructor.
 */
class PointHelperFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $CommonModel    = $container->get(CommonModel::class);
        return new PointHelper($CommonModel);
    }
}


