<?php
namespace Api\Entity;

class PointEntity
{
    //定义表名
    const TABLE_NAME            = 'points';
    //定义表字段名称
    //表示字段的常量名称不要更改
    const FILED_ID          = 'id';
    const FILED_NAME        = 'name';
    const FILED_WORKYARD_ID = 'workyard_id';
    const FILED_QRCODE_FILENAME = 'qrcode_filename';
    const FILED_CREATED_BY  = 'created_by';
    const FILED_ADDRESS     = 'address';
    
    /**
    * users表字段相匹配，字段不可错误
    */
    private $id;
    private $name;
    private $workyard_id;
    private $qrcode_filename;
    private $created_by;
    private $address;
    
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

    /**
     * @return the $workyard_id
     */
    public function getWorkyard_id()
    {
        return $this->workyard_id;
    }

    /**
     * @return the $qrcode_filename
     */
    public function getQrcode_filename()
    {
        return $this->qrcode_filename;
    }

    /**
     * @return the $created_by
     */
    public function getCreated_by()
    {
        return $this->created_by;
    }

    /**
     * @return the $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    
}
