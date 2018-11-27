<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\ShiftManager;
use Api\Service\UserManager;
use Api\Service\ShiftGuardManager;
use Api\Entity\ShiftGuardEntity;
use Api\Entity\ShiftEntity;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class ShiftGuardHelper extends AbstractHelper 
{
    private $ShiftManager;
    private $ShiftGuardManager;
    private $UserManager;
    
    const TYPE_PLAN = 'plan';
    const TYPE_DONE = 'done';
    
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
    
    /**
    * 获取某一巡逻员，此时此刻巡逻任务
    * 
    * @param int $userID  
    * @param int $workyardID  
    * @return        
    */
    public function getShiftOnWorking($workyardID, $userID)
    {
        $Select = $this->getSelectOnworking($workyardID, $userID);
        //按照开始时间升序
        $Select->limit(1);
        $Entity = new ShiftEntity();
        $Select->order([ShiftEntity::FILED_START_TIME => Select::ORDER_ASCENDING]);
        $ShiftEntities = $this->ShiftManager->MyOrm->select($Select, $Entity);
        return $ShiftEntities->current();
    }
    
    /**
    * 获取某一巡逻员，最近一次的巡逻计划
    * 
    * @param int $userID  
    * @param int $workyardID  
    * @return        
    */
    public function getNextShiftOnPlan($workyardID, $userID)
    {
        $Select = $this->getSelectOnPlan($workyardID, $userID);
        //按照开始时间升序
        $Select->order([ShiftEntity::FILED_START_TIME => Select::ORDER_ASCENDING]);
        $Select->limit(1);
        $Entity = new ShiftEntity();
        $ShiftEntities = $this->ShiftManager->MyOrm->select($Select, $Entity);
        return $ShiftEntities->current();
    }
    
    /**
    * 
    * 
    * @param  
    * @return Select        
    */
    private function getSelectOnworking($workyardID, $userID)
    {
        $Select = new Select();
        $Select->from(['s'=>ShiftEntity::TABLE_NAME]);
        $on = 's.id=sg.shift_id';
        $Select->join(['sg'=>ShiftGuardEntity::TABLE_NAME], $on, [], Select::JOIN_LEFT);
        
        $Select ->where
        ->equalTo(ShiftEntity::FILED_WORKYARD_ID, $workyardID)
        ->equalTo(ShiftGuardEntity::FILED_GUARD_ID, $userID)
        ->lessThan(ShiftEntity::FILED_START_TIME, time())
        ->greaterThan(ShiftEntity::FILED_END_TIME, time());
        
        return $Select;
    }
    
    /**
    * 
    * 
    * @param  
    * @return Select        
    */
    private function getSelectOnPlan($workyardID, $userID)
    {
        $Select = new Select();
        $Select->from(['s'=>ShiftEntity::TABLE_NAME]);
        $on = 's.id=sg.shift_id';
        $Select->join(['sg'=>ShiftGuardEntity::TABLE_NAME], $on, [], Select::JOIN_LEFT);
        
        $Select ->where
        ->equalTo(ShiftEntity::FILED_WORKYARD_ID, $workyardID)
        ->equalTo(ShiftGuardEntity::FILED_GUARD_ID, $userID)
        ->greaterThan(ShiftEntity::FILED_START_TIME, time());
        
        return $Select;
    }
    
    /**
    * 
    * 
    * @param  
    * @return Select        
    */
    private function getSelectOnDone($userID)
    {
        $Select = new Select();
        $Select->from(['s'=>ShiftEntity::TABLE_NAME]);
        $on = 's.id=sg.shift_id';
        $Select->join(['sg'=>ShiftGuardEntity::TABLE_NAME], $on, [], Select::JOIN_LEFT);
        
        $Select ->where
        ->equalTo(ShiftGuardEntity::FILED_GUARD_ID, $userID)
        ->lessThan(ShiftEntity::FILED_END_TIME, time());
        
        return $Select;
    }
    
    /**
    * 获取某一巡逻员所有的值班安排
    * 值班开始时间 > 当前时间
    * 
    * @param int $userID  
    * @param int $workyardID  
    * @return        
    */
    public function getPaginatorOnPlan($workyardID, $userID, $page = 1)
    {
        $Select = $this->getSelectOnPlan($workyardID, $userID);
        //按照开始时间升序
        $Select->order([ShiftEntity::FILED_START_TIME=>Select::ORDER_ASCENDING]);
        $Entity = new ShiftEntity();
        $paginator = $this->ShiftManager->MyOrm->paginator($page, $Select, null, $Entity);
        $paginator->setItemCountPerPage(4);
        
        return $paginator;
    }
    
    public function getPaginatorOnDone($userID, $page = 1)
    {
        $Select = $this->getSelectOnDone($userID);
        //按照开始时间倒序
        $order = [ShiftEntity::FILED_START_TIME=>Select::ORDER_DESCENDING];
        $Entity = new ShiftEntity();
        $paginator = $this->ShiftManager->MyOrm->paginator($page, $Select, $order, $Entity);
        $paginator->setItemCountPerPage(4);
        return $paginator;
    }
    
    /**
    * 获取某工地所有的值班记录
    * 
    * @param  
    * @return        
    */
    public function getAllGuardsPaginator($workyard_id, $page=1, $query=[])
    {
        $Select = $this->getSelectAllGuard($workyard_id, $query);
        $Entity = new ShiftEntity();
        $paginator = $this->ShiftManager->MyOrm->paginator($page, $Select, null, $Entity);
        return $paginator;
    }
    
    /**
    * 获取所有巡逻员的执勤表
    * 用于管理员
    * 
    * @param  int $workyard_id
    * @param array $query 查询数据
    * @return Select       
    */
    public function getSelectAllGuard($workyard_id, $query)
    {
        $Select = new Select();
        $Select->from(['s'=>ShiftEntity::TABLE_NAME]);
        $on = 's.id=sg.shift_id';
        $Select->join(['sg'=>ShiftGuardEntity::TABLE_NAME], $on, [ShiftEntity::FILED_GUARD_ID], Select::JOIN_LEFT);
        $Select ->where->equalTo(ShiftEntity::FILED_WORKYARD_ID, $workyard_id);
        //process query
        //data
        //query the data before today
        $start = time();
        $Select->where
        ->lessThan(ShiftEntity::FILED_START_TIME, $start);
        //                 ->lessThan(ShiftEntity::FILED_END_TIME, $start);
        
        //if has where
        if (!empty($query['range']))
        {
            $range = $query['range'];
            $range = explode('-', $range);
            $start = strtotime($range[0]);
            $end   = strtotime($range[1]) + 24 * 60 * 60;
            $Select->where->between(ShiftEntity::FILED_START_TIME, $start, $end);
            $Select->where->between(ShiftEntity::FILED_END_TIME, $start, $end);
        }
        
        //set guard id
        //set guard_id
        if (!empty($query['guard_id']))
        {
            $guard_id = $query['guard_id'];
            $Select->where->equalTo(ShiftGuardEntity::FILED_GUARD_ID, $guard_id);
        }
        
        $order = [
            ShiftEntity::FILED_GUARD_ID=>Select::ORDER_ASCENDING,
            ShiftEntity::FILED_START_TIME=>Select::ORDER_DESCENDING,
        ];
        $Select->order($order);
        return $Select;
    }
    
    /**
    * 
    * 
    * @param  
    * @return Select        
    */
    public function hasShiftsOnPlan($userID, $workyardID)
    {
        $MyOrm =  $this->ShiftManager->MyOrm;
        $Select = $this->getSelectOnPlan($workyardID, $userID);
        $Entity = new ShiftEntity();
        $MyOrm->select($Select, $Entity);
        return !empty($MyOrm->getCount());
    }
    
    /**
    * 查看用户是否有已完成的值班记录
    * 
    * @param  int $userID
    * @return        
    */
    public function hasShiftsOnDone($userID)
    {
        $MyOrm =  $this->ShiftManager->MyOrm;
        $Select = $this->getSelectOnDone($userID);
        $Entity = new ShiftEntity();
        $MyOrm->select($Select, $Entity);
        return !empty($MyOrm->getCount());
    }
    
    public function hasShiftsOnWorking($userID, $workyardID)
    {
        $MyOrm =  $this->ShiftManager->MyOrm;
        $Select = $this->getSelectOnworking($workyardID, $userID);
        $Entity = new ShiftEntity();
        $MyOrm->select($Select, $Entity);
        return !empty($MyOrm->getCount());
    }
    
    public function getGuardNamesByShiftId($shift_id)
    {
        $shiftGuards = $this->getShiftGuardsByShiftId($shift_id);
        $guardName = '';
        foreach ($shiftGuards as $shiftGuard)
        {
            if (!empty($guardName)) {
                $guardName.= ' | ';
            }
            $guard_id = $shiftGuard->getGuard_id();
            $guard    = $this->UserManager->MyOrm->findOne($guard_id);
            $name     = $guard->getUserName();
            $guardName.=  $guard->getUserName();
        }
        
        return $guardName;
    }
    
    public function getShiftGuardsByShiftId($shift_id)
    {
        $where = [
            ShiftGuardEntity::FILED_SHIFT_ID => $shift_id
        ];
        $shiftGuards = $this->ShiftGuardManager->MyOrm->findAll($where);
        return $shiftGuards;
    }
}
