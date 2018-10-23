<?php
/**
* @类简述: 用户前端helper
* 获取用户可体现金额
* @debug:
*/
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Service\UserManager;

/**
 * This view helper is used in item list
 */
class UserHelper extends AbstractHelper
{
    private  $mySession     =null; 
    private  $user          =null;
    
    public function __construct($mySession, UserManager $user)
    {
        $this->mySession    =$mySession;
        $this->user         =$user;
    }
    
    /**
    * 获取用户账户可提现账户余额
    * 
    * @param  void
    * @return float $amount       
    */
    public function getAmountOfUser()
    {
        $openid=$this->mySession->openid;
        return $this->user->getAmountOfAcount($openid);
    }
}


