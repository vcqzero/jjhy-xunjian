<?php
/**
 * @insruction:返现管理
 * @desc 从有赞服务器提取已完成订单列表，从列表中选取所有参加返现的订单列表
 *       以商品id为判断是否参加返现
 *
 * @fileName  :PointTradeManager.php
 * @author: 秦崇
 */
namespace Application\Service;

use Application\Model\CommonModel;
use Zend\Log\Logger;
use Admin\Service\CurlManager;
use Zend\Config\Reader\Json;

class CardManager
{
    protected $CommonModel      = null;
    protected $table_card       = 'cards';
    protected $curl             = null;
    protected $logger           = null;
    protected $banknameFilename ='module/Application/config/bankname.json';
    
    const CARD_TYPE_DEBIT_CARD  ='DC';//借记卡
    const CARD_STATUS_ENABLE    ='ENABLE';
    const CARD_STATUS_DISABLED  ='DISABLED';

    public function __construct(CommonModel $commonModle, Logger $logger)
    {
        $this->CommonModel      = $commonModle;
        $this->curl             = new CurlManager();
        $this->logger           = $logger;
    }

    /**
     * get user's card information
     * fornow one user can only one card
     *
     * @param string $openid            
     * @return array $card
     */
    public function fetchCardByOpenid($openid)
    {
        $select     =new \Zend\Db\Sql\Select($this->table_card);
        $select     ->where(['openid' => $openid])->limit(1);
        return $this->CommonModel->fetchOne($select);
    }
    
    /**
     * create card infomation
     *
     * @param int $user_id            
     * @return boolean
     */
    public function createCardIfNotExists($openid)
    {
        if ($this->fetchCardByOpenid($openid))
        {
            return;
        }
        $insert=new \Zend\Db\Sql\Insert($this->table_card);
        $values = [
            'openid'    => $openid,
            'created'   => date('Y-m-d H:i:s'),
            'status'    => self::CARD_STATUS_ENABLE,
        ];
        $insert->values($values);
        return $this->CommonModel->insertItem($insert);
    }

    /**
     * update card
     *
     * @param array $data            
     * @return boolean
     */
    public function updateCardById($data, $id)
    {
        $update = new \Zend\Db\Sql\Update($this->table_card);
        $update ->where('id=' . $id);
        $update ->set($data);
        $res    = $this->CommonModel->updateOrDeleteItem($update);
        return empty($res) ? false : true;
    }
    
    /**
    * 查询银行卡信息
    * 
    * @param  string || int $card_no      
    * @return array $card_infomation 
    */    
    /* public function validateCardInformationFromShangHaiJiaShu($card_no)
    {
        $url='http://api43.market.alicloudapi.com/api/c43';
        $data=[
            'bankcard'        =>$card_no,
        ];
        $appcode = "e952350f4dd546939fea7cec0e223d56";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $response=$this->curl->get($url, $data, $headers);
        if (empty($response))
        {
            $this->log->writeToFile('查询接口错误，请检查该get方法是否正确');
            return false;
        }
        $showapi_res_code=$response['error_code'];
        if (!empty($showapi_res_code))
        {
            $this->log->writeToFile('接口出问题'.$response['reason']);
            return false;//查询失败
        }
        return $response['result'];
    } */
    /**
    * 查询银行卡信息并返回经过处理的查询结果数组
    * 
    * @param  string $card
    * @return array  $card       
    */
    public function validateBankCard($card_no)
    {
        //先通过支付宝接口查询银行卡的基本信息包括银行卡名称，卡片类别等信息
        $basic=$this->queryBasicInfoByZhifubao($card_no);
        if (!$basic['validated'])
        {
            //非法卡号
            return false;
        }
        if ($basic['cardType'] != self::CARD_TYPE_DEBIT_CARD)
        {
            //不是借记卡
            return false;
        }
        
        $card_type_name=$basic['cardType'] == self::CARD_TYPE_DEBIT_CARD ? '借记卡' : '非借记卡';
        //查询出发卡地 可能查询不到
        $area   =$this->queryAreaInfoByKunMingXiuPai($card_no);
        
        return [
            'card_no'=>$basic['key'],
            'card_bank_name'=>$basic['name'],
            'card_bank_area'=>$area,
            'card_type_code'=>$basic['cardType'],
            'card_type_name'=>$card_type_name,
            'card_bank_branch_name'=>'',
        ];
    }
    /**
    * 查询银行卡信息
    * 
    * @param  string || int $card_no      
    * @return string bank_card_area or '' if not find 
    */    
    protected function queryAreaInfoByKunMingXiuPai($card_no)
    {
        try{
            $url='http://ali-bankcard.showapi.com/bankcard';
            $data=[
                'kahao'        =>$card_no,
            ];
            $appcode = "e952350f4dd546939fea7cec0e223d56";
            $headers = array();
            array_push($headers, "Authorization:APPCODE " . $appcode);
            $response=$this->curl->get($url, $data, $headers);
            if (empty($response))
            {
                throw new \Exception('查询银行卡未返回任何结果，请检查该get方法是否正确');
            }
            $showapi_res_code=$response['showapi_res_code'];
            if (!empty($showapi_res_code))
            {
                throw new \Exception('查询银行卡出错'.$response['showapi_res_error']);
            }
            $card=$response['showapi_res_body'];
            $area= array_key_exists('area', $card) ? $card['area'] : '';
        }catch (\Exception $e ){
            $area='';
            $this->logger->log(\Zend\Log\Logger::DEBUG, $e->getMessage());
        }
        
        return $area;//返回银行卡所在地区，如果未查询到，返回空
    }
    /**
    * 通过支付宝接口获取银行卡基本信息 是否是合法银行卡 借记卡还是信用卡
    * 
    * @param   int $card_no
    * @return  array $infomaton or false if not validate     
    */
    private function queryBasicInfoByZhifubao($card_no)
    {
        $url='https://ccdcapi.alipay.com/validateAndCacheCardInfo.json';
        $data=[
            '_input_charset'=>'utf-8',
            'cardNo'        =>$card_no,
            'cardBinCheck'  =>'true',
        ];
        $response=$this->curl->get($url, $data);
        if (empty($response))
        {
            $this->logger->log(\Zend\Log\Logger::DEBUG, '通过支付宝api获取银行卡名称出错，请检查该接口 file_path= '.__FILE__);
            return false;
        }
        if (!$response['validated'])
        {
            return $response;
        }
        $code                   =$response['bank'];
        $response['name']       =$this->queryBankNameByCode($code);
        return $response;
    }
    
    /**
    * 根据银行英文代码，获取银行中文 
    * 
    * @param  
    * @return        
    */
    protected function queryBankNameByCode($code)
    {
        $reader     =new Json();
        $bankname   =$reader->fromFile($this->banknameFilename);
        if (!array_key_exists($code, $bankname))
        {
            $message='通过bankname.json文件未读取到银行卡名称 , THE CODE= ' . $code;
            $this->logger->log(Logger::DEBUG, $message);
        }
        return array_key_exists($code, $bankname) ? $bankname[$code] : 'UNDEFIND';
    }
}

