<?php
/**
* @类简述:管理用户积分
* 主要实现按积分订单中的订单将积分返给用户
* @debug:
*/
namespace Admin\Service;

use Zend\Db\Sql\Sql;
use Application\Model\CommonModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Application\Service\UserManager;
use Zend\Log\Logger;

class PointManager
{
    protected $CommonModel      = null;
    protected $user             = null;
    protected $reason_increas_points_youzan ='消费返积分';
    protected $table_point_traders  = 'point_traders';//积分订单表
    protected $table_record_acquire = 'record_acquire';//积分赠送记录表

    protected $logger    = null;

    public function __construct(CommonModel $CommonModel, UserManager $user, Logger $logger)
    {
        $this->CommonModel      = $CommonModel;
        $this->user             = $user;
        $this->logger           = $logger;
    }
    
    public function returnPointPerMonth()
    {
        //获取积分订单数据
        $trades=$this->fetchPointTrades();
        
        if (empty($trades))
        {
            return ;
        }
        //循环处理每个积分订单
        //获取订单
        foreach ($trades as $key=>$trade)
        {
            //获取该月应该获取的积分数量
            $points_this_month=$this->calucatePointsOfThisMonth($trade);
            //将该月应获取的积分数保存到数据库中
            if ($points_this_month > 0)
            {
                $this->savePoint($trade, $points_this_month);
            }
        } 
    }
    
    /**
     * acquire the points
     *
     * @param array $data
     * @return success true or false
     */
    private function savePoint(array $trade, $points_this_month)
    {
        $percent=$trade['percent'];
        $points_cash=ceil($points_this_month * $percent);
        $points_consume=$points_this_month - $points_cash;
        $openid=$trade['openid'];
        
        $values=[
            'openid'    =>$openid,
            'created'   =>time(),
            'point_traders_id'=>$trade['id'],
            'points_cash'=>$points_cash,
            'points_consume'=>$points_consume,
        ];
        $insert=new Insert($this->table_record_acquire);
        $insert->values($values);
        
        // beginTransaction
        $beginTransaction = $this->CommonModel->getDbAdapter()
        ->getDriver()
        ->getConnection()
        ->beginTransaction();
        try {
            //insert recore_point table
            $insert_recore_id=$this->CommonModel->insertItem($insert);
            if (empty($insert_recore_id))
            {
                throw new \Exception('插入积分获取记录出错');
            }
            
            //add cash amount into user's acount
            $data=[
                'amount'=>new \Zend\Db\Sql\Predicate\Expression("amount + $points_cash"),
            ];   
            $update_id = $this->user->updateUser($data, $openid);
            
            if (empty($update_id))
            {
                throw new \Exception('更新用户账户出错');
            }
            
            //add points_consume into user's youzan's point acount
            $reason         =$this->reason_increas_points_youzan;
            $this->user->increasePointsOfYouzan($points_consume, $reason, $openid);
            
            //commit
            $beginTransaction->commit();
            $flag = true;
            
        } catch (\Exception $e) 
        {
            $beginTransaction->rollback();
            $flag   = false;
            $this   ->logger->log(Logger::DEBUG, $e->getMessage());
        }
        return $flag;
    }
    
    /**
    * 计算本月应返积分总额
    * 
    * @param  
    * @return        
    */
    private function calucatePointsOfThisMonth($trade)
    {
        /**
        * 思路：
        * 查询本订单，本月已返积分数量
        * 计算剩余应返积分数量
        */
        //首先查询该订单本月已返积分情况
        $point_traders_id   =$trade['id'];
        $this_month_start   =mktime(0, 0, 0, date('m'), 1, date('Y'));
        $this_month_end     =mktime(59, 59, 59, date('m'), date('t'), date('Y'));
        
        $select =new Select($this->table_record_acquire);
        $select ->where->equalTo('point_traders_id', $point_traders_id)
                ->where->between('created', $this_month_start, $this_month_end);
        $select ->columns([
                'id',
                'points_cash'=>new Expression("SUM(points_cash)"),
                'points_consume'=>new Expression("SUM(points_consume)"),
        ]);
        
        $res=$this->CommonModel->fethchAll($select);
        $point_returned=empty($res) ? 0 : $res[0]['points_cash'] + $res[0]['points_consume'];
        
        //获取该订单本月还需返积分数量
        $payment_all=$trade['payment'] - $trade['refunded_fee'];
        $months_all =$trade['months_total'];
        $points_all_each_month =ceil($payment_all / $months_all);
        return $points_all_each_month - $point_returned;
    }
    
    /**
    * 查询出可领取的积分订单，即下单次月和未到结束时间
    * 
    * @param  void
    * @return array or [] if no data        
    */
    private function fetchPointTrades()
    {
        $select=new Select($this->table_point_traders);
        //查询条件：该积分订单还处于返现期
        //积分订单数据表中字段中end_time 代表领取到哪个月结束（含当月）
        //根据end_time字段查询出当前时间在领取时间内的订单数据，
        //根据create字段查询出当前时间至少是在下单的次月
        
        //获取本月月初时间
        $this_month_start       =mktime(0, 0, 0, date('m'), 1, date('Y'));
        //获取本月月初时间 格式化时间
        $this_month_start_date  =date('Y-m-d H-s-i', $this_month_start);
        
        $select ->where
                ->lessThan('created', $this_month_start_date)
                ->greaterThanOrEqualTo('end_date', $this_month_start);
        $res    =$this->CommonModel->fethchAll($select);
        return  $res;            
        //------debug ok until here------
    }
}

