<?php
namespace Api\Service;

use Api\Filter\FormFilter;
use Api\Model\MyOrm;
use Api\Entity\RegisterEntity;
use Api\Service\Server\Weixiner;
use Api\Tool\MyCurl;

/**
* 所有的配置信息，都从此读取
*/
class RegisterManager
{
    const STATUS_APPLIYING = 'APPLYING';
    const STATUS_REFUSED = 'REFUSED';
    const STATUS_SUCCESS = 'SUCCESS';
    const PATH_FORM_FILTER_CONFIG = 'module/Api/src/Filter/rules/Register.php';
    const TEMPLATE_ID = 'WjeSsFkAn4U_P_zp0IW6BOqTIt3A2fDrSUaC4Z2KXMA';//申请受理通知
    /**
    * 存放巡逻点二维码文件路径
    * 以不同工地id分开保存
    */
    public $MyOrm;
    public $FormFilter;
    public $Weixiner;
    public function __construct(
        MyOrm $MyOrm,
        FormFilter $FormFilter,
        Weixiner $Weixiner
        )
    {
        $MyOrm->setEntity(new RegisterEntity());
        $MyOrm->setTableName(RegisterEntity::TABLE_NAME);
        $this->MyOrm = $MyOrm;
        $FormFilter->setRules(include self::PATH_FORM_FILTER_CONFIG);
        $this->FormFilter = $FormFilter;
        
        $this->Weixiner = $Weixiner;
    }
    
    public function sendTemplate($id, $success = true)
    {
        $Register   = $this->MyOrm->findOne($id);
        $openid     = $Register->getAdmin_openid();
        $realname   = $Register->getAdmin_realname();
        $workyard_name= $Register->getWorkyard_name();
        $tel        = $Register->getAdmin_tel();
        $created_at = $Register->getCreated_at();
        $username   = $Register->getAdmin_username();
        $password   = $Register->getAdmin_password();
        $access_token = $this->Weixiner->getAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
        $data = [
            'touser' => $openid,
            'template_id'=>self::TEMPLATE_ID,
            "url" =>"http://guard.jjhycom.cn/register?openid=$openid",  
            'data'=>[
                'first'=>[
                    'value'=> '工地注册成功!',
                ],
                'keyword1'=>[
                    'value'=> $realname,
                ],
                'keyword2'=>[
                    'value'=> $tel,
                ],
                'keyword3'=>[
                    'value'=> date('Y-m-d H:i:s', $created_at),
                ],
                'keyword4'=>[
                    'value'=> $workyard_name,
                ],
                'remark'=>[
                    'value'=> "用户名:$username, 密码:$password, 登录地址：http://xunluo.jjhycom.cn",
                ],
            ]
        ];
        if (empty($success))
        {
            $data['data']['first'] = '工地注册失败';
            $data['data']['remark'] = '请重新提交';
        }
        $data = json_encode($data);
        $res = MyCurl::post($data, $url);
        
//         $path="data/log/debug.log";
//         $mess=$id . '|' . $openid .  '|' .$res['errmsg'];
//         file_put_contents($path, $mess."\r\n", FILE_APPEND);
    }
}

