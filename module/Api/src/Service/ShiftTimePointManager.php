<?php
namespace Api\Service;

use Api\Model\MyOrm;
use Api\Entity\ShiftTimePointEntity;

/**
* 所有的配置信息，都从此读取
*/
class ShiftTimePointManager
{
    public $MyOrm;
    public function __construct(
        MyOrm $MyOrm)
    {
        $MyOrm->setEntity(new ShiftTimePointEntity());
        $MyOrm->setTableName(ShiftTimePointEntity::TABLE_NAME);
        $this->MyOrm = $MyOrm;
    }
}

