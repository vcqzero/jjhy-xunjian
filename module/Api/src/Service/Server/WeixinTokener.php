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

class WeixinTokener
{
    const CACHE_KEY_WEIXIN_TOKEN = 'weixin-token';
    
    private $Cache;
    private $Curler;
    private $weixin_token_config;

    public function __construct(
        Filesystem $Cache,
        array $weixinTokenConfig= null, 
        Curler $Curler)
    {
        $this->Cache = $Cache;
        $this->weixin_token_config= $weixinTokenConfig;
        $this->Curler  = $Curler;
    }
    
    /**
     * fetch the token array 
     *
     * @param            
     * @return array $res
     */
    public function getToken()
    {
        $Cache = $this->Cache;
        $weixin_token_config = $this->weixin_token_config;
        
        $expires_in = $weixin_token_config['expires_in'];
        $Cache->getOptions()->setTtl($expires_in - 20);
        
        $access_token = $Cache->getItem(self::CACHE_KEY_WEIXIN_TOKEN);
        if (empty($access_token))
        {
            //需要重新获取token
            $url = $weixin_token_config['url'];
            
            $data = [
                'appid'     => $weixin_token_config['appid'],
                'secret'    => $weixin_token_config['secret'],
                'grant_type'=> $weixin_token_config['grant_type'],
            ];
            
            $res = $this->Curler->get($url, $data);
            
            if (isset($res['errcode']))
            {
                $errcode = $res['errcode'];
                $errmsg  = $res['errmsg'];
                throw new \Exception("获取微信access_token 发生错误，错误代码为:$errcode, 错误信息：$errmsg");
            }
            $access_token = $res['access_token'];
            $expires_in   = $res['expires_in'];
            $res = $Cache->setItem(self::CACHE_KEY_WEIXIN_TOKEN, $access_token);
        }
        return $res;
    }
}

