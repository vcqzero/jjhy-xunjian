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
use Application\Service\MyTokenManager;
use Application\Service\CardManager;

class CardController extends AbstractActionController
{

    protected $dbAdapter        = null;
    protected $mySession        = null;

    protected $myTokenManager   = null;
    protected $card             = null;
    protected $suffix           ='CARD';

    public function __construct($dbAdapter, $mySession, MyTokenManager $myTokenManager, CardManager $card)
    {
        $this->dbAdapter        = $dbAdapter;
        $this->mySession        = $mySession;
        $this->myTokenManager   = $myTokenManager;
        $this->card             = $card;
    }

    // render the cardui
    public function indexAction()
    {
        return new ViewModel([
            'item'  => $this->card->fetchCardByOpenid($this->mySession->openid),
            'token' => $this->myTokenManager->setMyToken($this->suffix)
        ]);
    }
    
    // edit first
    public function editFirstAction()
    {
        return new ViewModel([
            'card'  => $this->card->fetchCardByOpenid($this->mySession->openid),
            'token' => $this->myTokenManager->setMyToken($this->suffix)
        ]);
    }
    //edit second
    public function editSecondAction()
    {
        $token  = $this->params()->fromPost('token');
        if (! $this->myTokenManager->isValidate($token, $this->suffix) ) {
            return $this->redirect()->toRoute('card', ['action'=>'error']);
        } 
        
        //判断是否更改银行卡，如果没有更改银行卡不需要在验证了
        //主要判断银行卡号是否变化
        $card_no        =$this->params()->fromPost('card_no', 0);
        $card_no_old    =$this->params()->fromPost('card_no_old', 0);
        
        //如果两次输入的卡号一致则，不需要重新获取开户卡等信息，直接从数据库读取
        if ($card_no == $card_no_old)
        {
            $card=$this->card->fetchCardByOpenid($this->mySession->openid);
            $card['card_user_name']=$this->params()->fromPost('card_user_name');
            $card['card_user_tel']=$this->params()->fromPost('card_user_tel');
            return new ViewModel([
                'card'  => $card,
                'token' => $this->myTokenManager->setMyToken($this->suffix)
            ]);
        }
        
        //重新验证银行卡开户行等信息
        $card=$this->card->validateBankCard($card_no);
        if (empty($card))
        {
            //查询失败或非借记卡即返回error
            return $this->redirect()->toRoute('card', ['action'=>'error']);
        }
        //验证成功或者没有出错，因为在一些情况下会出错
        $data=$this->params()->fromPost();
        $card=array_merge($data, $card);
        return new ViewModel([
            'card'  => $card,
            'token' => $this->myTokenManager->setMyToken($this->suffix)
        ]);
    }
    
    public function errorAction()
    {
        return new ViewModel();
    }
    
    public function saveAction()
    {
        $token = $this->params()->fromPost('token');
        if (! $this->myTokenManager->isValidate($token, $this->suffix) )
        {
            echo false;
            exit();
        } else
        {
            $data   = $this->params()->fromPost();
            $id     =$data['id'];
            unset($data['card_no_old']);
            unset($data['token']);
            unset($data['id']);
            $res = $this->card->updateCardById($data, $id);
        }
        echo $res;
        exit();
    }
}
