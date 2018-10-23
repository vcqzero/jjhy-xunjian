<?php
namespace Application\Service;

class MyTokenManager
{

    protected $mySession = null;
    protected $keyName          ='myToken';

    public function __construct($mySession)
    {
        $this->mySession = $mySession;
    }

    /**
     * get the token from session
     *
     * @param  string $key
     * @return string $token
     */
    protected function readMyToken($keyName)
    {
        $token = $this->mySession->$keyName;
        return empty($token) ? 'TOKEN_IS_ILLEAGAL' : $token;
    }

    /**
     * 生成一个token并存入session中，无时效
     *
     * @param  string $suffix  
     * @return string $token 
     */
    public function setMyToken($suffix)
    {
        if (empty($suffix))
        {
            throw new \Exception('TOKEN 后缀不能为空');
        }
        
        $token  =$this->createToken();
        $key    =$this->getKeyName($suffix);
        $this   ->mySession->$key= $token;
        return  $token;
    }
    
    protected function getKeyName($suffix)
    {
        $keyName=$this->keyName;
        return $keyName . $suffix;
    }
    
    /**
    * create token
    * 
    * @param  void
    * @return string      
    */
    public function createToken()
    {
        $numbers    = range(10, 90); // 将10~50的数字排成数组
        shuffle($numbers); // shuffle 将数组顺序随即打乱
        $result     = array_slice($numbers, 0, 3); // 从数组中下标为3的开始取值，步长为3 也就是获取了6位随机数字
        $random     = implode('', $result);
        $token      = md5($random);
        return $token;
    }
    
    /**
     * clear myToken
     *
     * @param  void
     * @return void
     */
    protected function clearMyToken($keyName)
    {
        $this->mySession->$keyName   = null;
        return;
    }

    /**
     * verify token
     *
     * @param string $token from post
     * @param string $suffix 
     * @return boolean true or false
     */
    public function isValidate($token, $suffix)
    {
        $keyName    =$this->getKeyName($suffix);
        
        $res        = $token === $this->readMyToken($keyName) ? true : false;
        
        $this       ->clearMyToken($keyName);
        return $res;
    }
}

