<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
return [
    'Zend\Mvc\Plugin\Prg',
    'Zend\Mvc\Plugin\Identity',
    'Zend\Mvc\Plugin\FlashMessenger',
    'Zend\Mvc\Plugin\FilePrg',
    'Zend\Cache',
    'Zend\Session',
    'Zend\Paginator',
    'Zend\Db',
    'Zend\Router',
    'Zend\Validator',
    'Zend\Log',
//     'ZendDeveloperTools',
    'Admin',//管理员端 PC
	'Api',//获取数据
    'Guard',//巡检员端 (微信)
];
