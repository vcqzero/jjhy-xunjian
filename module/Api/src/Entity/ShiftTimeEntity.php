<?php
namespace Api\Entity;

/**
* 某一巡检任务和巡检次数关系
*/
class ShiftTimeEntity
{
    //定义表名
    const TABLE_NAME            = 'shift_time';
    //定义表字段名称
    //表示字段的常量名称不要更改
    const FILED_ID                  = 'id';
    const FILED_SHIFT_ID            = 'shift_id';
    const FILED_GUARD_ID            = 'guard_id';
    const FILED_STATUS              = 'status';
    
    /**
    * users表字段相匹配，字段不可错误
    */
    private $id;
    private $shift_id;
    private $guard_id;
    private $status;
    
    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $shift_id
     */
    public function getShift_id()
    {
        return $this->shift_id;
    }

    /**
     * @return the $guard_id
     */
    public function getGuard_id()
    {
        return $this->guard_id;
    }

    /**
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    
}
