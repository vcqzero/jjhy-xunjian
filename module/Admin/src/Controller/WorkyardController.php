<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
* @desc 账户管理
*/
class WorkyardController extends AbstractActionController
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
    public function addPageAction()
    {
        return new ViewModel([
        ]);
    }
    
    //goto edit page
    public function editPageAction()
    {
        return new ViewModel($this->params()->fromRoute());
    }
    
    public function deleteModalAction()
    {
        $view = new ViewModel($this->params()->fromRoute());
        $view->setTerminal(true);
        return $view;
    }
    
    public function detailModalAction()
    {
        $view = new ViewModel($this->params()->fromRoute());
        $view ->setTerminal(true);
        return $view;
    }
}
