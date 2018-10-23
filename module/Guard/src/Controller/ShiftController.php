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
        return new ViewModel();
    }
    
    public function logShiftPopupAction()
    {
        $view = new ViewModel($this->params()->fromPost());
        $view->setTerminal(true);
        return $view;
    }
}
