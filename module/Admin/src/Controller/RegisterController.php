<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
* @desc 账户管理
*/
class RegisterController extends AbstractActionController
{
    //go to index page
    public function indexAction()
    {
        return new ViewModel([
            'where' => $this->params()->fromQuery(),
            'page'  => $this->params()->fromQuery('page', 1),
        ]);
    }
    public function successAction()
    {
        return new ViewModel([
            'where' => $this->params()->fromQuery(),
            'page'  => $this->params()->fromQuery('page', 1),
        ]);
    }
    public function refuseAction()
    {
        return new ViewModel([
            'where' => $this->params()->fromQuery(),
            'page'  => $this->params()->fromQuery('page', 1),
        ]);
    }
    public function successModalAction()
    {
        $View = new ViewModel($this->params()->fromRoute());
        $View->setTerminal(true);
        return $View;
    }
    public function refuseModalAction()
    {
        $View = new ViewModel($this->params()->fromRoute());
        $View->setTerminal(true);
        return $View;
    }
   
}
