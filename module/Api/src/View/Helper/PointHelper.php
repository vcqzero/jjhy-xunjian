<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\PointManager;
use Api\Entity\PointEntity;
use Api\Service\ShiftManager;
use Api\Entity\ShiftEntity;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Predicate\Between;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class PointHelper extends AbstractHelper 
{
    private $PointManager;
    private $ShiftManager;
    
    public function __construct(
        PointManager $PointManager,
        ShiftManager $ShiftManager
        )
    {
        $this->PointManager = $PointManager;
        $this->ShiftManager = $ShiftManager;
    }
    
    public function getEntity($id)
    {
        return $this->PointManager->MyOrm->findOne($id);
    }
    
    public function getEntities($where = [])
    {
        return $this->PointManager->MyOrm->findAll($where);
    }
    
    /**
    * 获取某次巡逻任务的巡逻点
    * 巡逻点创建时间小于任务开始时间
    * 
    * @param  
    * @return        
    */
    public function getEntitiesOnShift($workyard_id, $shift_id = null)
    {
        if (!empty($shift_id))
        {
            $ShiftEntity     = $this->ShiftManager->MyOrm->findOne($shift_id);
            $start_time      = $ShiftEntity->getStart_time();
            $created         = $ShiftEntity->getCreated();
            $start_time      = $start_time >= $created ? $start_time : $created;
        }else {
            $start_time = time();
        }
        $where[PointEntity::FILED_WORKYARD_ID] = $workyard_id;
        $where[] = new \Zend\Db\Sql\Predicate\Between(PointEntity::FILED_CREATED, 0, $start_time);
        return $this->PointManager->MyOrm->findAll($where);
    }
    
    public function getName($id)
    {
        $Entity = $this->PointManager->MyOrm->findOne($id);
        return $Entity->getName();
    }
    
    public function getPaginator($page, $where = [], $workyard_id)
    {
        $where[PointEntity::FILED_WORKYARD_ID] = $workyard_id;
        $paginator = $this->PointManager->MyOrm->paginator($page, $where);
        $paginator::setDefaultItemCountPerPage(12);
        return $paginator;
    }
    
    public function getPointNamesBy($workyard_id, $shift_id=null)
    {
        $points = $this->getEntitiesOnShift($workyard_id, $shift_id);
        $names = '';
        foreach ($points as $point)
        {
            $name = $point->getName();
            $names = $names . "&nbsp;&nbsp;" . $name;
        }
        
        return $names;
    }
    
    public function canDelete($point_id, $workyard_id)
    {
        $Point  = $this->PointManager->MyOrm->findOne($point_id);
        $created= $Point->getCreated();
        //如果巡逻点正在使用中，则不可删除
        //获取当前正在巡逻的任务
        $where1 = [
            ShiftEntity::FILED_WORKYARD_ID => $workyard_id,
        ];
        $where1[]= new Between(ShiftEntity::FILED_END_TIME, time(), strtotime('2099-12-31'));
        $where1[]= new Between(ShiftEntity::FILED_START_TIME, 0, time());
        $where1[]= new Between(ShiftEntity::FILED_CREATED, $created, strtotime('2099-12-31'));
        $count = $this->ShiftManager->MyOrm->count($where1);
        if($count > 0) {
            return false;
        }
        
        $where2 = [
            ShiftEntity::FILED_WORKYARD_ID => $workyard_id,
        ];
        $where2[]= new Between(ShiftEntity::FILED_END_TIME, time(), strtotime('2099-12-31'));
        $where2[]= new Between(ShiftEntity::FILED_START_TIME, 0, time());
        $where2[]= new Between(ShiftEntity::FILED_CREATED, 0, $created);
        $where2[]= new Between(ShiftEntity::FILED_START_TIME, $created, strtotime('2099-12-31'));
        $count = $this->ShiftManager->MyOrm->count($where2);
        if($count > 0) {
            return false;
        }
        return true;
    }
}
