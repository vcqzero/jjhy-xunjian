<?php
namespace Api\Entity;

/**
* 某一巡逻任务中某一巡逻次数和巡逻点的关系
*/
class ShiftTimePointEntity
{
    //定义表名
    const TABLE_NAME            = 'shift_time_point';
    //定义表字段名称
    //表示字段的常量名称不要更改
    const FILED_ID                      = 'id';
    const FILED_SHIFT_TIME_ID           = 'shift_time_id';
    const FILED_POINT_ID                = 'point_id';
    const FILED_NOTE                    = 'note';
    const FILED_TIME                    = 'time';
    const FILED_ADDRESS_PATH            = 'address_path';
    
    /**
    * users表字段相匹配，字段不可错误
    */
    private $id;
    private $shift_time_id;
    private $point_id;
    private $note;
    private $time;
    private $address_path;
    /**
     * @return the $time
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return the $shift_time_id
     */
    public function getShift_time_id()
    {
        return $this->shift_time_id;
    }

    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $point_id
     */
    public function getPoint_id()
    {
        return $this->point_id;
    }

    /**
     * @return the $note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return the $address_path
     */
    public function getAddress_path()
    {
        return $this->address_path;
    }
}
