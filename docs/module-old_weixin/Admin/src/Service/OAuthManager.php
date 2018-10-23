<?php
/**
 * @insruction:get the user openid and other information 
 * then do something
 *
 * @fileName  :OAuthManager.php
 * @author: 秦崇
 */
namespace Admin\Service;

use Zend\Config\Config;
use Admin\Service\CurlManager;
use Zend\Log\Logger;

class OAuthManager
{

    protected $curlManager  = null;
    protected $logger       = null;
    protected $configFile   = 'module/Admin/config/token.config.php';
    protected $appid='';
    protected $appsecrt='';

    public function __construct(Logger $logger)
    {
        $this->curlManager  = new CurlManager();
        $this->logger       = $logger;
        $this->readConfig();
    }
    
    private function readConfig()
    {
        $config         =new Config(include $this->configFile);
        $this->appid    =$config->weixin->appid;
        $this->appsecrt =$config->weixin->appsecrt;
    }
    /**
     * get the access_token using code
     *
     * @param string $code            
     * @return string $openid
     *        
     *         { "access_token":"ACCESS_TOKEN",
     *        
     *         "expires_in":7200,
     *        
     *         "refresh_token":"REFRESH_TOKEN",
     *        
     *         "openid":"OPENID",
     *        
     *         "scope":"SCOPE" }
     */
    public function getOpenId($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        
        $data = [
            'appid'     => $this->appid,
            'secret'    => $this->appsecrt,
            'code'      => $code,
            'grant_type' => 'authorization_code'
        ];
        $res = $this->curlManager->get($url, $data);
        if (!empty($res['errcode']))
        {
            $this->logger->log(Logger::DEBUG, $res['errcode'] . 'MSG=' . $res['errmsg']);
            throw new \Exception('系统故障，请稍后访问！');
        }
        return $res['openid'];
    }

    /**
     *
     * @param            
     * @return
     */
    public function getUrlForOAuth($state)
    {
        $data = [
            'appid'         => $this->appid,
            'redirect_uri'  => urlencode("http://weishop.tanhansi.com/admin/oauth"),
            'response_type' => 'code',
            'scope' => 'snsapi_base',
            'state' => $state,
        ];
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize';
        
        $url = $this->curlManager->splitUrl($url, $data);
        $url = $url . '#wechat_redirect';
        
        return $url;
    }
}

