<?php
namespace Guard;

// use Zend\ServiceManager\Factory\InvokableFactory;
// use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Hostname;
use Api\Service\UserManager;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            //以下路由只有在规定的域名下才会匹配
            "guard.tanhansi.com" => [
                'type' => Hostname::class,
                'options' => [
                    'route' => ':subdomain.tanhansi.com',
                    'constraints' => [
                        'subdomain' => 'guard'
                    ],
                    'defaults' => [
                        'subdomain' => 'guard'
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
                    
                    'auth' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/auth[/:action]',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    
                    //值班版块
                    'shift' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/shift[/:action][/:shiftID][/:workyardID]',
                            'defaults' => [
                                'controller' => Controller\ShiftController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    
                    //巡检
                    'shiftTime' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/shiftTime[/:action][/:shiftID][/:workyardID]',
                            'defaults' => [
                                'controller' => Controller\ShiftTimeController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    
                    //个人中心
                    'account' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/account[/:action][/:userID]',
                            'defaults' => [
                                'controller' => Controller\AccountController::class,
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
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\ShiftController::class => Controller\Factory\ShiftControllerFactory::class,
            Controller\AccountController::class => Controller\Factory\AccountControllerFactory::class,
            Controller\ShiftTimeController::class => Controller\Factory\ShiftTimeControllerFactory::class,
        ],
        
    ],
    
    'permission' => [
        Controller\IndexController::class => [
            'allow'=> [
                UserManager::ROLE_WORKYARD_GUARD,
                UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
        Controller\AuthController::class => [
            'allow'=> [
                UserManager::ROLE_WORKYARD_GUARD,
                UserManager::ROLE_GUEST,
                UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
        Controller\ShiftController::class => [
            'allow'=> [
                UserManager::ROLE_WORKYARD_GUARD,
                UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
        Controller\AccountController::class => [
            'allow'=> [
                UserManager::ROLE_WORKYARD_GUARD,
                UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
        Controller\ShiftTimeController::class => [
            'allow'=> [
                UserManager::ROLE_WORKYARD_GUARD,
                UserManager::ROLE_WORKYARD_ADMIN,
            ],
        ],
    ],
    
    'view_manager' => [
        'template_path_stack' => [
            'Guard' => __DIR__ . '/../view',
        ],
    ],
    
];
