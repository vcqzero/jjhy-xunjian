<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\ShiftTimeManager;
use Api\Entity\ShiftTimeEntity;
use Api\Model\MyOrm;

class ShiftTimeHelper extends AbstractHelper 
{
    private $ShiftTimeManager;
    
    public function __construct(
        ShiftTimeManager $ShiftTimeManager
        )
    {
        $this->ShiftTimeManager = $ShiftTimeManager;
    }
    
    /**
    * 获取某一巡检员关于某已巡检班次已完成的巡检任务
    * 
    * @param  int $shift_id 
    * @return int max_count     
    */
    public function getDoneCount($userID, $shift_id)
    {
        $where = [
            ShiftTimeEntity::FILED_SHIFT_ID => $shift_id,
            ShiftTimeEntity::FILED_GUARD_ID => $userID,
            ShiftTimeEntity::FILED_STATUS   => ShiftTimeManager::STATUS_DONE,
        ];
        
        $count = $this->ShiftTimeManager->MyOrm->count($where);
        return $count;
    }
    
    public function hasDone($shift_time_id)
    {
        $ShiftTime = $this->ShiftTimeManager->MyOrm->findOne($shift_time_id);
        $status = $ShiftTime->getStatus();
        return $status == ShiftTimeManager::STATUS_DONE;
    }
    
    /**
    * 获取关于某一巡检员在某次巡检任务中，正在进行的巡检次数id
    * 当该次巡检一个巡检点未扫描时，id = null
    * 也就是说，当在第一次扫描巡检点时会生成巡检次数id
    * 
    * @param  int $userID
    * @param  int $shift_id
    * @param  bool $has_done 是否已完成
    * @return int]null $shift_time_id         
    */
    public function getShiftTimeIdOnWorking($userID, $shift_id, $has_done)
    {
        $MyOrm = $this->ShiftTimeManager->MyOrm;
        //如果本次巡检已完成，则代表没有正在巡检的次数id
        if ($has_done)
        {
            return null;
        }
        
        //如果未完成，则在数据库中查找未完成的id
        $where = [
            ShiftTimeEntity::FILED_SHIFT_ID => $shift_id,
            ShiftTimeEntity::FILED_GUARD_ID => $userID,
            ShiftTimeEntity::FILED_STATUS   => ShiftTimeManager::STATUS_WORKING,
        ];
        
        $Entity = $MyOrm->findOne($where);
        $shfit_time_id =$Entity->getId();
        $count = $MyOrm->getCount();
        //如果未找到，则需要重新插入一条
        if(empty($count))
        {
            $values = $where;
            $MyOrm->insert($values);
            $shfit_time_id = $MyOrm->getLastInsertId();
        }
        return $shfit_time_id;
    }
    
    /**
    * 获取某一值班记录 某一巡检员的所有巡检次数记录id的数组
    * 
    * @param  
    * @return array       
    */
    public function getShfitTimeIDs($shift_id, $user_id)
    {
        //如果未完成，则在数据库中查找未完成的id
        $where = [
            ShiftTimeEntity::FILED_SHIFT_ID => $shift_id,
            ShiftTimeEntity::FILED_GUARD_ID => $user_id,
        ];
        
        $Entities = $this->ShiftTimeManager->MyOrm->findAll($where);
        
        $arr_ids = [];
        if(count($Entities)) {
            foreach ($Entities as $Entity) {
                $shit_time_id = $Entity->getId();
                $arr_ids[] = $shit_time_id;
            }
        }
        
        return $arr_ids;
    }
}
