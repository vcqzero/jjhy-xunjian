<?php
namespace Admin;

// use Zend\ServiceManager\Factory\InvokableFactory;
// use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
return [
    'router' => [
        'routes' => [
            
            'admin' => [
                'type' => Segment::class,
                'options' => [
                    // Change this to something specific to your module
                    'route' => '/admin[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            
            // for weixin
            'oauth' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/oauth[/:action]',
                    'constraints' => [ // 设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*' // 字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\OauthController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            
            'push' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/push[/:action]',
                    'constraints' => [ // 设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*' // 字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\PushController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            
            'signature' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/signature[/:action]',
                    'constraints' => [ // 设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*' // 字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\SignatureController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            'timing' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/timing[/:action]',
                    'constraints' => [ // 设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*' // 字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\TimingController::class,
                        'action' => 'index'
                    ]
                ]
            ],
        ]
    ],
    
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\OauthController::class => Controller\Factory\OauthControllerFactory::class,
            Controller\PushController::class => Controller\Factory\PushControllerFactory::class,
            Controller\SignatureController::class => Controller\Factory\SignatureControllerFactory::class,
            Controller\TimingController::class => Controller\Factory\TimingControllerFactory::class,
        ]
    ],
    
    'service_manager' => [
        'factories' => [
            Service\TokenManager::class => Service\Factory\TokenManagerFactory::class,
            Service\OAuthManager::class => Service\Factory\OauthManagerFactory::class,
            Service\TradePushManager::class => Service\Factory\TradePushManagerFactory::class,
            Service\OAuthManager::class => Service\Factory\OauthManagerFactory::class,
            Service\SignatureManager::class => Service\Factory\SignatureManagerFactory::class,
            Service\PointManager::class => Service\Factory\PointManagerFactory::class,
            Service\CommissionManager::class => Service\Factory\CommissionManagerFactory::class,
        ]
    ],
    
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view'
        ]
    ]
];
