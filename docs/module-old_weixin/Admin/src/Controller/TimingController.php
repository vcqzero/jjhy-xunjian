<?php
/**
* @类简述: 定时任务管理
* 1、每月初统计分销员上月业绩
* 2、每月初发送积分给用户
* @debug:
*/
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Service\PointManager;
use Admin\Service\CommissionManager;

class TimingController extends AbstractActionController
{
    protected $point        = null;
    protected $commission   = null;
    
    public function __construct(PointManager $point, CommissionManager $commission)
    {
        $this->point=$point;
        $this->commission=$commission;
    }

    public function indexAction()
    {
        exit('未选择action');
    }
    
    //月初积分赠送
    public function pointAction()
    {
        $token=$this->params()->fromQuery('token');
        if ($token != 'qinchong')
        {
            exit();
        }
        $this->point->returnPointPerMonth();
        exit('月初：积分赠送成功');
    }
    
    //计算截止到上个月销售员佣金情况
    public function commissionAction()
    {
        $token=$this->params()->fromQuery('token');
        if ($token != 'qinchong')
        {
            exit();
        }
        $this->commission->calucateCommissioPerMonth();
        exit('月初：分销员奖金计算成功');
    }
    
}
