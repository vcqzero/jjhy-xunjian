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
use Application\Service\RecordWithdrawManager;

class RecordWithdrawController extends AbstractActionController
{
    protected $record     = null;
    protected $mySession  = null;
    protected $suffix     ='WITHDRAW';
    
    public function __construct(RecordWithdrawManager $record, $mySession)
    {
        $this->record   = $record;
        $this->mySession= $mySession;
    }

    // render the withdraw page
    public function indexAction()
    {
        $pagintor = $this->record->getPaginator(1, $this->mySession->openid);
        return new ViewModel([
            'paginator' => $pagintor
        ]);
    }

    public function paginatorAction()
    {
        $this->layout('layout/blank.phtml');
        $page = $this->params()->fromRoute('page', 1);
        $pagintor = $this->record->getPaginator($page, $this->mySession->openid);
        return new ViewModel([
            'paginator' => $pagintor
        ]);
    }
}
