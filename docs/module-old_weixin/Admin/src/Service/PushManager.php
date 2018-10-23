<?php
namespace Admin\Service;

use Zend\Config\Config;
use Zend\Config\Reader\Json;
use Zend\Log\Logger;

class PushManager implements \SplSubject
{
    protected $configToken      = null;
    protected $configTokenFile  = 'module/Admin/config/token.config.php';
    protected $logger           = null;
    public $type='';
    public $msg ='';
    public $observers   =null;
    
    //交易类型 type
    const TRADE_ORDER_STATE     ='TRADE_ORDER_STATE';//订单状态变更消息推送
    const TRADE_ORDER_REFUND    ='TRADE_ORDER_REFUND';//订单退款消息推送
    const POINTS                ='POINTS';//积分消息信息推送
    
    public function __construct(Logger $logger)
    {
        $this->configToken  = new Config(include $this->configTokenFile);
        $this->logger       = $logger;
        $this->observers    = new \SplObjectStorage();
    }
    
    /**
     *
     * @param
     * @return
     */
    public function responceSuccess()
    {
        $result = array(
            "code" => 0,
            "msg" => "success"
        );
        var_dump($result);
        return;
    }
    /**
    * check the push whehter come from youzan  
    * 
    * @param  json $json
    * @return boolean or false is not validate       
    */
    public function isValid(array $data)
    {
        $client_id      = $this->configToken->youzan->client_id; // 应用的 client_id
        $client_secret  = $this->configToken->youzan->client_secret; // 应用的 client_secret
        
        //判断消息是否合法，若合法则返回成功标识
        $msg            = $data['msg'];
        $sign_string    = $client_id . $msg . $client_secret;
        $sign           = md5($sign_string);
        $isValid        = $sign != $data['sign'] ? false :true;
        
        if (!$isValid)
        {
            $mess='youzan PUSH 检测失败，请检查下一条记录，确定错误原因';
            $this->logger->log(Logger::DEBUG, $mess);
            $this->logger->log(Logger::DEBUG, $sign_string);
        }
        
        return $isValid;
    }
    
    /**
     * store data
     *
     * @param  $data
     * @return void
     */
    protected function storeJson($data)
    {
        $path = "data/log/json.log";
        
        $mess = '-------------json--------------------';
        file_put_contents($path, $mess . "\r\n", FILE_APPEND);
        
        foreach ($data as $key => $val) 
        {
            $mess = $key . '=>' . $val;
            file_put_contents($path, $mess . "\r\n", FILE_APPEND);
        }
        $mess = '-------------mess--------------------';
        file_put_contents($path, $mess . "\r\n", FILE_APPEND);
        $msg=json_decode(urldecode($data['msg']), true);
        foreach ($msg as $key => $val)
        {
            $mess = $key . '=>' . $val;
            file_put_contents($path, $mess . "\r\n", FILE_APPEND);
        }
    }
    
    /**
     * {@inheritDoc}
     * @see SplSubject::attach()
     */
    public function attach(\SplObserver $observer)
    {
        // TODO Auto-generated method stub
        $this->observers->attach($observer);
    }

    /**
     * {@inheritDoc}
     * @see SplSubject::detach()
     */
    public function detach(\SplObserver $observer)
    {
        // TODO Auto-generated method stub
        $this->observers->detach($observer);
    }

    /**
     * {@inheritDoc}
     * @see SplSubject::notify()
     */
    public function notify()
    {
        // TODO Auto-generated method stub
        $this->observers->rewind();
        while ($this->observers->valid())
        {
            $observer=$this->observers->current();
            $observer->update($this);
            $this->observers->next();
        }
    }

}

