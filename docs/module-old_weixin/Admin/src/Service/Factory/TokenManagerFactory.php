<?php
namespace Admin\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Service\TokenManager;

/**
 * This is the factory for IndexController.
 * Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class TokenManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config     =$container->get('config');
        $test_config=array_key_exists('test_config', $config) ? $config['test_config'] : [];
        return TokenManager::getInstace($test_config);
    }
}