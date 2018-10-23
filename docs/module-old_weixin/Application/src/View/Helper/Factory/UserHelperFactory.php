<?php
namespace Application\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\View\Helper\UserHelper;
use Application\Service\UserManager;

/**
 * This is the factory for Access view helper.
 * Its purpose is to instantiate the helper
 * and inject dependencies into its constructor.
 */
class UserHelperFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mySession = $container->get('mySession');
        $user      = $container->get(UserManager::class); 
        return new UserHelper($mySession, $user);
    }
}


