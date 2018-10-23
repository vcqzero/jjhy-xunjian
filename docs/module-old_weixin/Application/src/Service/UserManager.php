<?php
namespace Application\Service;

use Zend\Db\Sql\Sql;
use Zend\Log\Logger;
use Application\Model\CommonModel;
use Admin\Api\YouzanApi;
use Application\Service\CardManager;
use Zend\Db\Sql\Ddl\Column\Decimal;

require_once 'vendor/youzan/lib/YZTokenClient.php';

class UserManager
{
    protected $commonModel      = null;
    protected $table_users      = 'users';
    protected $youzan           = null;
    protected $card             = null;
    protected $logger           = null;
    
    const USER_STATUS_ENABLE    ='ENABLE';
    const USER_STATUS_DISABLED  ='DIASABLED';

    public function __construct(CommonModel $commonModle, CardManager $card, Logger $logger)
    {
        $this->card             = $card;
        $this->logger           = $logger;
        $this->commonModel      = $commonModle;
        $this->youzan           = YouzanApi::getInstanse($logger);
    }
    
    /**
     * 如果用户不存在，则创建用户
     *
     * @param  string $openid           
     * @return boolean true or false if fail
     */
    public function createUserIfNotExists($openid)
    {
        $sql=$this->commonModel->getSql();
        if (empty($this->fetchUserByOpenid($openid))) {
            $insert = $sql->insert($this->table_users);
            $values = [
                'openid' => $openid,
                'amount' => 0.00,
                'status' => self::USER_STATUS_ENABLE,
                'created' => date('Y-m-d H:i:s')
            ];
            
            //通过有赞获取用户微信头像
            $user = $this->youzan->fetchUserInfoByOpenid($openid);
            if (! empty($user)) {
                $values['nick']     = $user['nick'];
                $values['sex']      = empty($user['sex']) ? '-' : $user['sex'];
                $values['avatar']   = $user['avatar'];
            }
            
            $insert->values($values);
            $user_id = $this->commonModel->insertItem($insert);
        }
        $this->card->createCardIfNotExists($openid);
    }

    /**
     * get one user infomation by openid
     *
     * @param string $openid
     * @return array user information or []
     */
    public function fetchUserByOpenid($openid)
    {
        $select = $this->commonModel->getSql()->select($this->table_users);
        $select->where(['openid' => $openid])->limit(1);
        $user = $this->commonModel->fetchOne($select);
        return empty($user) ? [] : $user;
    }
    
    /**
    * get user's amount of the acount
    * 
    * @param  void
    * @return Decimal       
    */
    public function getAmountOfAcount($openid)
    {
        $user   =$this->fetchUserByOpenid($openid);
        return empty($user) ? 0 : $user['amount'];
    }
    
    /**
     * update user infomation
     *
     * @param array $data            
     * @param string $openid            
     * @return true or false
     */
    public function updateUser(array $data, $openid)
    {
        $update = $this->commonModel->getSql()->update($this->table_users);
        unset($data['openid']);
        $update->where([
            'openid' => $openid
        ])->set($data);
        
        return $this->commonModel->updateOrDeleteItem($update);
    }
    
    /**
    * increase user's comsume points 
    * 
    * @param int $points 
    * @param string $reason 
    * @return boolean       
    */
    public function increasePointsOfYouzan($points, $reason, $openid=null)
    {
        $openid=empty($openid) ? $this->sessionContainer->openid : $openid;
        $res    =$this->youzan->increasePointByOpenid($openid, $points, $reason);
        
        if (empty($res))
        {
            throw new \Exception('INCREASE POINTS OF YOUZAN FAILED');
        }
        return  $res;
    }
}

