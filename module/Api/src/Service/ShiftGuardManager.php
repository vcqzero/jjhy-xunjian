<?php
namespace Api\Service;

use Api\Model\MyOrm;
use Api\Entity\ShiftGuardEntity;

/**
* 所有的配置信息，都从此读取
*/
class ShiftGuardManager
{
    public $MyOrm;
    public function __construct(
        MyOrm $MyOrm)
    {
        $MyOrm->setEntity(new ShiftGuardEntity());
        $MyOrm->setTableName(ShiftGuardEntity::TABLE_NAME);
        $this->MyOrm = $MyOrm;
    }
    
    public function add($guard_id, $shift_id)
    {
        $values = [
            ShiftGuardEntity::FILED_GUARD_ID => $guard_id,
            ShiftGuardEntity::FILED_SHIFT_ID => $shift_id,
        ];
        $res = $this->MyOrm->insert($values);
        
        return $res;
    }
    
    public function deleteBy($shift_id)
    {
        $where = [
            ShiftGuardEntity::FILED_SHIFT_ID => $shift_id
        ];
        
        return $this->MyOrm->delete($where);
    }
}

