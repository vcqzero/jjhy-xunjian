<?php
namespace Admin;

// use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Hostname;
use Api\Service\UserManager;

return [
    'router' => [
        'routes' => [
            //以下路由只有在规定的域名下才会匹配
            "xunluo.jjhycom.cn" => [
                'type' => Hostname::class,
                'options' => [
                    'route' => ':subdomain.jjhycom.cn',
                    'constraints' => [
                        'subdomain' => 'xunluo'
                    ],
                    'defaults' => [
                        'subdomain' => 'xunluo'
                    ],
                ],
                
                'child_routes'=>[
                    'home' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    
                    //用户认证
                    'auth' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/auth[/:action]',
                            'constraints' => [//设置router规则
                                'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                            ],
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    
                    //系统管理员
                    'user' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/user[/:action][/:userID]',
                            'constraints' => [//设置router规则
                                'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                                'userID' => '[0-9]*',//字母和数字组成 字母开头
                            ],
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'index',
                                'userID'=> '0',
                            ],
                        ],
                    ],
                    
                    //工地巡检员
                    'guard' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/guard[/:action][/:userID]',
                            'constraints' => [//设置router规则
                                'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                                'userID' => '[0-9]*',//字母和数字组成 字母开头
                            ],
                            'defaults' => [
                                'controller' => Controller\GuardController::class,
                                'action'     => 'index',
                                'userID'=> '0',
                            ],
                        ],
                    ],
                    
                    //工地管理
                    'workyard' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/workyard[/:action][/:workyardID]',
                            'constraints' => [//设置router规则
                                'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                                'userID' => '[0-9]*',//字母和数字组成 字母开头
                            ],
                            'defaults' => [
                                'controller' => Controller\WorkyardController::class,
                                'action'     => 'index',
                                'userID'=> '0',
                            ],
                        ],
                    ],
                    //站点设置
                    'website' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/website[/:action]',
                            'constraints' => [//设置router规则
                                'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                            ],
                            'defaults' => [
                                'controller' => Controller\WebsiteController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    //个人中心
                    'account' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/account[/:action]',
                            'constraints' => [//设置router规则
                                'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                            ],
                            'defaults' => [
                                'controller' => Controller\AccountController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    
                    //巡检点管理
                    'point' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/point[/:action][/:workyardID][/:pointID]',
                            'constraints' => [//设置router规则
                                'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                            ],
                            'defaults' => [
                                'controller' => Controller\PointController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    //值班班次类型管理
                    'shiftType' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/shiftType[/:action][/:typeID]',
                            'constraints' => [//设置router规则
                                'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                            ],
                            'defaults' => [
                                'controller' => Controller\ShiftTypeController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    
                    //值班表安排
                    'shift' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/shift[/:action][/:shiftID]',
                            'constraints' => [//设置router规则
                                'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                            ],
                            'defaults' => [
                                'controller' => Controller\ShiftController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    
                    //值班考勤记录
                    'shift-guard' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/shiftGuard[/:action][/:shiftID][/:guardID]',
                            'constraints' => [//设置router规则
                                'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                            ],
                            'defaults' => [
                                'controller' => Controller\ShiftGuardController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
			
		    'register' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/register[/:action][/:id]',
                            'constraints' => [//设置router规则
                                'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                            ],
                            'defaults' => [
                                'controller' => Controller\RegisterController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],

                    
                ],//child_routes end
            ],//admin route end
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class   => Controller\Factory\IndexControllerFactory::class,
            Controller\WebsiteController::class => Controller\Factory\WebsiteControllerFactory::class,
            Controller\AuthController::class    => Controller\Factory\AuthControllerFactory::class,
            Controller\UserController::class    => Controller\Factory\UserControllerFactory::class,
            Controller\WorkyardController::class=> Controller\Factory\WorkyardControllerFactory::class,
            Controller\AccountController::class=> Controller\Factory\AccountControllerFactory::class,
            Controller\PointController::class=> Controller\Factory\PointControllerFactory::class,
            Controller\GuardController::class=> Controller\Factory\GuardControllerFactory::class,
            Controller\ShiftTypeController::class=> Controller\Factory\ShiftTypeControllerFactory::class,
            Controller\ShiftController::class=> Controller\Factory\ShiftControllerFactory::class,
            Controller\ShiftGuardController::class=> Controller\Factory\ShiftGuardControllerFactory::class,
	    Controller\RegisterController::class=> Controller\Factory\RegisterControllerFactory::class,
        ],
        
    ],
    
    'permission' => [
        Controller\IndexController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
                UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
        Controller\WebsiteController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
            ],
        ],
        Controller\AuthController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
                UserManager::ROLE_WORKYARD_ADMIN,
                UserManager::ROLE_GUEST,
            ],
        ],
        Controller\UserController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
//                 UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
        Controller\WorkyardController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
            ],
        ],
        Controller\AccountController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
                UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
        Controller\PointController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
                UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
        Controller\GuardController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
                UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
        Controller\ShiftTypeController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
                UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
        Controller\ShiftController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
                UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
        Controller\ShiftGuardController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
                UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
	Controller\RegisterController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
            ],
        ],

    ],
    
    'view_helpers' => [
        'factories' => [
            View\Helper\NavbarHelper::class => View\Helper\Factory\NavbarHelperFactory::class,
        ],
        
        'aliases' => [
            'Navbar' => View\Helper\NavbarHelper::class,
        ],
    ],
    
    'view_manager' => [
        'doctype'                  => 'HTML5',
//         'not_found_template'       => 'error/404',
//         'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/admin/layout.phtml',
            //             'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
//             'error/404'               => __DIR__ . '/../view/error/my404.phtml',
//             'error/index'             => __DIR__ . '/../view/error/my500.phtml',
        ],
        'template_path_stack' => [
            'admin' => __DIR__ . '/../view',
        ],
    ],
];
