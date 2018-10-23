<?php
namespace Api\Entity;

class UserEntity
{
    //定义表名
    const TABLE_NAME            = 'users';
    //定义表字段名称
    //表示字段的常量名称不要更改
    const FILED_ID              = 'id';
    const FILED_USERNAME        = 'username';
    const FILED_PASSWORD        = 'password';
    const FILED_INITIAL_PASSWORD= 'initial_password';
    const FILED_REALNAME        = 'realname';
//     const FILED_EMAIL           = 'email';
    const FILED_TEL             = 'tel';
    const FILED_STATUS          = 'status';
    const FILED_WORKYARD_ID     = 'workyard_id';
    const FILED_ROLE            = 'role';
    /**
    * users表字段相匹配，字段不可错误
    */
    private $id;
    private $username;
    private $password;
    private $initial_password;
    private $realname;
    private $email;
    private $tel;
    private $status;
    private $workyard_id;
    private $role;
    /**
     * @return the $role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return the $initial_password
     */
    public function getInitial_password()
    {
        return $this->initial_password;
    }

    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return the $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return the $realname
     */
    public function getRealname()
    {
        return $this->realname;
    }

    /**
     * @return the $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return the $tel
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return the $workyard_id
     */
    public function getWorkyard_id()
    {
        return $this->workyard_id;
    }

    
}
