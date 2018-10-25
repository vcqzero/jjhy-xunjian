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
    
    public function getPaginator($page, $workyard_id, $where = [])
    {
        //默认显示今天~7天的值班安排
        //只有开始时间在此范围内即可
        $range = $where['range'];
        unset($where['range']);
        if (empty($range))
        {
            $start = strtotime(date('Y-m-d'));
            $end   = $start + 7 * 24 * 60 * 60;
        }else {
            $range = explode('-', $range);
            $start = strtotime($range[0]);
            $end   = strtotime($range[1]) + 24 * 60 * 60;
        }
        $where[ShiftEntity::FILED_WORKYARD_ID] =$workyard_id;
        $where[] = new \Zend\Db\Sql\Predicate\Between(ShiftEntity::FILED_START_TIME, $start, $end);
        $paginator = $this->ShiftManager->MyOrm->paginator($page, $where);
        $paginator::setDefaultItemCountPerPage(12);
        return $paginator;
    }
}
