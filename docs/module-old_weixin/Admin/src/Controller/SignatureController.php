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
use Admin\Service\SignatureManager;

class SignatureController extends AbstractActionController
{
    protected $signature    =null;

    public function __construct( SignatureManager $signature)
    {
        $this->signature=$signature;
    }

    public function indexAction()
    {
        $url    =$this->params()->fromPost('url');
        $sign   =$this->signature->getSignatureArray($url);
        echo json_encode($sign);
        exit();
    }
}
