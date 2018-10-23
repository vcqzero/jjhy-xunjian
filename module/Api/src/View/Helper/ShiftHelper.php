<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\ShiftManager;
use Api\Service\UserManager;
use Api\Service\ShiftGuardManager;
use Api\Entity\ShiftGuardEntity;
use Zend\Db\Sql\Predicate\Predicate;
use Api\Entity\ShiftEntity;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class ShiftHelper extends AbstractHelper 
{
    private $ShiftManager;
    private $ShiftGuardManager;
    private $UserManager;
    
    public function __construct(
        ShiftManager $ShiftManager,
        ShiftGuardManager $ShiftGuardManager,
        UserManager  $UserManager
        )
    {
        $this->ShiftManager = $ShiftManager;
        $this->ShiftGuardManager= $ShiftGuardManager;
        $this->UserManager= $UserManager;
    }
    
    public function getEntity($id)
    {
        return $this->ShiftManager->MyOrm->findOne($id);
    }
    
    public function getEntities($where =[])
    {
        return $this->ShiftManager->MyOrm->findAll($where);
    }
    
    public function getLastItems($workyard_id, $count = null)
    {
        $where[] = new \Zend\Db\Sql\Predicate\NotBetween(ShiftEntity::FILED_DATE, 0, time());
        $where[ShiftEntity::FILED_WORKYARD_ID] = $workyard_id;
        $order = [ShiftEntity::FILED_DATE=> Select::ORDER_ASCENDING];
        return $this->ShiftManager->MyOrm->findAll($where, $order, $count);
    }
    public function getGuardsName($shiftID) 
    {
        $guard_ids = $this->getGuardIds($shiftID);
        $guardName = '';
        foreach ($guard_ids as $guard_id)
        {
            $guard    = $this->UserManager->MyOrm->findOne($guard_id);
            $guardName.= ' ' . $guard->getUserName();
        }
        
        return $guardName;
    }
    
    /**
    * 查找一个排班班次中所有值班人
    * 
    * @param int $shiftID  
    * @return array $guard_ids         
    */
    public function getGuardIds($shiftID)
    {
        $where = [
            ShiftGuardEntity::FILED_SHIFT_ID => $shiftID
        ];
        $shift_guards = $this->ShiftGuardManager->MyOrm->findAll($where);
        
        $guard_ids =[];
        
        foreach ($shift_guards as $shift_guard)
        {
            $guard_id = $shift_guard->getGuard_id();
            $guard_ids[] = $guard_id;
        }
        return $guard_ids;
    }
    
    public function getPaginator($page, $where = [], $workyard_id)
    {
        
        $done = isset($where['done']) ? $where['done'] : '';
        $date = isset($where['date']) ? $where['date'] : '';
        unset($where['done']);
        if ($done == 'done') {
            $where[] = new \Zend\Db\Sql\Predicate\Between(ShiftEntity::FILED_DATE, 0, time());
            $order = [ShiftEntity::FILED_DATE=> Select::ORDER_DESCENDING];
        }else {
            $where[] = new \Zend\Db\Sql\Predicate\NotBetween(ShiftEntity::FILED_DATE, 0, time());
            $order = [ShiftEntity::FILED_DATE=> Select::ORDER_ASCENDING];
        }
        
        if ($date)
        {
            $where['date'] = strtotime($date);
        }
        
        $where[ShiftEntity::FILED_WORKYARD_ID] =$workyard_id;
        $paginator = $this->ShiftManager->MyOrm->paginator($page, $where, $order);
        $paginator::setDefaultItemCountPerPage(12);
        return $paginator;
    }
    
    /**
    * 获取某一巡检员，今天的巡检任务
    * 
    * @param int $userID  
    * @param int $workyardID  
    * @return        
    */
    public function getGuardShift($userID, $workyardID)
    {
        //获取今天凌晨的时间戳
        $today = strtotime(date("Y-m-d"),time());
        $end = $today + 60 * 60 * 24;
        
        $Select = new Select();
        $Select->from(['s'=>ShiftEntity::TABLE_NAME]);
        $on = 's.id=sg.shift_id';
        $Select->join(['sg'=>ShiftGuardEntity::TABLE_NAME], $on, [], Select::JOIN_LEFT);
        
        //查询条件
        $where = [
            ShiftEntity::FILED_WORKYARD_ID => $workyardID,
            ShiftGuardEntity::FILED_GUARD_ID => $userID
        ];
        
        $Select ->where
        ->equalTo(ShiftEntity::FILED_WORKYARD_ID, $workyardID)
        ->equalTo(ShiftGuardEntity::FILED_GUARD_ID, $userID)
        ->lessThan(ShiftEntity::FILED_START_TIME, $today)
        ->greaterThan(ShiftEntity::FILED_END_TIME, $today)
        ->or
        ->equalTo(ShiftEntity::FILED_WORKYARD_ID, $workyardID)
        ->equalTo(ShiftGuardEntity::FILED_GUARD_ID, $userID)
        ->lessThan(ShiftEntity::FILED_END_TIME, $end)
        ->greaterThan(ShiftEntity::FILED_START_TIME, $today)
        ->or
        ->equalTo(ShiftEntity::FILED_WORKYARD_ID, $workyardID)
        ->equalTo(ShiftGuardEntity::FILED_GUARD_ID, $userID)
        ->lessThan(ShiftEntity::FILED_START_TIME, $end)
        ->greaterThan(ShiftEntity::FILED_END_TIME, $end);
        
        $ShiftEntities = $this->ShiftManager->MyOrm->select($Select, new ShiftEntity());
        return $ShiftEntities;
    }
}
