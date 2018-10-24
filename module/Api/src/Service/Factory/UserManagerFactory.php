<?php
namespace Api\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Service\UserManager;
use Api\Entity\UserEntity;
use Api\Filter\FormFilter;
use Api\Model\MyOrm;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class UserManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        //MyOrm
        $UserEntity = new UserEntity();
        $MyOrm = $container->get(MyOrm::class);
        $MyOrm  ->setEntity($UserEntity);
        $MyOrm  ->setTableName($UserEntity::TABLE_NAME);
        
        //FormFilter
        $FormFilter = $container->get(FormFilter::class);
        $FormFilter ->setRules(include 'module/Api/src/Filter/rules/User.php');
        
        //super admin config
        $config = $container->get('config');
        $super_admin_config = $config['super_admin'];
        $UserManager = new UserManager(
            $MyOrm,
            $FormFilter,
            $super_admin_config
            );
        
        return  $UserManager;
    }
}