<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
* @desc 账户管理
*/
class ShiftTypeController extends AbstractActionController
{
    //go to index page
    public function indexAction()
    {
        return new ViewModel([
            'where' => $this->params()->fromQuery(),
            'page'  => $this->params()->fromQuery('page', 1),
        ]);
    }
    
    //goto add page
    public function addModalAction()
    {
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }
    
    //goto edit page
    public function editModalAction()
    {
        $view = new ViewModel($this->params()->fromRoute());
        $view->setTerminal(true);
        return $view;
    }
    
    //goto delete page
    public function deleteModalAction()
    {
        $view = new ViewModel($this->params()->fromRoute());
        $view->setTerminal(true);
        return $view;
    }
    
}
