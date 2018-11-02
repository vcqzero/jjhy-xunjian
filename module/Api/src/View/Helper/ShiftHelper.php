<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\ShiftManager;
use Api\Service\ShiftGuardManager;
use Zend\Db\Sql\Predicate\Predicate;
use Api\Entity\ShiftEntity;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Between;
use Api\Entity\ShiftGuardEntity;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class ShiftHelper extends AbstractHelper 
{
    private $ShiftManager;
    private $ShiftGuardManager;
    
    public function __construct(
        ShiftManager $ShiftManager,
        ShiftGuardManager $ShiftGuardManager
        )
    {
        $this->ShiftManager = $ShiftManager;
        $this->ShiftGuardManager= $ShiftGuardManager;
    }
    
    public function getEntity($id)
    {
        return $this->ShiftManager->MyOrm->findOne($id);
    }
    
    public function getEntities($where =[])
    {
        return $this->ShiftManager->MyOrm->findAll($where);
    }
    
//     public function getEntitiesOnPlayInWeek($workyard_id)
//     {
//         $where[] = new Between(ShiftEntity::FILED_START_TIME, time(), strtotime('2099-12-31'));
//         $where[ShiftEntity::FILED_WORKYARD_ID] = $workyard_id;
//         $order = [ShiftEntity::FILED_START_TIME=> Select::ORDER_ASCENDING];
//         $limit = 7;
//         return $this->ShiftManager->MyOrm->findAll($where, $order, $limit);
//     }
    
    public function getPaginator($workyard_id, $page =1, $query= [])
    {
        //about date range
        //show the future data by default
        $where[ShiftEntity::FILED_WORKYARD_ID] =$workyard_id;
        $start = strtotime(date('Y-m-d'));
        $end   = strtotime('2099-12-31');
        $where[] = new \Zend\Db\Sql\Predicate\Between(ShiftEntity::FILED_START_TIME, $start, $end);
        
        //if has where
        if (!empty($query['range']))
        {
            $range = $query['range'];
            $range = explode('-', $range);
            $start = strtotime($range[0]) ;
            $end   = strtotime($range[1]) + 24 * 60 * 60;
            $where[] = new \Zend\Db\Sql\Predicate\Between(ShiftEntity::FILED_START_TIME, $start, $end);
        }
        
        //set guard_id
        if (!empty($query['guard_id']))
        {
            $guard_id = $query['guard_id'];
            $where[ShiftGuardEntity::FILED_GUARD_ID] = $guard_id;
        }
        
        //set join select
        $Select = new Select();
        $Select->from(['s'=>ShiftEntity::TABLE_NAME]);
        $on = 's.id=sg.shift_id';
        $Select->join(['sg'=>ShiftGuardEntity::TABLE_NAME], $on, [], Select::JOIN_RIGHT);
        $Select->where($where);
        
        //order
        $order = [ShiftEntity::FILED_START_TIME=>Select::ORDER_ASCENDING];
        $Select->order($order);
        
        //group by
        $Select->group(ShiftEntity::FILED_ID);
        
//         $this->ShiftManager->MyOrm->startDebug();
        $paginator = $this->ShiftManager->MyOrm->paginator($page, $Select, null, new ShiftEntity());
//         $this->ShiftManager->MyOrm->stopDebug();
        return $paginator;
    }
    
    public function getHistoryPaginator($page, $workyard_id, $query = [])
    {
        //about date range
        //show the future data by default
        $end   = strtotime(date('Y-m-d'));
        $start = 0;
        $where[ShiftEntity::FILED_WORKYARD_ID] =$workyard_id;
        $where[] = new \Zend\Db\Sql\Predicate\Between(ShiftEntity::FILED_END_TIME, $start, $end);
        //if has where
        if (!empty($query['range']))
        {
            $range = $query['range'];
            $range = explode('-', $range);
            $start = strtotime($range[0]);
            $end   = strtotime($range[1]) + 24 * 60 * 60;
            $where[] = new \Zend\Db\Sql\Predicate\Between(ShiftEntity::FILED_END_TIME, $start, $end);
        }
        
        //set guard_id
        if (!empty($query['guard_id']))
        {
            $guard_id = $query['guard_id'];
            $where[ShiftGuardEntity::FILED_GUARD_ID] = $guard_id;
        }
        
        //set join select
        $Select = new Select();
        $Select->from(['s'=>ShiftEntity::TABLE_NAME]);
        $on = 's.id=sg.shift_id';
        $Select->join(['sg'=>ShiftGuardEntity::TABLE_NAME], $on, [], Select::JOIN_LEFT);
        $Select->where($where);
        
        //order
        $order = [ShiftEntity::FILED_START_TIME=>Select::ORDER_DESCENDING];
        $Select->order($order);
        
        //group by
        $Select->group(ShiftEntity::FILED_ID);
        
        $paginator = $this->ShiftManager->MyOrm->paginator($page, $Select, null, new ShiftEntity());
        $paginator::setDefaultItemCountPerPage(12);
        return $paginator;
    }
    
    public function isNextDay($start_time, $end_time)
    {
        $start_day   = date('Y-m-d', $start_time);
        $end_day     = strtotime($start_day) + 60 * 60 * 24;
        $is_next_day = $end_time >= $end_day;
        return $is_next_day;
    }
}
