<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Application\Model\CommonModel;

/**
 * This view helper is used in item list
 */
class PointHelper extends AbstractHelper
{

    protected $CommonModel  = null;
    protected $table_record_acquire = 'record_acquire';

    public function __construct(CommonModel $CommonModel)
    {
        $this->CommonModel=$CommonModel;
    }

    /**
     * get the points can be getted each time
     *
     * @param array $item
     * @return array [
     'all'       => $points_all_each_time,
     'cash'      => $points_cash_each_time,
     'consume'   => $points_consume_each_time,
     ];
     */
    public function queryPointsOfItem($item)
    {
        //首先获取该积分订单，已返积分情况
        $points=$this->queryPointsHadReturened($item['id']);
        
        //实际支付金额=已支付金额-已退款金额
        $payment     =$item['payment'] - $item['refunded_fee'];
        $point_all   =ceil($payment);
        $points['payment']      =$payment;
        $points['points_all']   =$point_all;
        return $points;
    }
    
    private function queryPointsHadReturened($point_trade_id)
    {
        $select=new Select($this->table_record_acquire);
        $select->where->equalTo('point_traders_id', $point_trade_id);
        $select->columns([
            'total_cash'=>new Expression("SUM(points_cash)"),
            'total_consume'=>new Expression("SUM(points_consume)"),
        ]);
        
        $res=$this->CommonModel->fethchAll($select);
        $total_cash     =empty($res) ? 0 :$res[0]['total_cash'];
        $total_consume  =empty($res) ? 0 :$res[0]['total_consume'];
        return [
            'all'=>$total_cash + $total_consume,
            'cash'=>$total_cash,
            'consume'=>$total_consume,
        ];
    }

    /**
     * get action
     *
     * @param            
     * @return
     */
    public function getStatusOfButton($item)
    {
        $created=strtotime($item['created']);
        $end_date=$item['end_date'];
        //获取本月月初时间
        $this_month_start       =mktime(0, 0, 0, date('m'), 1, date('Y'));
        if ($this_month_start <= $created)
        {
            $button ="<a class='weui-form-preview__btn weui-form-preview__btn_default' href='javascript:'>次月开始返积分</a>";
        }else 
        {
            $button ="<a class='weui-form-preview__btn weui-form-preview__btn_default' href='javascript:'>月初自动返积分</a>";
//             $button ="<a class='weui-form-preview__btn weui-form-preview__btn_primary btn_acquire' href='javascript:'>返积分记录</a>";
        }
        
        return $button;
    }
    
    

    public function getStatusOfRemit($status)
    {
        switch ($status) {
            case $this->configWithdraw->status->success:
                $mess = "<i class='weui-icon-success-no-circle'></i>提现成功";
                break;
            case $this->configWithdraw->status->progress:
                $mess = "<i class='weui-icon-info-circle'></i>待打款";
                break;
            case $this->configWithdraw->status->fail:
            default:
                $mess = "<i class='weui-icon-warn'></i>提现失败";
                break;
        }
        return $mess;
    }
}


