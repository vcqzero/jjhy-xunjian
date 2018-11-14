<?php
namespace Api\Service;

use Api\Filter\FormFilter;
use Api\Model\MyOrm;
use Api\Entity\WorkyardEntity;

/**
* 所有的配置信息，都从此读取
*/
class WorkyardManager
{
    const PATH_FORM_FILTER_CONFIG = 'module/Api/src/Filter/rules/Workyard.php';
    
    public $MyOrm;
    public $FormFilter;
    public function __construct(
        MyOrm $MyOrm,
        FormFilter $FormFilter)
    {
        $MyOrm->setEntity(new WorkyardEntity());
        $MyOrm->setTableName(WorkyardEntity::TABLE_NAME);
        $this->MyOrm = $MyOrm;
        $FormFilter->setRules(include self::PATH_FORM_FILTER_CONFIG);
        $this->FormFilter = $FormFilter;
    }
}

