<?php
namespace Api\Entity;

class ShiftEntity
{
    //定义表名
    const TABLE_NAME        = 'shifts';
    //定义表字段名称
    //表示字段的常量名称不要更改
    const FILED_ID          = 'id';
    const FILED_WORKYARD_ID = 'workyard_id';
    const FILED_NOTE    = 'note';
    const FILED_TIMES   = 'times';
    const FILED_START_TIME   = 'start_time';
    const FILED_END_TIME     = 'end_time';
    const FILED_SHIFT_TYPE_NAME= 'shift_type_name';
    
    /**
    * users表字段相匹配，字段不可错误
    */
    private $id;
    private $shift_type_name;
    private $note;
    private $workyard_id;
    private $times;
    private $start_time;
    private $end_time;
    
    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $shift_type_name
     */
    public function getShift_type_name()
    {
        return $this->shift_type_name;
    }

    /**
     * @return the $note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return the $workyard_id
     */
    public function getWorkyard_id()
    {
        return $this->workyard_id;
    }

    /**
     * @return the $times
     */
    public function getTimes()
    {
        return $this->times;
    }

    /**
     * @return the $start_time
     */
    public function getStart_time()
    {
        return $this->start_time;
    }

    /**
     * @return the $end_time
     */
    public function getEnd_time()
    {
        return $this->end_time;
    }

}
