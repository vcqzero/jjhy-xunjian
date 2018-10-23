<?php
/**
 * @文件名称：IndexController.php
 * @编写时间: 2017年10月19日
 * @作者: 秦崇
 * @版本:
 * @说明: 接收来自youzan的消息推送，首先需要验证消息是否合法即是否来自有赞
 * @业务：1、销售员缴费自动成为销售员
 *      2、积分返现订单处理
 *       
 */
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Service\PushManager;
use Admin\Service\TradePushManager;

class PushController extends AbstractActionController
{
    protected $push = null;
    protected $trade= null;

    public function __construct(PushManager $push, TradePushManager $trade)
    {
        $this->push = $push;
        $this->trade= $trade;
    }

    public function indexAction()
    {
        //当有消息推送时，就返回正确信息，不做任何判断
        $json = file_get_contents('php://input');
        $json = file_get_contents('data/log/json_test.log');
        if (!empty($json))
        {
            //首先回复有赞服务器 代表已收到信息
            $this->push->responceSuccess();
        }
        $data = json_decode($json, true);
        //判断是否有赞测试消息
        if (!empty($data['test']))
        {
            exit('测试内容，不进行任何处理');
        }
        
        //验证是否来自有赞的推送，如果是，则进行业务处理
        if (!$this->push->isValid($data))
        {
            //并非来自有赞的消息推送，不进行业务处理
            exit('仅接受来自有赞推送');
        }
        //验证通过，进行业务处理
        //获取推送消息类型
        $this->push->type   = $data['type'];
        $this->push->msg    = json_decode(urldecode($data['msg']), true);
        
        $this->push->attach($this->trade);
        $this->push->notify();
        exit();
    }
}
