<?php
/**
* @类简述: 
* 判断token是否在有效期内，如果不在有效期内，则需要重新获取token，并存入文件中
* 以后获取token需要从文件中读取，不能再从本类获取
* 
* @debug:
*/
namespace Admin\Service;

use Admin\Service\CurlManager;
use Zend\Config\Config;
use Zend\Config\Writer\PhpArray;

class TokenManager
{
    private static  $_instance    =null;
    protected $curlManager  = null;
    protected $configFile   = 'module/Admin/config/token.config.php';
    protected $config       = null;

    final private function __construct()
    {
        $this->curlManager  = new CurlManager();
        $this->config       = new Config(include $this->configFile, true);
    }
    private function __clone()
    {
        //do nothing
        throw new \Exception("TokenManger can't been cloned");
    }
    
    static public function getInstace()
    {
        if (is_null(self::$_instance))
        {
            self::$_instance=new self();
        }
        return self::$_instance;
    }
    
    /**
    * 判断token是否在有效期，如则重新获取
    * 
    * @param  
    * @return        
    */
    public function updateToken($mark)
    {
        if (! $this->whetherInExpires($mark)) 
        {
            $res = $this->fetchToken($mark);
            $this->writeToken($res, $mark);
        }
    }

    /**
     * 判断token是否在有效期内
     *
     * @param            
     * @return boolean true or false if not in expires
     */
    private function whetherInExpires($mark)
    {
        $update_time    = $this->config->$mark->update_time;
        $update_time    = strtotime($update_time);
        
        $expires        = $this->config->$mark->expires_in;
        
        return (time() - $update_time) < $expires ? true : false;
    }
    
    /**
     * fetch the token array 
     *
     * @param            
     * @return array $res
     */
    protected function fetchToken($mark)
    {
        $url = $this->config->$mark->url;
        switch ($mark) 
        {
            case 'weixin':
                $data = [
                    'appid'     => $this->config->$mark->appid,
                    'secret'    => $this->config->$mark->appsecrt,
                    'grant_type'=> 'client_credential'
                ];
                $res = $this->curlManager->get($url, $data);
                break;
                
            case 'youzan':
            default:
                $data = [
                    'client_id'     => $this->config->$mark->appid,
                    'client_secret' => $this->config->$mark->appsecrt,
                    'grant_type'    => 'silent',
                    'kdt_id'        => $this->config->$mark->ktd_id
                ];
                $res = $this->curlManager->post($data, $url);
        }
        return $res;
    }

    /**
     * update token of database
     *
     * @param array $res            
     * @param string $mark            
     * @return void
     */
    protected function writeToken(array $res, $mark)
    {
        $writer = new PhpArray();
        foreach ($res as $key => $val) 
        {
            $this->config->$mark->$key = $val;
        }
        $this   ->config->$mark->update_time = date('Y-m-d H:i:s', time()-60);
        $writer ->toFile($this->configFile, $this->config);
    }
}

