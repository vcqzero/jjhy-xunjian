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
     * 获取某一次巡检任务中，已经完成的巡检点数量
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
    * 判断某一巡检点是否合法
    * 
    * @param  
    * @return bool       
    */
    public function isValidPoint($workyard_id, $shift_time_id, $point_id)
    {
        //巡检点必须属于该工地
        $where = [
            PointEntity::FILED_WORKYARD_ID => $workyard_id,
            PointEntity::FILED_ID => $point_id,
        ];
        $count = $this->PointManager->MyOrm->count($where);
        if (empty($count))
        {
            $res = [
                'success' => false,
                'err' => '巡检点无效'
            ];
            return json_encode($res);
        }
        
        //巡检点不能已巡检
        $where = [
            ShiftTimePointEntity::FILED_SHIFT_TIME_ID => $shift_time_id,
            ShiftTimePointEntity::FILED_POINT_ID => $point_id,
        ];
        $count = $this->MyOrm->count($where);
        if (!empty($count))
        {
            $res = [
                'success' => false,
                'err' => '该巡检点已巡检'
            ];
            return json_encode($res);
        }
        return true;
    }
    
    /**
    * 判断该次巡检是否已经完成所有巡检点的巡检任务
    * 
    * @param  
    * @return bool       
    */
    public function hasDoneAllPointsOnThisShiftTime($workyard_id, $shift_time_id)
    {
        //获取该工地所有巡检点数量
        $where = [
            PointEntity::FILED_WORKYARD_ID => $workyard_id
        ];
        $all_count = $this->PointManager->MyOrm->count($where);
        //获取该巡检已完成的巡检点数量
        $done_count = $this->getCountOnDone($shift_time_id);
        
        return  $all_count == $done_count;
    }
}

