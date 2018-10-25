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
        $View =  new ViewModel();
        $type = $this->params()->fromQuery('type');
        $template = $type == 'done' ? 'guard/shift/index-done' : 'guard/shift/index-plan';
        $View->setTemplate($template);
        return $View;
    }
    
    public function logShiftPopupAction()
    {
        $view = new ViewModel($this->params()->fromPost());
        $view->setTerminal(true);
        return $view;
    }
    
    public function paginatorAction()
    {
        $post = $this->params()->fromPost();
        $type = $this->params()->fromQuery('type');
        $template = $type == 'done' ? 'guard/shift/paginator-done' : 'guard/shift/paginator-plan';
        $view = new ViewModel($post);
        $view->setTerminal(true);
        $view->setTemplate($template);
        return $view;
    }
}
