<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
* @desc 账户管理
*/
class ShiftGuardController extends AbstractActionController
{
    
    //go to index page
    public function indexAction()
    {
        return new ViewModel([
            'where' => $this->params()->fromQuery(),
            'page'  => $this->params()->fromQuery('page', 1),
        ]);
    }
    
    //go to index page
    public function detailModalAction()
    {
        $view = new ViewModel($this->params()->fromRoute());
        $view->setTerminal(true);
        return $view;
    }
    
    public function csvAction()
    {
        
    }
    
}
