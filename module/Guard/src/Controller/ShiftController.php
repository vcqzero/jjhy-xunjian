<?php
namespace Guard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;

class ShiftController extends AbstractActionController
{
    public function onDispatch(MvcEvent $e)
    {
        // Call the base class' onDispatch() first and grab the response
        $response = parent::onDispatch($e);
        
        // Set alternative layout
        $this->layout()->setTemplate('layout/weixin/layout.phtml');
        
        // Return the response
        return $response;
    }
    
    public function indexAction()
    {
        $View =  new ViewModel($this->params()->fromQuery());
        return $View;
    }
    
    public function shiftDetailAction()
    {
        $view = new ViewModel($this->params()->fromRoute());
        return $view;
    }
    
    public function paginatorAction()
    {
        $post  = $this->params()->fromPost();
        $query = $this->params()->fromQuery();
        $view = new ViewModel(array_merge($post, $query));
        $view->setTerminal(true);
        return $view;
    }
}
