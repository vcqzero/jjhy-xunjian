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
use Api\Tool\MyCurl;

class Weixiner
{
    const CACHE_KEY_WEIXIN_TOKEN = 'weixin-token';
    const CACHE_KEY_WEIXIN_JSAPI_TICKET = 'weixin-jsapi-ticket';
    
    const EXPIRES_IN = 7200;
    
    private $Cache;
    private $appid;
    private $secret;

    public function __construct(
        Filesystem $Cache,
        $weixinConfig
        )
    {
        $this->Cache = $Cache;
        $this->appid = $weixinConfig['appid'];
        $this->secret= $weixinConfig['secret'];
    }
    
    /**
     * fetch the access_token array 
     *
     * @param            
     * @return string $access_token
     */
    public function getAccessToken()
    {
        //get access token
        $url = 'http://index.jjhycom.cn/api/access-token';
        $res = MyCurl::get($url);
        $access_token = $res['access_token'];
        return $access_token;
    }
    
    public function getWxConfig($_url)
    {
        //get wxconfig
        $url = 'http://index.jjhycom.cn/api/wxconfig';
        $data = ['url'=>$_url];
        $res = MyCurl::post($data, $url);
        $wxconfig= $res['wxconfig'];
        return $wxconfig;
    }
    
    /**
    * 获取用户openid
    * 静默方式
    * 
    * @param string $code 
    * @return string $openid       
    */
    public function getOpenid($code)
    {
        $appid  = $this->appid;
        $secret = $this->secret;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $res = MyCurl::get($url);
        
        if (!empty($res['errcode']))
        {
            $errcode = $res['errcode'];
            $errmsg  = $res['errmsg'];
            throw new \Exception("获取微信openid 发生错误，错误代码为:$errcode, 错误信息：$errmsg");
        }
        
        $openid= $res['openid'];
        return $openid;
    }
}

