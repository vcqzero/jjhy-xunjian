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
        $data = [
            'touser' => $openid,
            'template_id'=>self::TEMPLATE_ID,
            "url" =>"http://guard.jjhycom.cn/register?openid=$openid",  
            'data'=>[
                'first'=>[
                    'value'=> '项目注册成功!',
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
                    'value'=> "根据用户名:$username, 密码:$password, 访问工地巡逻安保管理系统：http://xunluo.jjhycom.cn",
                ],
            ]
        ];
        if (empty($success))
        {
            $data['data']['first'] = '项目注册失败';
            $data['data']['remark'] = '请重新提交';
        }
        $this->sendMsg($data);
    }
    
    public function sentMsgToAdmin($id)
    {
        $openid_admin = 'oy38vxD8aqaLV5pajqoR1kqA1Hqc';
        $Register   = $this->MyOrm->findOne($id);
        $realname   = $Register->getAdmin_realname();
        $workyard_name= $Register->getWorkyard_name();
        $tel        = $Register->getAdmin_tel();
        $created_at = $Register->getCreated_at();
        $username   = $Register->getAdmin_username();
        $password   = $Register->getAdmin_password();
        
        $data = [
            'touser'        => $openid_admin,
            'template_id'   =>'WjeSsFkAn4U_P_zp0IW6BOqTIt3A2fDrSUaC4Z2KXMA',
            "url" =>"",
            'data'=>[
                'first'=>[
                    'value'=> '有新项目申请',
                ],
                'keyword1'=>[
                    'value'=> $realname,//客户姓名
                ],
                'keyword2'=>[
                    'value'=> $tel,//客户手机
                ],
                'keyword3'=>[
                    'value'=> date('Y-m-d H:i:s'),//提交时间
                ],
                'keyword4'=>[
                    'value'=> $workyard_name,//申请项目
                ],
                'remark'=>[
                    'value'=> "请登录管理后台进行处理",
                ],
            ]
        ];
        $this->sendMsg($data);
        
    }
    
    private function sendMsg($data)
    {
        $access_token = $this->Weixiner->getAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
        $data = json_encode($data);
        $res = MyCurl::post($data, $url);
    }
}

