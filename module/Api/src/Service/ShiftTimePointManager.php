<?php
namespace Api\Service;

use Api\Model\MyOrm;
use Api\Entity\ShiftTimePointEntity;
use Api\Entity\PointEntity;

/**
* 所有的配置信息，都从此读取
*/
class ShiftTimePointManager
{
    public $MyOrm;
    public $PointManager;
    public $ShiftManager;
    
    /**
     * @param field_type $ShiftManager
     */
    public function setShiftManager(ShiftManager $ShiftManager)
    {
        $this->ShiftManager = $ShiftManager;
    }

    /**
     * @param PointManager
     */
    public function setPointManager(PointManager $PointManager)
    {
        $this->PointManager = $PointManager;
    }

    public function __construct(
        MyOrm $MyOrm)
    {
        $MyOrm->setEntity(new ShiftTimePointEntity());
        $MyOrm->setTableName(ShiftTimePointEntity::TABLE_NAME);
        $this->MyOrm = $MyOrm;
    }
    
    /**
     * 获取某一次巡逻任务中，已经完成的巡逻点数量
     *
     * @param  int $shift_time_id
     * @return int
     */
    public function getCountOnDone($shift_time_id)
    {
        $where = [
            ShiftTimePointEntity::FILED_SHIFT_TIME_ID => $shift_time_id,
        ];
        
        $count = $this->MyOrm->count($where);
        
        return $count;
    }
    
    /**
    * 判断某一巡逻点是否合法
    * 
    * @param  
    * @return bool       
    */
    public function isValidPoint($workyard_id, $shift_time_id, $point_id, $shift_id)
    {
        //巡逻点必须属于该工地
        $where = [
            PointEntity::FILED_WORKYARD_ID => $workyard_id,
            PointEntity::FILED_ID => $point_id,
        ];
        $count = $this->PointManager->MyOrm->count($where);
        if (empty($count))
        {
            $res = [
                'success' => false,
                'err' => '巡逻点无效'
            ];
            return json_encode($res);
        }else {
            $Point = $this->PointManager->MyOrm->findOne($where);
            $created_point = $Point->getCreated();
            //shift
            $Shift = $this->ShiftManager->MyOrm->findOne($shift_id);
            $created_shift = $Shift->getCreated();
            $start_time    = $Shift->getStart_time();
            if ($created_point > $created_shift && $created_point > $start_time)
            {
                $res = [
                    'success' => false,
                    'err' => '本次巡逻不含该巡逻点'
                ];
                return json_encode($res);
            }
        }
        
        //巡逻点不能已巡逻
        $where = [
            ShiftTimePointEntity::FILED_SHIFT_TIME_ID => $shift_time_id,
            ShiftTimePointEntity::FILED_POINT_ID => $point_id,
        ];
        $count = $this->MyOrm->count($where);
        if (!empty($count))
        {
            $res = [
                'success' => false,
                'err' => '该巡逻点已巡逻'
            ];
            return json_encode($res);
        }
        return true;
    }
    
    /**
    * 判断该次巡逻是否已经完成所有巡逻点的巡逻任务
    * 
    * @param  
    * @return bool       
    */
    public function hasDoneAllPointsOnThisShiftTime($workyard_id, $shift_id, $shift_time_id)
    {
        //获取该巡逻开始时间
        $Shift = $this->ShiftManager->MyOrm->findOne($shift_id);
        $start_time = $Shift->getStart_time();
        $created    = $Shift->getCreated();
        $start_time = $start_time >= $created ? $start_time : $created;
        //获取该工地所有巡逻点数量
        $where = [
            PointEntity::FILED_WORKYARD_ID => $workyard_id,
        ];
        $where[] = new \Zend\Db\Sql\Predicate\Between(PointEntity::FILED_CREATED, 0, $start_time);
        $all_count = $this->PointManager->MyOrm->count($where);
        //获取该巡逻已完成的巡逻点数量
        $done_count = $this->getCountOnDone($shift_time_id);
        
        return  $all_count <= $done_count;
    }
}

