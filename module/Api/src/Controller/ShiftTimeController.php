<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Service\ShiftTimeManager;
use Api\Service\ShiftTimePointManager;

class ShiftTimeController extends AbstractActionController
{
    private $ShiftTimeManager;
    private $ShiftTimePointManager;
    
    public function __construct(
        ShiftTimeManager $ShiftTimeManager,
        ShiftTimePointManager $ShiftTimePointManager
        )
    {
        $this->ShiftTimeManager = $ShiftTimeManager;
        $this->ShiftTimePointManager= $ShiftTimePointManager;
    }
    /**
     * We override the parent class' onDispatch() method to
     * set an alternative layout for all actions in this controller.
     */
    public function onDispatch(MvcEvent $e)
    {
        // Call the base class' onDispatch() first and grab the response
        $response = parent::onDispatch($e);
        
        // Set alternative layout
        $this->layout()->setTemplate('layout/blank.phtml');
        
        // Return the response
        return $response;
    }
    
    
}
