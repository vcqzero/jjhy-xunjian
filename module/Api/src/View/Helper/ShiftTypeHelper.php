<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\ShiftTypeManager;
use Api\Entity\ShiftTypeEntity;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class ShiftTypeHelper extends AbstractHelper 
{
    private $ShiftTypeManager;
    
    public function __construct(
        ShiftTypeManager $ShiftTypeManager
        )
    {
        $this->ShiftTypeManager = $ShiftTypeManager;
    }
    
    public function getEntity($id)
    {
        return $this->ShiftTypeManager->MyOrm->findOne($id);
    }
    
    public function getEntities($where)
    {
        return $this->ShiftTypeManager->MyOrm->findAll($where);
    }
    
    public function getName($id)
    {
        $Entity = $this->ShiftTypeManager->MyOrm->findOne($id);
        return $Entity->getName();
    }
    
    public function getPaginator($page, $where = [], $workyard_id)
    {
        $where[ShiftTypeEntity::FILED_WORKYARD_ID] = $workyard_id;
        $paginator = $this->ShiftTypeManager->MyOrm->paginator($page, $where);
        $paginator::setDefaultItemCountPerPage(12);
        return $paginator;
    }
}
