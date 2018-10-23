<?php
namespace Admin\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Model\CommonModel;
use Admin\Api\YouzanApi;
use Admin\Service\TradePushManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class TradePushManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $CommonModel=$container->get(CommonModel::class);
        $logger     =$container->get('MyLoggerDebug');
        $youzan     =YouzanApi::getInstanse($logger);
        return new TradePushManager($CommonModel, $youzan);
    }
}