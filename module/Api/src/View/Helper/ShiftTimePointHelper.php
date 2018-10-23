<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\ShiftTimePointManager;
use Api\Entity\ShiftTimePointEntity;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class ShiftTimePointHelper extends AbstractHelper 
{
    private $ShiftTimePointManager;
    
    public function __construct(
        ShiftTimePointManager $ShiftTimePointManager
        )
    {
        $this->ShiftTimePointManager = $ShiftTimePointManager;
    }
    
    public function getEntity($shift_time_id, $point_id)
    {
        $where = [
            ShiftTimePointEntity::FILED_SHIFT_TIME_ID => $shift_time_id,
            ShiftTimePointEntity::FILED_POINT_ID => $point_id
        ];
        
        $ShiftTimePointEntity = $this->ShiftTimePointManager->MyOrm->findOne($where);
        
        return $ShiftTimePointEntity;
    }
}
