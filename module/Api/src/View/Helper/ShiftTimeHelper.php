<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\ShiftTimeManager;
use Api\Entity\ShiftTimeEntity;

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
    
    public function getShiftTimeIdOnWorking($userID, $shift_id)
    {
        $where = [
            ShiftTimeEntity::FILED_SHIFT_ID => $shift_id,
            ShiftTimeEntity::FILED_GUARD_ID => $userID,
            ShiftTimeEntity::FILED_STATUS   => ShiftTimeManager::STATUS_WORKING,
        ];
        
        $shfit_time_id = $this->ShiftTimeManager->MyOrm->findOne($where);
        return $shfit_time_id;
    }
}
