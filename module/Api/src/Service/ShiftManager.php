<?php
namespace Api\Service;

use Api\Filter\FormFilter;
use Api\Model\MyOrm;
use Api\Entity\ShiftEntity;

/**
* 所有的配置信息，都从此读取
*/
class ShiftManager
{
    const PATH_FORM_FILTER_CONFIG = 'module/Api/src/Filter/rules/Shift.php';
    
    public $MyOrm;
    public $FormFilter;
    public function __construct(
        MyOrm $MyOrm,
        FormFilter $FormFilter)
    {
        $MyOrm->setEntity(new ShiftEntity());
        $MyOrm->setTableName(ShiftEntity::TABLE_NAME);
        $this->MyOrm = $MyOrm;
        $FormFilter->setRules(include self::PATH_FORM_FILTER_CONFIG);
        $this->FormFilter = $FormFilter;
    }
    
    /**
    * 执行新增值班表
    * 
    * @param  array $values
    * @return int $shift_id      
    */
    public function add($values)
    {
        //do filter
        $values = $this->FormFilter->getFilterValues($values);
        
        //执行增加操作
        $res = $this->MyOrm->insert($values);
        $shift_id = $this->MyOrm->getLastInsertId();
        return $shift_id;
    }
    
    public function processShiftType($values, $date, $ShiftTypeEntity)
    {
        $shfit_type_name = $ShiftTypeEntity->getName();
        $start_time      = $ShiftTypeEntity->getStart_time();
        $end_time        = $ShiftTypeEntity->getEnd_time();
        $is_next_day     = $ShiftTypeEntity->getIs_next_day();
        
        $start_time = $date . ' ' . $start_time;
        $start_time = strtotime($start_time);
        if ($is_next_day) {
            $date = date('Y-m-d', strtotime($date) + 24 * 60 * 60);
        }
        
        $end_time   = $date . ' ' . $end_time;
        $end_time   = strtotime($end_time);
        
        $values[ShiftEntity::FILED_SHIFT_TYPE_NAME] = $shfit_type_name;
        $values[ShiftEntity::FILED_START_TIME] = $start_time;
        $values[ShiftEntity::FILED_END_TIME] = $end_time;
        
        return $values;
    }
    
    /**
    * 根据日期范围 获取每一天
    * 
    * @param  
    * @return        
    */
    public function getDates($dateRange)
    {
        $dateRange = explode('-', $dateRange);
        $start  = strtotime($dateRange[0]);
        $end    = strtotime($dateRange[1]);
        
        $date   = $start;
        $dates = [];
        while ($date >= $start && $date<= $end)  
        {
            $dates[] = date("Y-m-d", $date);
            $date += 24 *60 *60 ;
        }
        
        return $dates;
    }
}

