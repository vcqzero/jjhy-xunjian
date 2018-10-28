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
    
    public function getEntitiesOnPlayInWeek($workyard_id)
    {
        $where[] = new Between(ShiftEntity::FILED_START_TIME, time(), strtotime('2099-12-31'));
        $where[ShiftEntity::FILED_WORKYARD_ID] = $workyard_id;
        $order = [ShiftEntity::FILED_START_TIME=> Select::ORDER_ASCENDING];
        $limit = 7;
        return $this->ShiftManager->MyOrm->findAll($where, $order, $limit);
    }
    
    public function getPaginator($page, $workyard_id, $where = [])
    {
        $start = strtotime(date('Y-m-d'));
        $end   = strtotime('2099-12-31');
        
        $range = $where['range'];
        unset($where['range']);
        if (!empty($range))
        {
            $range = explode('-', $range);
            $start = strtotime($range[0]) >= $start ? strtotime($range[0]) : $start;
            $end   = strtotime($range[1]) + 24 * 60 * 60;
        }
        $where[ShiftEntity::FILED_WORKYARD_ID] =$workyard_id;
        $where[] = new \Zend\Db\Sql\Predicate\Between(ShiftEntity::FILED_START_TIME, $start, $end);
        $order = [ShiftEntity::FILED_START_TIME=>Select::ORDER_ASCENDING];
        $paginator = $this->ShiftManager->MyOrm->paginator($page, $where, $order);
        $paginator::setDefaultItemCountPerPage(12);
        return $paginator;
    }
    
    public function getHistoryPaginator($page, $workyard_id, $where = [])
    {
        $end   = strtotime(date('Y-m-d'));
        
        //默认显示今天~7天的值班安排
        //只有开始时间在此范围内即可
        $range = $where['range'];
        unset($where['range']);
        $range = $where['range'];
        unset($where['range']);
        if (!empty($range))
        {
            $range = explode('-', $range);
            $start = strtotime($range[0]) ;
            $end_range   = strtotime($range[1]) + 24 * 60 * 60;
            $end   = $end_range >= $end ? $end : $end_range; 
        }
        
        $where[ShiftEntity::FILED_WORKYARD_ID] =$workyard_id;
        $where[] = new \Zend\Db\Sql\Predicate\Between(ShiftEntity::FILED_END_TIME, 0, $end);
        $order = [ShiftEntity::FILED_START_TIME=>Select::ORDER_DESCENDING];
        $paginator = $this->ShiftManager->MyOrm->paginator($page, $where, $order);
        $paginator::setDefaultItemCountPerPage(12);
        return $paginator;
    }
}
