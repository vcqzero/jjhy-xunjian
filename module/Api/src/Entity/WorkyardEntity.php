<?php
namespace Api\Entity;

class WorkyardEntity
{
    //定义表名
    const TABLE_NAME            = 'workyards';
    //定义表字段名称
    //表示字段的常量名称不要更改
    const FILED_ID          = 'id';
    const FILED_NAME        = 'name';
    const FILED_ADDRESS     = 'address';
    const FILED_ADDRESS_PATH= 'address_path';
    const FILED_NOTE        = 'note';
    /**
    * users表字段相匹配，字段不可错误
    */
    private $id;
    private $name;
    private $address;
    private $address_path;
    private $note;
    /**
     * @return the $address_path
     */
    public function getAddress_path()
    {
        return $this->address_path;
    }

    /**
     * @return the $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return the $note
     */
    public function getNote()
    {
        return $this->note;
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
