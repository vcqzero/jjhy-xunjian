<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\PointManager;
use Api\Entity\PointEntity;
use Api\Service\ShiftManager;

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
    * 获取某次巡检任务的巡检点
    * 巡检点创建时间小于任务开始时间
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
}
