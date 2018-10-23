<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;

// use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\Mvc\Application;


return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            
            'index' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/index[/:action]',
                    'constraints' => [ // 设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*' // 字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            
            'point' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/point[/:action][/:page]',
                    'constraints' => [ // 设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*', // 字母和数字组成 字母开头
                        'page' => '[0-9]*' // 字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\PointController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            
            'withdraw' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/withdraw[/:action][/:page]',
                    'constraints' => [ // 设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*', // 字母和数字组成 字母开头
                        'page' => '[0-9]*' // 字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\WithdrawController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            
            'recordwithdraw' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/recordwithdraw[/:action][/:page]',
                    'constraints' => [ // 设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*', // 字母和数字组成 字母开头
                        'page' => '[0-9]*' // 字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\RecordWithdrawController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            
            'commission' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/commission[/:action][/:page]',
                    'constraints' => [ // 设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*', // 字母和数字组成 字母开头
                        'page' => '[0-9]*' // 字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\CommissionController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            
            'card' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/card[/:action][/:page]',
                    'constraints' => [ // 设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*', // 字母和数字组成 字母开头
                        'page' => '[0-9]*' // 字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\CardController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            
            'seller' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/seller[/:action]',
                    'constraints' => [ // 设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*' // 字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\SellerController::class,
                        'action' => 'index'
                    ]
                ]
            ]
        
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\PointController::class => Controller\Factory\PointControllerFactory::class,
            Controller\WithdrawController::class => Controller\Factory\WithdrawControllerFactory::class,
            Controller\SellerController::class => Controller\Factory\SellerControllerFactory::class,
            Controller\CardController::class => Controller\Factory\CardControllerFactory::class,
            Controller\RecordWithdrawController::class => Controller\Factory\RecordWithdrawControllerFactory::class,
            Controller\CommissionController::class => Controller\Factory\CommissionControllerFactory::class,
        ]
    ],
    
    'service_manager' => [
        'factories' => [            
            Service\MyTokenManager::class => Service\Factory\MyTokenManagerFactory::class,
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
            Service\CardManager::class => Service\Factory\CardManagerFactory::class,
            Service\WithdrawManager::class => Service\Factory\WithdrawManagerFactory::class,
            Model\CommonModel::class => Model\Factory\CommonModelFactory::class,
            Service\RecordWithdrawManager::class => Service\Factory\RecordWithdrawManagerFactory::class,
            Service\SellerManager::class => Service\Factory\SellerManagerFactory::class,
            Service\PointManager::class => Service\Factory\PointManagerFactory::class,
            Service\CommissionManager::class => Service\Factory\CommissionManagerFactory::class,
        ]
    ],
    
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml'
        ],
        'template_path_stack' => [
            __DIR__ . '/../view'
        ]
    ],
    // We register module-provided view helpers under this key.
    'view_helpers' => [
        'factories' => [
            View\Helper\PointHelper::class => View\Helper\Factory\PointHelperFactory::class,
            View\Helper\UserHelper::class => View\Helper\Factory\UserHelperFactory::class,
        ],
        'aliases' => [
            'point' => View\Helper\PointHelper::class,
            'user' => View\Helper\UserHelper::class,
        ]
    ]
];
