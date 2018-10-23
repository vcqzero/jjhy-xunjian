<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\PointManager;
use Api\Entity\PointEntity;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class PointHelper extends AbstractHelper 
{
    private $PointManager;
    
    public function __construct(
        PointManager $PointManager
        )
    {
        $this->PointManager = $PointManager;
    }
    
    public function getEntity($id)
    {
        return $this->PointManager->MyOrm->findOne($id);
    }
    
    public function getEntities($where = [])
    {
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
