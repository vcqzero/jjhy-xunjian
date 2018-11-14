<?php
namespace Api\Entity;

class ShiftTypeEntity
{
    //定义表名
    const TABLE_NAME        = 'shift_type';
    //定义表字段名称
    //表示字段的常量名称不要更改
    const FILED_ID   = 'id';
    const FILED_NAME = 'name';
    const FILED_START_TIME = 'start_time';
    const FILED_END_TIME   = 'end_time';
    const FILED_IS_NEXT_DAY= 'is_next_day';
    const FILED_WORKYARD_ID= 'workyard_id';
    
    /**
    * users表字段相匹配，字段不可错误
    */
    private $id;
    private $name;
    private $start_time;
    private $end_time;
    private $is_next_day;
    private $workyard_id;

    /**
     * @return the $is_next_day
     */
    public function getIs_next_day()
    {
        return $this->is_next_day;
    }

    /**
     * @return the $workyard_id
     */
    public function getWorkyard_id()
    {
        return $this->workyard_id;
    }

    /**
     * @return the $end_time
     */
    public function getEnd_time()
    {
        return $this->end_time;
    }

    /**
     * @return the $start_time
     */
    public function getStart_time()
    {
        return $this->start_time;
    }

    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
    }

}
