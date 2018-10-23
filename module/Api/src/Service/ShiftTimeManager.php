<?php
namespace Api\Service;

use Api\Model\MyOrm;
use Api\Entity\ShiftTimeEntity;

/**
* 所有的配置信息，都从此读取
*/
class ShiftTimeManager
{
    /**
    * @var has done
    */
    const STATUS_DONE = 'DONE';
    
    /**
    * @var working
    */
    const STATUS_WORKING = 'WORKING';
    
    public $MyOrm;
    public function __construct(
        MyOrm $MyOrm)
    {
        $MyOrm->setEntity(new ShiftTimeEntity());
        $MyOrm->setTableName(ShiftTimeEntity::TABLE_NAME);
        $this->MyOrm = $MyOrm;
    }
}

