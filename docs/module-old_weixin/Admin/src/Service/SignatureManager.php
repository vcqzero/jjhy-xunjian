<?php
/**
* @insruction：调用微信js签名管理
*
* @fileName  :SignatureManager.php
* @author: 秦崇
*/
namespace Admin\Service;

use Zend\Config\Config;
use Admin\Service\CurlManager;
use Admin\Service\TokenManager;
use Zend\Config\Writer\PhpArray;
use Zend\Log\Logger;

class SignatureManager
{
    protected static $_instanse =null;
    protected $signature_config_filename='module/Admin/config/signature.config.php';
    protected $token_config_filename    ='module/Admin/config/token.config.php';
    protected $signature_config_data    ='';
    protected $log      ='';

    private final function __construct(Logger $logger)
    {
        $this->log=$logger;
        $this->signature_config_data=new Config(include $this->signature_config_filename, true);
        //在用到该类时首先更新ticket
        $this->updateTicket();
    }
    private function __clone(){}
    
    public static function getInstanse($logger)
    {
        return empty(self::$_instanse) ? new self($logger) : self::$_instanse;
    }
    
    /**
    * 更新ticket if enable
    * 
    * @param  
    * @return        
    */
    protected function updateTicket()
    {
        if (!$this->isOverTime())
        {
            //don't need update
            return ;
        }
        $response=$this->fetchTheNewestTicket();
        if (empty($response))
        {
            $this->log->log(Logger::DEBUG, '未读取到ticket');
            return false;
        }
        $this->storeIntoConfig($response);
    }
    /**
    * is over time 
    * 
    * @param  void
    * @return boolean true or false if over time       
    */
    private function isOverTime()
    {
        $update_time=$this->signature_config_data->update_time;
        $expires_in =$this->signature_config_data->expires_in;
        $period     =time() - $update_time;
        return $period >= $expires_in ? true : false;
    }
    /**
    * fetch the newest ticket from weixin
    * 
    * @param  void
    * @return array $response       
    */
    private function fetchTheNewestTicket()
    {
        $curl           =new CurlManager();
        $tokenManager   =TokenManager::getInstace();    
        $access_token   =$tokenManager->getToken('weixin');
        $url            =$this->signature_config_data->url;
        $data           =[
            'access_token'=>$access_token,
            'type'        =>'jsapi',
        ];
        $response       =$curl->get($url, $data);
        return $response;
    }
    /**
    * 将获取到的 ticket 保存到配置文件中
    * 
    * @param  array $response
    * @return void       
    */
    private function storeIntoConfig($response)
    {
        $writer=new PhpArray();
        foreach ($response as $key=>$val)
        {
            $this->signature_config_data->$key=$val;
        }
        $this->signature_config_data->update_time=time();
        $writer->toFile($this->signature_config_filename, $this->signature_config_data);
    }
    
    /**
    * 获取ticket from weixin
    * 
    * @param  void
    * @return string $ticket       
    */
    protected function getTicket()
    {
        return $this->signature_config_data->ticket;
    }
    /**
    * 根据获取到的ticket，获取signature
    * 
    * @param  
    * @return  array $signature      
    */
    public function getSignatureArray($url)
    {
        $noncestr       ='tanhansiweixinqianming';
        $jsapi_ticket   =$this->getTicket();
        $timestamp      =time();
        
        $data=[
            'jsapi_ticket'  =>$jsapi_ticket,
            'noncestr'      =>$noncestr,
            'timestamp'     =>$timestamp,
            'url'           =>$url,
        ];
        $data_string=[];
        foreach ($data as $key=>$val)
        {
            $data_string[]=$key . '=' . $val;
        }
        
        $signature          = sha1(implode('&', $data_string));
        $data['signature']  =$signature;
        $token_config_data  =new Config(include $this->token_config_filename);
        $data['appid']      =$token_config_data->weixin->appid;
        return $data;
    }
}

