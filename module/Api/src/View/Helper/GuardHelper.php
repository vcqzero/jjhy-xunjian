<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\UserManager;
use Api\Entity\UserEntity;
use Api\Service\ShiftGuardManager;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class GuardHelper extends AbstractHelper
{
    private $UserManager;
    private $ShiftGuardManager;
    
    public function __construct(
        UserManager $UserManager,
        ShiftGuardManager $ShiftGuardManager
        ) 
    {
        $this->UserManager   = $UserManager;
        $this->ShiftGuardManager = $ShiftGuardManager;
    }
    
    public function getPaginator($workyard_id, $page = 1, $query= [])
    {
        $where[UserEntity::FILED_WORKYARD_ID] = $workyard_id;
        $where[UserEntity::FILED_ROLE] = UserManager::ROLE_WORKYARD_GUARD;
        return $this->UserManager->MyOrm->paginator($page, $where);
    }
    /**
    * 查询所辖工地所有巡检员
    * 
    * @param  
    * @return        
    */
    public function getEntitiesBy($workyard_id)
    {
        $where = [
            UserEntity::FILED_WORKYARD_ID => $workyard_id,
            UserEntity::FILED_ROLE => UserManager::ROLE_WORKYARD_GUARD,
        ];
        $Guards = $this->UserManager->MyOrm->findAll($where);
        return $Guards;
    }
    
    /**
    * 获取select2插件的数据源配置
    * 
    * @param  
    * @return string json string       
    */
    public function getGuardDataForSelect2AsJson($workyard_id, $shift_id = null)
    {
        $where = [
            UserEntity::FILED_WORKYARD_ID => $workyard_id,
            UserEntity::FILED_ROLE => UserManager::ROLE_WORKYARD_GUARD
        ];
        $select2_config = [];
        $GuardEntities = $this->getEntitiesBy($workyard_id);
        foreach ($GuardEntities as $GuardEntity)
        {
            $guard_id = $GuardEntity->getId();
            $username = $GuardEntity->getUsername();
            $selected = empty($shift_id) ? false : $this->ShiftGuardManager->existGuardInShift($shift_id, $guard_id);
            $guard = [
                'id'    => $guard_id,
                'text'  => $username,
                "selected" => $selected,
            ];
            $select2_config[] = $guard;
        }
        
        return json_encode($select2_config);
    }
}
