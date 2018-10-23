<?php
namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\UserManager;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class NavbarHelper extends AbstractHelper 
{
    public function getNavbar($role)
    {
        $navbars = $this->getAllNavbars();
        $navbars = $this->filter($navbars, $role);
        return $navbars;
    }
    
    private function filter($navbars, $role)
    {
        //首先判断用户角色是否允许
        foreach ($navbars as $key1=>$sections)
        {
            foreach ($sections as $key2=>$section)
            {
                $menus = $section['menus'];
                foreach ($menus as $key3=>$menu)
                {
                    $allow = $menu['allow'];
                    if(empty($allow)) {
                        continue;
                    }
                    if (in_array($role, $allow)) {
                        continue;
                    }
                    
                    unset($navbars[$key1][$key2]['menus'][$key3]);
                }
            }
        }
        
        //然后将不含子菜单的删除
        foreach ($navbars as $key1=>$sections)
        {
            foreach ($sections as $key2=>$section)
            {
                $menus = $section['menus'];
                if (empty($menus)){
                    unset($navbars[$key1][$key2]);
                }
                
            }
        }
        
        //然后将不含子菜单的删除
        foreach ($navbars as $key1=>$sections)
        {
            if (empty($sections)){
                unset($navbars[$key1]);
            }
        }
        
        return $navbars;
    }
    
    private function getAllNavbars()
    {
        return [
            '操作台'=>[
                '操作台'=> [
                    'icon'  => 'fa fa-home',
                    'menus' => [
                        1=>[
                            'title'=> '操作台',
                            'url' => '/',
                            'allow' => [UserManager::ROLE_SUPER_ADMIN, UserManager::ROLE_WORKYARD_ADMIN],
                        ],
                    ]
                ],
            ],
            
            '管理员/工地管理'=>[
                
                '管理员'=> [
                    'icon'  => 'fa fa-users',
                    'menus' => [
                        1=>[
                            'title'=> '管理员',
                            'url' => '/user',
                            'allow' => [UserManager::ROLE_SUPER_ADMIN],
                        ],
                    ]
                ],
                
                '工地管理'=> [
                    'icon'  => 'fa fa-road',
                    'menus' => [
                        1=>[
                            'title'=> '工地管理',
                            'url' => '/workyard',
                            'allow' => [UserManager::ROLE_SUPER_ADMIN],
                        ],
                        2=>[
                            'title'=> '新增/编辑工地',
                            'url' => '/workyard/addPage',
                            'allow' => [UserManager::ROLE_SUPER_ADMIN],
                        ],
                    ]
                ],
                
            ],
            
            '系统设置'=>[
                '系统设置'=> [
                    'icon'  => 'fa fa-cogs',
                    'menus' => [
                        1=>[
                            'title'=> '基本信息',
                            'url' => '/website/basic',
                            'allow' => [UserManager::ROLE_SUPER_ADMIN],
                        ],
                        2=>[
                            'title'=> '邮箱设置',
                            'url' => '/website/email',
                            'allow' => [UserManager::ROLE_SUPER_ADMIN],
                        ],
                    ]
                ],
            ],
            
            '巡检部分'=>[
                
                '巡检点'=> [
                    'icon'  => 'fa fa-photo',
                    'menus' => [
                        1=>[
                            'title'=> '巡检点',
                            'url' => '/point',
                            'allow' => [UserManager::ROLE_WORKYARD_ADMIN],
                        ],
                        
//                         2=>[
//                             'title'=> '新增/编辑',
//                             'url' => '/point/addPage',
//                             'allow' => [UserManager::ROLE_WORKYARD_ADMIN],
//                         ],
                    ]
                    
                ],
                
                '巡检员'=> [
                    'icon'  => 'fa fa-users',
                    'menus' => [
                        1=>[
                            'title'=> '巡检员管理',
                            'url' => '/guard',
                            'allow' => [UserManager::ROLE_WORKYARD_ADMIN],
                        ],
                    ]
                ],
                
                '值班表/考勤'=> [
                    'icon'  => 'fa fa-file-word-o',
                    'menus' => [
                        1=>[
                            'title'=> '值班安排',
                            'url' => '/shift',
                            'allow' => [UserManager::ROLE_WORKYARD_ADMIN],
                        ],
                        2=>[
                            'title'=> '考勤',
                            'url' => '/articleCategories',
                            'allow' => [UserManager::ROLE_WORKYARD_ADMIN],
                        ],
                    ]
                ],
                
                '工地设置'=> [
                    'icon'  => 'fa fa-cogs',
                    'menus' => [
                        1=>[
                            'title'=> '值班班次设置',
                            'url' => '/shiftType',
                            'allow' => [UserManager::ROLE_WORKYARD_ADMIN],
                        ],
                    ]
                ],
            ],
            
            '个人中心'=>[
                '个人中心'=> [
                    'icon'  => 'fa fa-user',
                    'menus' => [
                        1=>[
                            'title'=> '修改密码',
                            'url' => '/account/changePassword',
                            'allow' => [UserManager::ROLE_SUPER_ADMIN, UserManager::ROLE_WORKYARD_ADMIN],
                        ],
                    ]
                ],
            ],
        ];
    }
}
