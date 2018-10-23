<?php
namespace Api\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Api\Service\UserManager;
use Zend\Authentication\AuthenticationService;
/**
* 获取用户所辖工地
*/
class AccessPlugin extends AbstractPlugin
{
    private $UserManager;
    private $mySession;
    private $AuthServer;
    
    public function __construct(
        UserManager $UserManager,
        $mySession
        )
    {
        $this->UserManager = $UserManager;
        $this->mySession   = $mySession;
        $this->AuthServer  = new AuthenticationService();
    }
    
    /**
     * 获取用户所辖工地id
     * 一个用户仅可同一时间管理一个工地
     * 如果是管理员则需要从session中读取所辖工地id
     * 如果是非管理员直接从数据库中读取所辖工地id
     *
     * @param string $identity 用户认证名
     * @param string $workyard_id 用户请求的workyard_id，
     * @return int $workyard_id 允许用户查看的 workyard_id
     */
    public function getWorkyardId($identity, $workyard_id=null)
    {
        //如果是管理员用户则直接返回用户请求的workyard_id
        if($this->isSuperAdmin($identity)) {
            return $workyard_id;
        }
        //如果是非管理员用户，则需要重新查询出用户的workyard_id
        $UserEntity = $this->UserManager->findUserByIdentity($identity);
        $workyard_id= $UserEntity ->getWorkyard_id();
        return $workyard_id;
    }
}