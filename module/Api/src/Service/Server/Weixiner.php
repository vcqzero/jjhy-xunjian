<?php
/**
* @类简述: 
* 判断token是否在有效期内，如果不在有效期内，则需要重新获取token，并存入文件中
* 以后获取token需要从文件中读取，不能再从本类获取
* 
* @debug:
*/
namespace Api\Service\Server;

use Zend\Cache\Storage\Adapter\Filesystem;

class Weixiner
{
    const CACHE_KEY_WEIXIN_TOKEN = 'weixin-token';
    const CACHE_KEY_WEIXIN_SIGNATURE = 'weixin-signature';
    const CACHE_KEY_WEIXIN_JSAPI_TICKET = 'weixin-jsapi-ticket';
    
    private $Cache;
    private $Curler;
    private $weixin_config;

    public function __construct(
        Filesystem $Cache,
        array $weixinTokenConfig= null, 
        Curler $Curler)
    {
        $this->Cache = $Cache;
        $this->weixin_config= $weixinTokenConfig;
        $this->Curler  = $Curler;
    }
    
    /**
     * fetch the access_token array 
     *
     * @param            
     * @return string $access_token
     */
    public function getAccessToken()
    {
        $Cache = $this->Cache;
        $weixin_config = $this->weixin_config;
        
        $expires_in = $weixin_config['expires_in'];
        $Cache->getOptions()->setTtl($expires_in - 20);
        
        $access_token = $Cache->getItem(self::CACHE_KEY_WEIXIN_TOKEN);
        if (empty($access_token))
        {
            //需要重新获取token
            $url = $weixin_config['url'];
            
            $data = [
                'appid'     => $weixin_config['appid'],
                'secret'    => $weixin_config['secret'],
                'grant_type'=> $weixin_config['grant_type'],
            ];
            
            $res = $this->Curler->get($url, $data);
            
            if (isset($res['errcode']))
            {
                $errcode = $res['errcode'];
                $errmsg  = $res['errmsg'];
                throw new \Exception("获取微信access_token 发生错误，错误代码为:$errcode, 错误信息：$errmsg");
            }
            $access_token = $res['access_token'];
            $Cache->setItem(self::CACHE_KEY_WEIXIN_TOKEN, $access_token);
        }
        return $access_token;
    }
    
    public function getSignature()
    {
        
    }
    
    /**
    * jsapi_ticket是公众号用于调用微信JS接口的临时票据。
    * 正常情况下，jsapi_ticket的有效期为7200秒，
    * 通过access_token来获取。
    * 
    * @param  
    * @return        
    */
    public function getJsapiTicket()
    {
        $Cache = $this->Cache;
        $weixin_config = $this->weixin_config;
        
        $expires_in = $weixin_config['expires_in'];
        $Cache->getOptions()->setTtl($expires_in - 20);
        
        $jsapi_ticket = $Cache->getItem(self::CACHE_KEY_WEIXIN_JSAPI_TICKET);
        if (empty($jsapi_ticket))
        {
            $access_token = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
            
            $res = $this->Curler->get($url);
            
            if (isset($res['errcode']))
            {
                $errcode = $res['errcode'];
                $errmsg  = $res['errmsg'];
                throw new \Exception("获取微信jspai_ticket 发生错误，错误代码为:$errcode, 错误信息：$errmsg");
            }
            $jsapi_ticket= $res['ticket'];
            $Cache->setItem(self::CACHE_KEY_WEIXIN_JSAPI_TICKET, $jsapi_ticket);
        }
        return $jsapi_ticket;
    }
}

