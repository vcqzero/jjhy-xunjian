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
use Application\Service\SellerManager;
use Application\Service\MyTokenManager;

class SellerController extends AbstractActionController
{

    protected $mySession    = null;
    protected $seller       = null;
    protected $myToken      = null;

    public function __construct(SellerManager $seller, MyTokenManager $myTokenManager, $mySession)
    {
        $this->mySession = $mySession;
        $this->seller    = $seller;
        $this->myToken   = $myTokenManager;
    }

    public function indexAction()
    {
        // $this->layout('layout/blank.phtml');
        //先判断是否是有赞用户，如果不是则进行注册提示
        
        return new ViewModel([
            'isSeller'  => $this->seller->isSellerByOpenid($this->mySession->openid),
            'isFree'    => $this->seller->isFree(),
            'config'    => $this->seller->getArrayConfig(),
        ]);
    }
    
    //pay and become a seller
    public function payAction()
    {
        $url=$this->seller->getUrlToPay();
        return $this->redirect()->toUrl($url);
    }
    //pay nothing and become a seller
    public function freeAction()
    {
        $res    =$this->seller->changeUserToBeSellerForFree($this->mySession->openid);
        echo $res;
        exit();
    }
}
