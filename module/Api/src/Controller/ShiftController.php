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

    public function __construct(ShiftManager $ShiftManager, ShiftGuardManager $ShiftGuardManager, ShiftTypeManager $ShiftTypeManager)
    {
        $this->ShiftManager = $ShiftManager;
        $this->ShiftGuardManager = $ShiftGuardManager;
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

    public function addAction()
    {
        $token = $this->params()->fromQuery('token');
        if (! $this->Token()->isValid($token)) {
            $this->ajax()->success(false);
        }
        
        // 获取用户提交表单
        $values = $this->params()->fromPost();
        $date_range = $this->params()->fromPost('date-range');
        $shift_type_id = $this->params()->fromPost('shift_type_id');
        $values = $this->params()->fromPost();
        
        $dates = $this->ShiftManager->getDatesFromDateRange($date_range);
        $ShiftTypeEntity = $this->ShiftTypeManager->MyOrm->findOne($shift_type_id);
        $connection = $this->ShiftManager->MyOrm->getConnection();
        try {
            $connection->beginTransaction();
            foreach ($dates as $date) {
                $values = $this->ShiftManager->processShiftType($values, $date, $ShiftTypeEntity);
                $shift_id = $this->ShiftManager->add($values);
                if (empty($shift_id)) {
                    throw new \Exception('insert shift error');
                }
                
                // 然后保存到shift_guard 数据表中
                $guard_ids = $this->params()->fromPost('guard_ids');
                foreach ($guard_ids as $key => $guard_id) {
                    $res = $this->ShiftGuardManager->add($guard_id, $shift_id);
                    if (empty($res)) {
                        throw new \Exception('insert shift_guard error');
                    }
                }
            }
            $connection->commit();
            $res = true;
        } catch (\Exception $e) {
            $res = false;
            $connection->rollback();
        }
        $this->ajax()->success($res);
    }

    public function deleteAction()
    {
        $token = $this->params()->fromQuery('token');
        if (! $this->Token()->isValid($token)) {
            $this->ajax()->success(false);
        }
        
        $shift_id = $this->params()->fromRoute('shiftID');
        $connection = $this->ShiftManager->MyOrm->getConnection();
        $connection->beginTransaction();
        
        try {
            // 从shift表中删除
            $res = $this->ShiftManager->MyOrm->delete($shift_id);
            if (empty($shift_id)) {
                throw new \Exception('delete shift error');
            }
            
            // 从shift_guard表中删除
            $res = $this->ShiftGuardManager->deleteBy($shift_id);
            if (empty($shift_id)) {
                throw new \Exception('delete shift guard error');
            }
            
            $connection->commit();
            $res = true;
        } catch (\Exception $e) {
            $res = false;
            $connection->rollback();
        }
        $this->ajax()->success($res);
    }

    public function editAction()
    {
        //验证token 
        $token = $this->params()->fromQuery('token');
        if (! $this->Token()->isValid($token)) {
            $this->ajax()->success(false);
        }
        
        $shift_id = $this->params()->fromRoute('shiftID');
        // 获取用户提交表单
        $values = $this->params()->fromPost();
        $connection = $this->ShiftManager->MyOrm->getConnection();
        $connection->beginTransaction();
        try {
            // 更新shift仅可更新note
            // 其他信息不可更新
            $values = $this->ShiftManager->FormFilter->getFilterValues($values);
            // 执行更新
            $res = $this->ShiftManager->MyOrm->update($shift_id, $values);
            if (empty($res)) {
                throw new \Exception('update shift error');
            }
            
            // 先将shift_guard 中关于shift_id 的全部删掉
            $res = $this->ShiftGuardManager->deleteBy($shift_id);
            if (empty($res)) {
                throw new \Exception('delete shift_guard error');
            }
            // 然后保存到shift_guard 数据表中
            $guard_ids = $this->params()->fromPost('guard_ids');
            foreach ($guard_ids as $key => $guard_id) {
                $res = $this->ShiftGuardManager->add($guard_id, $shift_id);
                if (empty($res)) {
                    throw new \Exception('insert shift_guard error');
                }
            }
            $connection->commit();
            $res = true;
        } catch (\Exception $e) {
            $res = false;
            $connection->rollback();
        }
        $this->ajax()->success($res);
    }
}
