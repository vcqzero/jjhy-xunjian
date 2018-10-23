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
}

