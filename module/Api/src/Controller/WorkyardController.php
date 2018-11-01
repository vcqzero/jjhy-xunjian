<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Service\WorkyardManager;
use Api\Entity\WorkyardEntity;

class WorkyardController extends AbstractActionController
{
    private $WorkyardManager;
    public function __construct(WorkyardManager $WorkyardManager)
    {
        $this->WorkyardManager = $WorkyardManager;
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
        $name = $this->params()->fromPost('name');
        $old_name = $this->params()->fromPost('old_name');
        if($name == $old_name) {
            $this->ajax()->valid(true);
        }
        $where = [
            WorkyardEntity::FILED_NAME => $name
        ];
        $count = $this->WorkyardManager->MyOrm->count($where);
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
        $workyardID = $this->params()->fromRoute('workyardID');
        //获取用户提交表单
        $values = $this->params()->fromPost();
        //do filter
        $values = $this->WorkyardManager->FormFilter->getFilterValues($values);
        //执行增加操作
        $res = $this->WorkyardManager->MyOrm->update($workyardID, $values);
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
        //do filter
        $values = $this->WorkyardManager->FormFilter->getFilterValues($values);
        //执行增加操作
        $res = $this->WorkyardManager->MyOrm->insert($values);
        $this->ajax()->success($res);
    }
}
