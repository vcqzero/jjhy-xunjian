<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Service\ShiftManager;
use Api\Service\ShiftGuardManager;
use Api\Service\ShiftTypeManager;

class ShiftController extends AbstractActionController
{
    private $ShiftManager;
    private $ShiftGuardManager;
    private $ShiftTypeManager;
    
    public function __construct(
        ShiftManager $ShiftManager,
        ShiftGuardManager $ShiftGuardManager,
        ShiftTypeManager $ShiftTypeManager
        )
    {
        $this->ShiftManager = $ShiftManager;
        $this->ShiftGuardManager = $ShiftGuardManager;
        $this->ShiftTypeManager= $ShiftTypeManager;
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
    
    public function addAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        
        //获取用户提交表单
        $values        = $this->params()->fromPost();
        $date_range    = $this->params()->fromPost('date-range');
        $shift_type_id = $this->params()->fromPost('shift_type_id');
        $values        = $this->params()->fromPost();
        
        $dates = $this->ShiftManager->getDates($date_range);
        $ShiftTypeEntity = $this->ShiftTypeManager->MyOrm->findOne($shift_type_id);
        $connection = $this->ShiftManager->MyOrm->getConnection();
        try{
            $connection->beginTransaction();
            foreach ($dates as $date){
                $values   = $this->ShiftManager->processShiftType($values, $date, $ShiftTypeEntity);
                $shift_id = $this->ShiftManager->add($values);
                if(empty($shift_id))
                {
                    throw new \Exception('insert shift error');
                }
                //然后保存到shift_guard 数据表中
                $guard_ids = $this->params()->fromPost('guard_ids');
                foreach ($guard_ids as $key=>$guard_id)
                {
                    $res = $this->ShiftGuardManager->add($guard_id, $shift_id);
                    if(empty($res))
                    {
                        throw new \Exception('insert shift_guard error');
                    }
                }
            }
            $connection->commit();
            $res = true;
        }catch (\Exception $e ){
            $res = false;
            $connection->rollback();
        }
        $this->ajax()->success($res);
    }
    
    public function deleteAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        
        $shift_id = $this->params()->fromRoute('shiftID');
        $res = $this->ShiftManager->MyOrm->delete($shift_id);
        $this->ajax()->success($res);
    }
    
}
