<?php
/**
 * @文件名称：IndexController.php
 * @编写时间: 2017年10月19日
 * @作者: 秦崇
 * @版本:
 * @说明: 
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\WithdrawManager;
use Zend\Config\Config;
use Application\Service\MyTokenManager;
use Application\Service\CardManager;
use Zend\Log\Logger;

class WithdrawController extends AbstractActionController
{

    protected $mySession        = null;
    protected $myTokenManager   = null;
    protected $withdraw         = null;
    protected $user             = null;
    protected $config_withdraw  = null;
    protected $card             = null;
    protected $suffix           ='WITHDRAW';
    protected $logger           =null;
    
    

    public function __construct($mySession, 
                                WithdrawManager $withdraw, 
                                CardManager $card, 
                                MyTokenManager $myToken)
    {
        $this->mySession    = $mySession;
        $this->withdraw     = $withdraw;
        $this->card         = $card;
        $this->myTokenManager= $myToken;
        
    }

    // render the withdraw page
    public function indexAction()
    {
        $openid = $this->mySession->openid;
        $card   = $this->card->fetchCardByOpenid($openid);
        $config = $this->withdraw->getwithdrawConfigData();
        $button = $this->withdraw->renderButtonOfWithdraw($openid);
        $token  = $this->myTokenManager->setMyToken($this->suffix);
        return new ViewModel([
            'card'      => $card,
            'config'    => $config,
            'button'    => $button,
            'token'     => $token
        ]);
    }

    // save cash
    public function savewithdrawAction()
    {
        $token  = $this->params()->fromPost('token');
        
        if (! $this->myTokenManager->isValidate($token, $this->suffix)) 
        {
            echo false;
            exit();
        }
        $cashback   = $this->params()->fromPost('cashback', 0);
        $res        = $this->withdraw->saveWithDraw($cashback, $this->mySession->openid);
        echo $res;
        exit();
    }

    // show the list of history
    public function historyAction()
    {
        $pagintor = $this->withdraw->getPaginator(1);
        return new ViewModel([
            'paginator' => $pagintor
        ]);
    }

    public function paginatorAction()
    {
        $this->layout('layout/blank.phtml');
        $page = $this->params()->fromRoute('page', 1);
        $pagintor = $this->withdraw->getPaginator($page);
        return new ViewModel([
            'paginator' => $pagintor
        ]);
    }
}
