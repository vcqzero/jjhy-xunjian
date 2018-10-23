<?php
/**
 * 监听订单签收：如果是积分订单则保存到数据库中
 * 监听退款：不判断订单是否是积分订单，直接根据订单tid和item_id更新订单信息，这样即使不是积分订单也不会有问题
 */
namespace Admin\Service;

use Application\Model\CommonModel;
use Zend\Config\Config;
use Admin\Api\YouzanApi;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Predicate\Expression;

class TradePushManager implements \SplObserver
{
    protected $CommonModel  = null;
    protected $youzan       =null;
    protected $table_point_traders  = 'point_traders';
    protected $trade_config_filename= 'module/Admin/config/trade.config.php';
    
    //正常订单 状态
    const TRADE_SUCCESS     ='TRADE_SUCCESS';
    const FULL_REFUND       ='FULL_REFUND';
    
    //退款订单 状态
    const REFUND_STATUS_SUCCESS   ='SUCCESS';
    
    public function __construct(CommonModel $CommonModel, YouzanApi $youzan)
    {
        $this->CommonModel      =$CommonModel;
        $this->youzan           =$youzan;
    }
    
    /**
     * {@inheritDoc}
     * @see SplObserver::update()
     */
    public function update(\SplSubject $push)
    {
        //普通订单信息推送
        if ($push->type === $push::TRADE_ORDER_STATE)
        {
            $msg=$push->msg;
            $tid=$msg['tid'];
            $this->handleTradePush($tid);
        }
        //退款订单
        if($push->type === $push::TRADE_ORDER_REFUND)
        {
            $refund_id=$push->msg['refund_id'];
            $this->handleRefundPush($refund_id);
        }
    }
    
    /**
    * 处理退款订单
    * 如果退款成功则，更新
    * 
    * @param  string $refund_id 退款id
    * @return        
    */
    private function handleRefundPush($refund_id)
    {
        //获取退款订单信息
        $refund=$this->youzan->fetchRefundByRefundid($refund_id);
        //验证退款状态
        if (empty($refund))
        {
            return ;
        }
        if ($refund['status'] !== self::REFUND_STATUS_SUCCESS)
        {
            return ;
        }
        //获取退款金额和要退款的订单，然后更新订单
        $item_id    =$refund['item_id'];
        $tid        =$refund['tid'];
        $refund_fee =$refund['refund_fee'];
        $this->updateOrderOnRefund($item_id, $tid, $refund_fee);
    }
    
    /**
    * 处理积分订单消息推送
    * 
    * @param  string $tid 订单编号
    * @return        
    */
    private function handleTradePush($tid)
    {
        //验证积分订单活动是否仍在进行
        $config     = new Config(include $this->trade_config_filename);
        $config     = $config->toArray();
        if (empty($this->isValidOnPoint($config)))
        {
            return false;
        }
        
        //验证订单是否合法
        $trade=$this->youzan->fetchTradeByTid($tid);
        if (empty($this->isValidOnTrade($trade)))
        {
            return ;
        }
        
        //验证订单中的order是否合法，如合法则存入数据库
        $orders=$trade['orders'];
        foreach ($orders as $order)
        {
            if (empty($this->isValidOnOrder($order, $config)))
            {
                break;
            }
            $this->saveOrder($order, $trade, $config);
        }
    }
    
    /**
    * 验证该活动是否正在进行
    * 
    * @param  
    * @return boolean true or false       
    */
    private function isValidOnPoint($config)
    {
        if (empty($config['types']['point']))
        {
            return false;
        }
        $point=$config['types']['point'];
        //从时间上判断
        $date=$point['date'];
        if (time() > $date['end'])
        {
            return false;
        }
        if (time() < $date['start'])
        {
            return false;
        }
        return true;
    }
    
    /**
    * 验证订单是否合法
    * 
    * @param  
    * @return        
    */
    private function isValidOnTrade(array $trade)
    {
        if (empty($trade))
        {
            return false;
        }
        //判断订单是否已全额退款
        if ($trade['refund_state'] === self::FULL_REFUND)
        {
            return false;
        }
        //判断是否是积分订单
        if (!empty($trade['points_price']))
        {
            return false;
        }
        return true;
    }
    /**
    * 验证item是否是积分订单
    * 
    * @param  array $order
    * @return        
    */
    private function isValidOnOrder(array $order, $config)
    {
        $item_refund_state=$order['item_refund_state'];
        //如果已经全额退款，则不保存
        if ($item_refund_state === self::FULL_REFUND)
        {
            return false;
        }
        //验证商品itemid
        $item_id    = $order['item_id'];
        $nums       = $order['num'];
        $payment    = $order['payment'];
        $price      = $payment / $nums;
        
        if (empty($config['trade_type']['point']))
        {
            return false;
        }
        $points=$config['trade_type']['point'];
        $flag=false;
        //从配置文件中读取订单point数据，并验证该订单item
        foreach ($points as $point)
        {
            //先判断该item是否合法
            if ($point['item_id'] == $item_id)
            {
                $flag=true;
                $the_lowest_payment=$point['the_lowest_payment'];
                break;
            }
        }
        
        if (empty($flag) || !isset($the_lowest_payment))
        {
            return false;
        }
        if ($price <= $the_lowest_payment)
        {
            return false;
        }
        return true;
    }
    
    /**
     * store the order into database
     *
     * @param
     * @return
     */
    private function saveOrder(array $order, array $trade, $config)
    {
        $config_point   = $config['types']['point']['back'];
        $percent_cash   = $config_point['percent_cash'];
        $months         = $config_point['months'];
        
        if ($percent_cash < 0 || $percent_cash > 1 || $months <= 0)
        {
            return;
        }
        
        $payment    = $order['payment'];
        $item_id    = $order['item_id'];
        $trade_id   = $trade['tid'];
        $created    = $trade['created'];
        $openid     = $trade['fans_info']['fans_weixin_openid'];
        
        $months     +=1;
        $end_daye   = strtotime("+$months months", strtotime($created));
        $data = [
            'openid'        => $openid,
            'item_id'       => $item_id,
            'trade_id'      => $trade_id,
            'created'       => $created,
            'status'        => $trade['status'],
            'title'         => $order['title'],
            'payment'       => $payment,
            'refunded_fee'  => $order['refunded_fee'],
            'percent'       => $percent_cash,
            'num'           => $order['num'],
            'months_total'      => $months,
            'end_date'          => $end_daye,
        ];
        
        $insert = new Insert($this->table_point_traders);
        $insert ->values($data);
        $res    = $this->CommonModel->insertItem($insert);
    }
    /**
    * 更新订单退款信息
    * 
    * @param  
    * @return        
    */
    private function updateOrderOnRefund($item_id, $tid, $refund_fee)
    {
        $update=new Update($this->table_point_traders);
        $update->where([
            'item_id' => $item_id,
            'trade_id' => $tid,
        ]);
        $update->set([
            'refunded_fee'=>new Expression("refunded_fee + $refund_fee"),
        ]);
        $this->CommonModel->updateOrDeleteItem($update);
    }
}

