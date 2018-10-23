<?php
namespace Api;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;
use Api\Service\UserManager;
// use Zend\Router\Http\Literal;
// use Zend\Router\Http\Hostname;

return [
    'router' => [
        'routes' => [
//             'guard.xunjina.com' => [
//                 'type' => Hostname::class,
//                 'options' => [
//                     'route' => ':subdomain.xunjina.com',
//                     'constraints' => [
//                         /**
//                         * 声明子域名部分
//                         * 只有子域名不同，则路由就会严格按照域名进行匹配
//                         * 
//                         * 举例：
//                         * admin.xxx.com/home 和 custom.xxx.com/home 进入的是不同控制器
//                         */
//                         'subdomain' => 'guard',
//                     ],
//                     'defaults' => [
//                     ],
//                 ],
                
//                 'child_routes'=>[
//                     'home' => [
//                         'type'    => Literal::class,
//                         'options' => [
//                             'route'    => '/',
//                             'defaults' => [
//                                 'controller' => Controller\AuthController::class,
//                                 'action'     => 'index',
//                             ],
//                         ],
//                     ],
//                  ],
//              ],
            
            //进行登录验证
            'api/auth' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/auth[/:action]',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            'api/user' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/user[/:action][/:userID]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'index',
                        'userId'     => '0',
                    ],
                ],
            ],
            
            'api/website' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/website[/:action]',
                    'constraints' => [//设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\WebsiteController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            'api/workyard' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/workyard[/:action][/:workyardID]',
                    'constraints' => [//设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\WorkyardController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            //point
            'api/point' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/point[/:action][/:workyardID][/:pointID]',
                    'defaults' => [
                        'controller' => Controller\PointController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            //shiftType 值班班次类型管理
            'api/shiftType' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/shiftType[/:action][/:typeID]',
                    'defaults' => [
                        'controller' => Controller\ShiftTypeController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            //值班班次
            'api/shift' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/shift[/:action][/:shiftID]',
                    'defaults' => [
                        'controller' => Controller\ShiftController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
        ],
    ],
    
    'controllers' => [
        'factories' => [
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
            Controller\PointController::class => Controller\Factory\PointControllerFactory::class,
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\WebsiteController::class => Controller\Factory\WebsiteControllerFactory::class,
            Controller\WorkyardController::class => Controller\Factory\WorkyardControllerFactory::class,
            Controller\ShiftTypeController::class => Controller\Factory\ShiftTypeControllerFactory::class,
            Controller\ShiftController::class => Controller\Factory\ShiftControllerFactory::class,
        ],
    ],
    'permission' => [
        Controller\UserController::class => [
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
        Controller\AuthController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
                UserManager::ROLE_WORKYARD_ADMIN,
                UserManager::ROLE_WORKYARD_GUARD,
                UserManager::ROLE_GUEST,
            ],
        ],
        Controller\WebsiteController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
            ],
        ],
        Controller\WorkyardController::class => [
            'allow'=> [
                UserManager::ROLE_SUPER_ADMIN,
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
    ],
    
    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\TokenPlugin::class  => Controller\Plugin\Factory\TokenPluginFactory::class,
            Controller\Plugin\AjaxPlugin::class   => Controller\Plugin\Factory\AjaxPluginFactory::class,
            Controller\Plugin\AuthPlugin::class   => Controller\Plugin\Factory\AuthPluginFactory::class,
            Controller\Plugin\DownloadPlugin::class => Controller\Plugin\Factory\DownloadPluginFactory::class,
        ],
        
        'aliases' => [
            'token'      => Controller\Plugin\TokenPlugin::class,
            'ajax'       => Controller\Plugin\AjaxPlugin::class,
            'Download'   => Controller\Plugin\DownloadPlugin::class,
        ],
    ],
    //service_manager
    'service_manager' => [
        'factories' => [
            //Controller\Plugin
            Controller\Plugin\AjaxPlugin::class   => Controller\Plugin\Factory\AjaxPluginFactory::class,
            Controller\Plugin\AuthPlugin::class   => Controller\Plugin\Factory\AuthPluginFactory::class,
            Controller\Plugin\DownloadPlugin::class => Controller\Plugin\Factory\DownloadPluginFactory::class,
            
            //Model\
            \Zend\Authentication\AuthenticationService::class => InvokableFactory::class,
            Model\MyOrm::class              => Model\Factory\MyOrmFactory::class,
            
            //Filter
            Filter\FormFilter::class => Filter\Factory\FormFilterFactory::class,
            
            //Mailer
//             Mailer\MyMailer::class => Mailer\Factory\MyMailerFactory::class,
            
            //Service/Server
            Service\Server\WeixinTokener::class   => Service\Server\Factory\WeixinTokenerFactory::class,
            Service\Server\AclPermissioner::class => Service\Server\Factory\AclPermissionerFactory::class,
            
            
            //Service
            Service\InitServer::class       => Service\Factory\InitServerFactory::class,
            Service\UserManager::class      => Service\Factory\UserManagerFactory::class,
            Service\WebsiteManager::class   => Service\Factory\WebsiteManagerFactory::class,
            Service\WorkyardManager::class   => Service\Factory\WorkyardManagerFactory::class,
            Service\PointManager::class   => Service\Factory\PointManagerFactory::class,
            Service\ShiftManager::class   => Service\Factory\ShiftManagerFactory::class,
            Service\ShiftTypeManager::class   => Service\Factory\ShiftTypeManagerFactory::class,
            Service\ShiftGuardManager::class   => Service\Factory\ShiftGuardManagerFactory::class,
        ],
        
        'shared' => [
            // Specify here which services must be non-shared
            Model\MyOrm::class          => false,
            Filter\FormFilter::class     =>false,
        ]
    ],
    
    'view_helpers' => [
        'factories' => [
            View\Helper\UserHelper::class => View\Helper\Factory\UserHelperFactory::class,
            View\Helper\WebsiteHelper::class => View\Helper\Factory\WebsiteHelperFactory::class,
            View\Helper\WorkyardHelper::class => View\Helper\Factory\WorkyardHelperFactory::class,
            View\Helper\PointHelper::class => View\Helper\Factory\PointHelperFactory::class,
            View\Helper\ShiftHelper::class => View\Helper\Factory\ShiftHelperFactory::class,
            View\Helper\ShiftTypeHelper::class => View\Helper\Factory\ShiftTypeHelperFactory::class,
        ],
        
        'aliases' => [
            'User'      => View\Helper\UserHelper::class,
            'Website'   => View\Helper\WebsiteHelper::class,
            'Workyard'  => View\Helper\WorkyardHelper::class,
            'Point'     => View\Helper\PointHelper::class,
            'Shift'     => View\Helper\ShiftHelper::class,
            'ShiftType' => View\Helper\ShiftTypeHelper::class,
        ],
    ],
    
    'weixin_token' =>[
        'url'       =>'https://api.weixin.qq.com/cgi-bin/token',
        'appid'     => 'wx8c36e3d3fba83dad',
        'secret'    => '24ecd9972b5490f043b0da21a571f605',
        'grant_type'=> 'client_credential',
        'expires_in'=> 7200
    ],
];
