<?php
/**
 * @insruction:返现管理
 * @desc 从有赞服务器提取已完成订单列表，从列表中选取所有参加返现的订单列表
 *       以商品id为判断是否参加返现
 *
 * @fileName  :PointTradeManager.php
 * @author: 秦崇
 */
namespace Admin\Api;

use Zend\Config\Config;
use Zend\Log\Logger;

require_once 'vendor/youzan/lib/YZTokenClient.php';

class YouzanApi
{
    protected static $_instanse =null;
    
    protected $token_config_filename    = 'module/Admin/config/token.config.php';
    protected $token_config_data        = null;
    protected $saler_config_filename    = 'module/Admin/config/seller.config.php';
    protected $saler_config_data        = null;

    protected $youzan_token     = null;
    protected $client           = null;
    protected $logger           = null;

    protected final function __construct(\Zend\Log\Logger $logger)
    {
        $this->token_config_data = new Config(include $this->token_config_filename);
        $this->saler_config_data = new Config(include $this->saler_config_filename);
        
        $this->youzan_token   = $this->token_config_data->youzan->access_token;
        $this->client         = new \YZTokenClient($this->youzan_token);
        
        $this->logger = $logger;
    }
    protected function __clone(){}
    
    public static function getInstanse($logger)
    {
        return empty(self::$_instanse) ? new self($logger) : self::$_instanse;
    }
    
    /**
     * get user information by openid
     *
     * @param string $openid            
     * @return array $user or null if the user's openid is'not enable
     */
    public function fetchUserInfoByOpenid($openid)
    {
        $method = 'youzan.users.weixin.follower.get'; // 要调用的api名称
        $api_version = '3.0.0'; // 要调用的api版本号
        
        $my_params = [
            'weixin_openid' => $openid
        ];
        
        $my_files = [];
        $response = $this->client->post($method, $api_version, $my_params, $my_files);
        $this->storeError($response, __METHOD__);
        
        return empty($response['error_response']) ? $response['response']['user'] : null;
    }
    
    /**
    * get user's fans_id
    * 
    * @param  string $openid
    * @return int $fans_id       
    */
    protected function getUserFansidByOpenid($openid)
    {
        $user_data=$this->fetchUserInfoByOpenid($openid);
        return empty($user_data) ? '' : $user_data['user_id'];
    }
    
    /**
     * fetch traders information by trade_id
     *
     * @param  string $trade_id
     * @return array $trades
     */
    public function fetchTradeByTid($tid)
    {
        $method         = 'youzan.trade.get'; // 要调用的api名称
        $api_version    = '3.0.0'; // 要调用的api版本号
        
        $my_params      = [
            'tid'       => $tid,
        ];
        $my_files       = [];
        $response       = $this->client->post($method, $api_version, $my_params, $my_files);
        
        $this->storeError($response, __METHOD__);
        return empty($response['response']) ? [] : $response['response']['trade'];
    }
    
    /**
     * fetch refund trade data by refund id
     *
     * @param  $refund_id
     * @return array or []
     */
    public function fetchRefundByRefundid($refund_id)
    {
        $method = 'youzan.trade.refund.get'; //要调用的api名称
        $api_version = '3.0.0'; //要调用的api版本号
        
        $my_params = [
            'refund_id' => $refund_id,
        ];
        
        $my_files = [
        ];
        
        $response=$this->client->post($method, $api_version, $my_params, $my_files);
        $this->storeError($response, __METHOD__);
        return empty($response['response']) ? [] : $response['response'];
    }
    
    /**
     * fetch the seller's history trader
     *
     * @param  string $openi
     * @param  int $start_day
     * @param  int $end_day
     * @return array
     */
    public function fetchSellerHistoryTradeByOpenid($openid, $start_day, $end_day)
    {
        $fans_id=$this->getUserFansidByOpenid($openid);
        
        $method = 'youzan.salesman.account.score.search'; //要调用的api名称
        $api_version = '3.0.0'; //要调用的api版本号
        $my_params = [
            'fans_type'     => '1',
            'mobile'        => '0',
            'fans_id'       => $fans_id,
            'start_time'    => $start_day,
            'end_time'      => $end_day,
            'page_size'     => '100',
            'page_no'       =>1,
        ];
        $my_files = [
        ];
        $response=$this->client->post($method, $api_version, $my_params, $my_files);
        $this->storeError($response, __METHOD__);
        return array_key_exists('response', $response) ? $response['response']['accumulations'] : [];
    }
    
    /**
     * write error message into error.log
     *
     * @param array $response
     * @param string $mark
     * @return void
     */
    protected function storeError(array $response, $method)
    {
        if (empty($response['error_response'])) 
        {
            return false;
        }
        $this->logger->log(Logger::DEBUG, __METHOD__);
        foreach ($response['error_response'] as $key=>$mes)
        {
            $this->logger->log(Logger::DEBUG,$mes);
        }
        throw new \Exception($mes);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * get saler information by fans_id or mobile
     *
     * @param int $fans_id            
     * @return int saler's mobile or null if the user is not a saler
     */
    protected function fetchSalerByFansid($fans_id)
    {
        $method = 'youzan.salesman.account.get'; // 要调用的api名称
        $api_version = '3.0.0'; // 要调用的api版本号
        
        $my_params = [
            'mobile' => 0,
            'fans_type' => 1,
            'fans_id' => $fans_id
        ];
        
        $my_files = [];
        
        $response = $this->client->post($method, $api_version, $my_params, $my_files);
        return empty($response['error_response']) ? $response['response']['mobile'] : null;
    }
    
    
    /**
    * validate the user is a seller or not by user's openid
    * 
    * @param  string
    * @return boolean  true or false if not a seller     
    */
    public function isSellerByOpenid($openid)
    {
        $fans_id=$this->getUserFansidByOpenid($openid);
        $seller =$this->fetchSalerByFansid($fans_id);
        return empty($seller) ? false : true;
    }
    
    /**
     * get trades by qrcode
     *
     * @param date $created            
     * @return array $qr_trades
     */
    public function getTradesOfQr()
    {
        $method = 'youzan.trades.qr.get'; // 要调用的api名称
        $api_version = '3.0.0'; // 要调用的api版本号
        
        $created = $this->saler_config_data->last_query_time;
        
        $my_params = [
            'status' => 'TRADE_RECEIVED',
            'qr_id' => $this->saler_config_data->qr_id,
            'start_created' => date('Y-m-d H:i:s', $created)
        ];
        
        
        $my_files = [];
        
        $response = $this->client->post($method, $api_version, $my_params, $my_files);
        $this->storeError($response, __METHOD__);
        
        return $response['response']['total_results'] > 0 ? $response['response']['qr_trades'] : [];
    }

    /**
     * get traders of all from last created time by for loop
     *
     * @param int $last_query_time            
     * @return array $res
     */
    public function getAllTraders($last_query_time)
    {
        $method = 'youzan.trades.sold.get'; // 要调用的api名称
        $api_version = '3.0.0'; // 要调用的api版本号
        
        $start_created = $last_query_time;
        $end_created = date('Y-m-d H:i:s', strtotime("+3 months", strtotime($start_created)));
        
        /*
         * echo "<pre>";
         * var_dump($start_created);
         * var_dump($end_created);
         * echo "</pre>";
         * exit('debug information');
         */
        
        $res = [];
        $has_next = true;
        $page_no = 1;
        
        do {
            $my_params = [
                'page_size' => 20,
                'status' => 'TRADE_BUYER_SIGNED',
                'page_no' => $page_no,
                'use_has_next' => true,
                'start_created' => $start_created,
                'end_created' => $end_created
            ];
            
            $my_files = [
            ];
            
            $response = $this->client->post($method, $api_version, $my_params, $my_files);
            
            if (! empty($response['error_response'])) {
                $this->storeError($response, __METHOD__);
                break;
            }
            
            $has_next = $response['response']['has_next'];
            $res[$page_no] = $response['response']['trades'];
            $page_no ++;
        } while ($has_next);
        
        return $res;
    }
    
    /**
    * change user to be seller by openid
    * 
    * @param  string $openid
    * @return boolean       
    */
    public function changeUserToBeSellerByOpenid($openid)
    {
        $fans_id=$this->getUserFansidByOpenid($openid);
        return $this->changeUserToBeSellerByFansid($fans_id);
    }
    
    /**
     * change user to be a seller
     *
     * @param  $fans_id
     * @return boolean true or false
     */
    public function changeUserToBeSellerByFansid($fans_id)
    {
        $method = 'youzan.salesman.account.add'; // 要调用的api名称
        $api_version = '3.0.0'; // 要调用的api版本号
        
        $my_params = [
            'mobile'    => 0,
            'fans_type' => 1,
            'fans_id'   => $fans_id,
        ];
        
        $my_files = [];
        
        $response = $this->client->post($method, $api_version, $my_params, $my_files);
        $this->storeError($response, __METHOD__);
        
        return empty($response['response']) ? false : true;
    }
    
    /**
    * increase points 
    * 
    * @param string $openid 
    * @param int $points 
    * @param string $reason 
    * @return  boolean      
    */
    public function increasePointByOpenid($openid, $points, $reason)
    {
        $fans_id    =$this->getUserFansidByOpenid($openid);
        $res        =$this->increasePointsByFansid($fans_id, $points, $reason);
        return $res;
    }
    
    /**
     * add points
     *
     * @param string $openid            
     * @param int $points            
     * @param string $reason            
     * @return boolean
     */
    public function increasePointsByFansid($fans_id, $points, $reason)
    {
        $method = 'youzan.crm.customer.points.increase'; // 要调用的api名称
        $api_version = '3.0.1'; // 要调用的api版本号
        
        $my_params = [
            'fans_id' => $fans_id,
            'fans_type' => 1,
            'points' => $points,
            'reason' => $reason
        ];
        
        $my_files = [];
        
        $response = $this->client->post($method, $api_version, $my_params, $my_files);
        $this->storeError($response, __METHOD__);
        return empty($response['error_response']) ? true : false;
    }
}


