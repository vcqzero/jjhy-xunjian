<?php
namespace Api\Service;

use Api\Filter\FormFilter;
use Api\Model\MyOrm;
use Api\Entity\ShiftTypeEntity;

/**
* 所有的配置信息，都从此读取
*/
class ShiftTypeManager
{
    const PATH_FORM_FILTER_CONFIG = 'module/Api/src/Filter/rules/ShiftType.php';
    
    public $MyOrm;
    public $FormFilter;
    
    const IS_NEXT_DAY_YES = 'YES';
    const IS_NEXT_DAY_NO  = 'NO';
    
    public function __construct(
        MyOrm $MyOrm,
        FormFilter $FormFilter)
    {
        $MyOrm->setEntity(new ShiftTypeEntity());
        $MyOrm->setTableName(ShiftTypeEntity::TABLE_NAME);
        $this->MyOrm = $MyOrm;
        $FormFilter->setRules(include self::PATH_FORM_FILTER_CONFIG);
        $this->FormFilter = $FormFilter;
    }
}

