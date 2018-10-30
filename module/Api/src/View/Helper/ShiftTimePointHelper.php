<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\ShiftTimePointManager;
use Api\Entity\ShiftTimePointEntity;
use Zend\Db\Sql\Select;

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
    
    /**
    * 判断某一巡检次数中，某一巡检点是否已巡检
    * 
    * @param int $shift_time_id
    * @param int $point_id
    * @return bool      
    */
    public function hasDone($shift_time_id, $point_id)
    {
        $where = [
            ShiftTimePointEntity::FILED_SHIFT_TIME_ID => $shift_time_id,
            ShiftTimePointEntity::FILED_POINT_ID => $point_id
        ];
        
        $count = $this->ShiftTimePointManager->MyOrm->count($where);
        
        return !empty($count);
    }
    
    /**
    * 获取某一次巡检任务中，已经完成的巡检点数量
    * 
    * @param  int $shift_time_id
    * @return int        
    */
    public function getCountOnDone($shift_time_id)
    {
        return $this->ShiftTimePointManager->getCountOnDone($shift_time_id);
    }
    
    /**
    * 获取某一巡检次数的所有巡检记录
    * 
    * @param  int $shift_time_id
    * @return        
    */
    public function getEntities($shift_time_id)
    {
        $where = [
            ShiftTimePointEntity::FILED_SHIFT_TIME_ID => $shift_time_id,
        ];
        
        $ShiftTimePointEntities= $this->ShiftTimePointManager->MyOrm->findAll($where);
        
        return $ShiftTimePointEntities;
    }
    
    /**
    * 获取某次巡检中巡检轨迹的坐标
    * 
    * @param  
    * @return        
    */
    public function getAddressPathJsonBy($shift_time_id)
    {
        //get the shift point time
        $where = [
            ShiftTimePointEntity::FILED_SHIFT_TIME_ID => $shift_time_id,  
        ];
        $order = [ShiftTimePointEntity::FILED_TIME => Select::ORDER_ASCENDING];
        $Entities = $this->ShiftTimePointManager->MyOrm->findAll($where, $order);
        $addresses_path = [];
        foreach ($Entities as $Entity)
        {
            $address_path = $Entity->getAddress_path();
            $address_path = json_decode($address_path, true);
            $addresses_path[] = $address_path;
        }
        return json_encode($addresses_path);
    }
}
