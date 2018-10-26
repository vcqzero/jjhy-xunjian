<?php
namespace Guard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    
    //goto login page
    public function indexAction()
    {
        $View = new ViewModel();
        $View ->setTerminal(true);
//         $this->layout()->setTemplate('layout/blank.phtml');
//         $tokenJson = $this->token()->token();
//         $View->setVariables([
//             'tokenJson' => $tokenJson,
//         ]);
        return $View;
    }
    
    public function changePasswordAction()
    {
        $View = new ViewModel();
        $View ->setTerminal(true);
//         $this->layout()->setTerminal(true);
//         $tokenJson = $this->token()->token();
//         $View->setVariables([
//             'tokenJson' => $tokenJson,
//         ]);
        return $View;
    }
    
    //goto not permission page
    public function noPermissionAction()
    {
        $view = new ViewModel([
            'title'=> '无权访问！',
            'desc'=> '您无权访问该资源!'
        ]);
        $view ->setTerminal(true);
        $view ->setTemplate('error/mobile/error');
        return $view;
    }
}
