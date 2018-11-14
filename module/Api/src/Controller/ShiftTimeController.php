<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Service\ShiftTimeManager;
use Api\Service\ShiftTimePointManager;
use Api\Entity\ShiftTimePointEntity;
use Api\Service\PointManager;
use Api\Entity\ShiftTimeEntity;
use Api\Service\ShiftManager;

class ShiftTimeController extends AbstractActionController
{
    private $ShiftTimeManager;
    private $ShiftTimePointManager;
    private $PointManager;
    private $ShiftManager;
    
    public function __construct(
        ShiftTimeManager $ShiftTimeManager,
        ShiftTimePointManager $ShiftTimePointManager,
        PointManager $PointManager,
        ShiftManager $ShiftManager
        )
    {
        $this->ShiftTimeManager = $ShiftTimeManager;
        $this->ShiftTimePointManager= $ShiftTimePointManager;
        $this->PointManager = $PointManager;
        
        $this->ShiftTimePointManager->setPointManager($PointManager);
        $this->ShiftTimePointManager->setShiftManager($ShiftManager);
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
        
        $MyOrm = $this->ShiftTimePointManager->MyOrm;
        $shift_time_id  = $this->params()->fromPost('shift_time_id');
        $point_id       = $this->params()->fromPost('point_id');
        $shift_id       = $this->params()->fromPost('shift_id');
        $workyard_id    = $this->params()->fromQuery('workyard_id');
        
        //判断巡检点是否合法
        $isValid = $this->ShiftTimePointManager->isValidPoint($workyard_id, $shift_time_id, $point_id, $shift_id);
        if($isValid !== true) {
            echo $isValid;
            exit();
        }
        //下面进行数据插入和更新操作
        $connection = $MyOrm->getConnection();
        $connection ->beginTransaction();
        try{
            $values= $this->params()->fromPost();
            $values[ShiftTimePointEntity::FILED_TIME] = time();
            unset($values['shift_id']);
            $res = $MyOrm->insert($values);
            $shift_time_point_id = $MyOrm->getLastInsertId();
            if(empty($res)) {
                throw new \Exception('数据插入错误');
            }
            
            //如果此时已完成全部巡检点的巡检任务
            //则将该次巡检标记为完成
            if ($this->ShiftTimePointManager->hasDoneAllPointsOnThisShiftTime($workyard_id, $shift_id, $shift_time_id)) {
                $set= [
                    ShiftTimeEntity::FILED_STATUS => ShiftTimeManager::STATUS_DONE
                ];
                $res = $this->ShiftTimeManager->MyOrm->update($shift_time_id, $set);
                if (empty($res)) {
                    throw new \Exception('数据更新错误');
                }
            }
            $connection->commit();
            $res = [
                'success' => true,
                'err' => '',
                'shift_time_point_id' => $shift_time_point_id
            ];
            echo json_encode($res);
            exit();
        }catch (\Exception $e ){
            $connection->rollback();
            $res = [
                'success' => false,
                'err' => '服务器异常',
            ];
            echo json_encode($res);
            exit();
        }
    }
    
    public function testAction()
    {
        $isValid = $this->ShiftTimePointManager->isValidPoint(33, 46, 63, 386);
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump($isValid);
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
    }
}
