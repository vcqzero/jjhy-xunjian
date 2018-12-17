<?php
namespace Api\Entity;

class RegisterEntity
{
    //定义表名
    const TABLE_NAME            = 'registers';
    //定义表字段名称
    //表示字段的常量名称不要更改
    const FILED_ID          = 'id';
    const FILED_WORKYARD_NAME       = 'workyard_name';
    const FILED_WORKYARD_ADDRESS    = 'workayrd_address';
    const FILED_ADMIN_OPENID        = 'admin_openid';
    const FILED_ADMIN_REALNAME      = 'admin_realname';
    const FILED_ADMIN_TEL           = 'admin_tel';
    const FILED_ADMIN_USERNAME      = 'admin_username';
    const FILED_ADMIN_PASSWORD      = 'admin_password';
    const FILED_CREATED_AT          = 'created_at';
    const FILED_STATUS          = 'status';
    const FILED_NOTE          = 'note';
    
    /**
    * users表字段相匹配，字段不可错误
    */
    private $id;
    private $workyard_name;
    private $workayrd_address;
    private $admin_openid;
    private $admin_realname;
    private $admin_tel;
    private $admin_username;
    private $admin_password;
    private $created_at;
    private $status;
    private $note;
    /**
     * @return the $note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $workyard_name
     */
    public function getWorkyard_name()
    {
        return $this->workyard_name;
    }

    /**
     * @return the $workayrd_address
     */
    public function getWorkayrd_address()
    {
        return $this->workayrd_address;
    }

    /**
     * @return the $admin_openid
     */
    public function getAdmin_openid()
    {
        return $this->admin_openid;
    }

    /**
     * @return the $admin_realname
     */
    public function getAdmin_realname()
    {
        return $this->admin_realname;
    }

    /**
     * @return the $admin_tel
     */
    public function getAdmin_tel()
    {
        return $this->admin_tel;
    }

    /**
     * @return the $admin_username
     */
    public function getAdmin_username()
    {
        return $this->admin_username;
    }

    /**
     * @return the $admin_password
     */
    public function getAdmin_password()
    {
        return $this->admin_password;
    }

    /**
     * @return the $created_at
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

}
