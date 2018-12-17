<?php
namespace Guard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RegisterController extends AbstractActionController
{
    public function indexAction()
    {
        $View = new ViewModel($this->params()->fromQuery());
        $View->setTerminal(true);
        return $View;
    }
    
    public function registerAction()
    {
        $View = new ViewModel($this->params()->fromQuery());
        $View->setTerminal(true);
        return $View;
    }
}
