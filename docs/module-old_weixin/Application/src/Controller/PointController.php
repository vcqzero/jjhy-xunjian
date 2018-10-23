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
use Application\Service\PointManager;

class PointController extends AbstractActionController
{
    protected $point    = null;
    protected $mySession= null;

    public function __construct(PointManager $point, $mySession)
    {
        $this->point     = $point;
        $this->mySession = $mySession;
    }

    public function indexAction()
    {
        $openid=$this->mySession->openid;
        $paginator = $this->point->getPaginator($openid, 1);
        return new ViewModel([
            'paginator' => $paginator
        ]);
    }

    // responce the ajax
    public function paginatorAction()
    {
        $page   = $this->params()->fromRoute('page', 1);
        $openid =$this->mySession->openid;
        $paginator = $this->point->getPaginator($openid, $page);
        $this->layout('layout/blank.phtml');
        return new ViewModel([
            'paginator' => $paginator,
        ]);
    }
}
