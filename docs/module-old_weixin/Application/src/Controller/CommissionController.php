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
use Application\Service\CommissionManager;
use Application\Service\SellerManager;

class CommissionController extends AbstractActionController
{
    protected $commission   = null;
    protected $mySession    = null;
    protected $seller       = null;

    public function __construct(CommissionManager $commission, $mySession, SellerManager $seller)
    {
        $this->commission   = $commission;
        $this->mySession    = $mySession;
        $this->seller       = $seller;
    }
    
    public function indexAction()
    {
        $openid=$this->mySession->openid;
        //首先判断是否是销售员
        if (!$this->seller->isSellerByOpenid($openid))
        {
            return $this->redirect()->toRoute('seller');
        }
        
        $pagintor = $this->commission->fetchPaginator($openid);
        return new ViewModel([
            'paginator' => $pagintor,
            'seller_url'=> $this->commission->getYouzanSellerCenterUrl(),
        ]);
    }

    public function paginatorAction()
    {
        $openid=$this->mySession->openid;
        $this->layout('layout/blank.phtml');
        $page = $this->params()->fromRoute('page', 1);
        $pagintor = $this->commission->fetchPaginator($openid, $page);
        return new ViewModel([
            'paginator' => $pagintor,
        ]);
    }
}
