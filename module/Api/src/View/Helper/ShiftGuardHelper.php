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
    * 获取某一巡检员，此时此刻巡检任务
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
        $ShiftEntities = $this->ShiftManager->MyOrm->select($Select, $Entity);
        return $ShiftEntities->current();
    }
    
    /**
    * 获取某一巡检员，最近一次的巡检计划
    * 
    * @param int $userID  
    * @param int $workyardID  
    * @return        
    */
    public function getNextShiftOnPlan($workyardID, $userID)
    {
        $Select = $this->getSelectOnPlan($workyardID, $userID);
        //按照开始时间升序
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
    private function getSelectOnDone($workyardID, $userID)
    {
        $Select = new Select();
        $Select->from(['s'=>ShiftEntity::TABLE_NAME]);
        $on = 's.id=sg.shift_id';
        $Select->join(['sg'=>ShiftGuardEntity::TABLE_NAME], $on, [], Select::JOIN_LEFT);
        
        $Select ->where
        ->equalTo(ShiftEntity::FILED_WORKYARD_ID, $workyardID)
        ->equalTo(ShiftGuardEntity::FILED_GUARD_ID, $userID)
        ->lessThan(ShiftEntity::FILED_END_TIME, time());
        
        return $Select;
    }
    
    /**
    * 获取某一巡检员所有的值班安排
    * 值班开始时间 > 当前时间
    * 
    * @param int $userID  
    * @param int $workyardID  
    * @return        
    */
    public function getPaginator($workyardID, $userID, $type, $page = 1)
    {
        if ($type == self::TYPE_PLAN) {
            $Select = $this->getSelectOnPlan($workyardID, $userID);
            //按照开始时间升序
            $Select->order([ShiftEntity::FILED_START_TIME=>Select::ORDER_ASCENDING]);
        }else 
        {
            $Select = $this->getSelectOnDone($workyardID, $userID);
            //按照开始时间倒序
            $Select->order([ShiftEntity::FILED_START_TIME=>Select::ORDER_DESCENDING]);
        }
        
        $Entity = new ShiftEntity();
        $paginator = $this->ShiftManager->MyOrm->paginator($page, $Select, null, $Entity);
        $paginator->setItemCountPerPage(4);
        $paginator->setCurrentPageNumber($page);
        
        return $paginator;
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
    
    public function hasShiftsOnDone($userID, $workyardID)
    {
        $MyOrm =  $this->ShiftManager->MyOrm;
        $Select = $this->getSelectOnDone($workyardID, $userID);
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
    
}