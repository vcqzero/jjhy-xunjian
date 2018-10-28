<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Service\ShiftTypeManager;
use Api\Entity\ShiftTypeEntity;

class ShiftTypeController extends AbstractActionController
{
    private $ShiftTypeManager;
    public function __construct(ShiftTypeManager $ShiftTypeManager)
    {
        $this->ShiftTypeManager = $ShiftTypeManager;
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
    
    
    public function validNameAction()
    {
        $name    = $this->params()->fromPost('name');
        $old_name= $this->params()->fromPost('old_name');
        $workyard_id= $this->params()->fromPost('workyard_id');
        
        $name = trim($name);
        
        if($old_name == $name) {
            $this->ajax()->valid(true);
        }
        $where = [
            ShiftTypeEntity::FILED_NAME => $name,
            ShiftTypeEntity::FILED_WORKYARD_ID => $workyard_id,
        ];
        $count = $this->ShiftTypeManager->MyOrm->count($where);
        
        $this->ajax()->valid(empty($count));
    }
    
    //edit the website infomation
    public function editAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        $typeID= $this->params()->fromRoute('typeID');
        //获取用户提交表单
        $values = $this->params()->fromPost();
        $values = $this->ShiftTypeManager->trimTime($values);
        //do filter
        $values = $this->ShiftTypeManager->FormFilter->getFilterValues($values);
        //执行增加操作
        $res = $this->ShiftTypeManager->MyOrm->update($typeID, $values);
        $this->ajax()->success($res);
    }
    
    //delete 
    public function deleteAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        
        $typeID= $this->params()->fromRoute('typeID');
        $res = $this->ShiftTypeManager->MyOrm->delete($typeID);
        $this->ajax()->success($res);
    }
    
    public function addAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        //获取用户提交表单
        $values = $this->params()->fromPost();
        $values = $this->ShiftTypeManager->trimTime($values);
        
        //do filter
        $values = $this->ShiftTypeManager->FormFilter->getFilterValues($values);
        //执行增加操作
        $res = $this->ShiftTypeManager->MyOrm->insert($values);
        $this->ajax()->success($res);
    }
    
}
