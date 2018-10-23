<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\WorkyardManager;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class WorkyardHelper extends AbstractHelper 
{
    private $WorkyardManager;
    
    public function __construct(
        WorkyardManager $WorkyardManager
        )
    {
        $this->WorkyardManager = $WorkyardManager;
    }
    
    public function getWorkyardEntity($workyardID)
    {
        return $this->WorkyardManager->MyOrm->findOne($workyardID);
    }
    
    public function getEntities()
    {
        return $this->WorkyardManager->MyOrm->findAll();
    }
    
    public function getName($workyard_id)
    {
        $Entity = $this->WorkyardManager->MyOrm->findOne($workyard_id);
        return $Entity->getName();
    }
    
    public function getPaginator($page, $where = [])
    {
        $paginator = $this->WorkyardManager->MyOrm->paginator($page, $where);
        $paginator::setDefaultItemCountPerPage(12);
        return $paginator;
    }
    
    public function count($where = [])
    {
        return $this->WorkyardManager->MyOrm->count($where);
    }
}
