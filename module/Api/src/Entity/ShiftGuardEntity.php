<?php
namespace Api\Entity;

class ShiftGuardEntity
{
    //定义表名
    const TABLE_NAME            = 'shift_guard';
    //定义表字段名称
    //表示字段的常量名称不要更改
    const FILED_ID          = 'id';
    const FILED_GUARD_ID    = 'guard_id';
    const FILED_SHIFT_ID    = 'shift_id';
    
    /**
    * users表字段相匹配，字段不可错误
    */
    private $id;
    private $guard_id;
    private $shift_id;
    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $guard_id
     */
    public function getGuard_id()
    {
        return $this->guard_id;
    }

    /**
     * @return the $shift_id
     */
    public function getShift_id()
    {
        return $this->shift_id;
    }


}
