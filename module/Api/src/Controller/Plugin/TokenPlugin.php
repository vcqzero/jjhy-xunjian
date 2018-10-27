<?php
namespace Api\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class TokenPlugin extends AbstractPlugin
{
    private $mySession;
    private $seperator = '|';
    private $session_token_name  = 'name';
    private $session_token_value = 'value';
    public function __construct(
        $mySession
        )
    {
        $this->mySession   = $mySession;
    }
    
    /**
     * 生成一个token并存入session中，
     * 返回生成的token
     * 此token中分隔符|前面的是sessionKey
     *
     * @param  string $tokenName token名称
     * @return string json 字符串
     */
    public function token($tokenName = null)
    {
        //获取session key 和 token
        $tokenName  = empty($tokenName) ? time() : $tokenName;
        $sessionkey = $this->generateTokenKey($tokenName);
        $token      = $this->generateTokenString();
        //将tokenName拼接到token中
        $array = [
            $this->session_token_name  => $sessionkey,
            $this->session_token_value => $token,
        ];
        
        //保存session
        $this-> mySession->$sessionkey = $token;
        
        return  json_encode($array);
    }
    
    /**
     * 获取最终存入session时使用的session key name
     *
     * @param  string $tokenName
     * @return string key
     */
    private function generateTokenKey($tokenName)
    {
        $tokenName = __CLASS__ . $tokenName;
        $tokenName = str_shuffle(md5($tokenName));
        $tokenName = substr($tokenName, 1, 9);
        $tokenName = str_replace($this->seperator, '', $tokenName);
        return $tokenName;
    }
    /**
     * create token
     *
     * @param  void
     * @return string (Md5)
     */
    private function generateTokenString():string
    {
        $numbers    = range(10, 90); // 将10~50的数字排成数组
        shuffle($numbers); // shuffle 将数组顺序随即打乱
        $result     = array_slice($numbers, 0, 3); // 从数组中下标为3的开始取值，步长为3 也就是获取了6位随机数字
        $random     = implode('', $result);
        return md5($random);
    }
    
    /**
     * 验证token值是否合法
     *
     * @param string $token
     * @return boolean true or false
     */
    public function isValid($token)
    {
        if (empty($token))
        {
            return false;
        }
        $tokenArray         = json_decode($token, true);
        $session_token_name = $tokenArray[$this->session_token_name];
        $session_token_value= $tokenArray[$this->session_token_value];
        
        //获取并重置token
        $tokenInSession = $this->mySession->$session_token_name;
        $this->mySession->$session_token_name = null;
        
        //返回验证结果
        return $session_token_value === $tokenInSession;
    }
}