<?php
namespace Api\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class AjaxPlugin extends AbstractPlugin
{
    const AJAX_RESULT_SUCCESS  = 'success';
    const AJAX_RESULT_MESSAGE  = 'message';
    
    /**
    * 
    * 
    * @param bool  $success 
    * @param array $message  
    * @return void     
    */
    public function success($success, $message = null)
    {
        $responce = [
            self::AJAX_RESULT_SUCCESS => $success === true,
            self::AJAX_RESULT_MESSAGE => $message,
        ];
        echo json_encode($responce);
        exit();
    }
    
    public function close($success, $message = null)
    {
        $responce = [
            self::AJAX_RESULT_SUCCESS => $success === true,
            self::AJAX_RESULT_MESSAGE => $message,
        ];
        echo json_encode($responce);
        
        // get the size of the output
        $size = ob_get_length();
        // send headers to tell the browser to close the connection
        header("Content-Length: $size");
        header('Connection: close');
        ob_end_flush();
        ob_flush();
        flush();
        
        /******** background process starts here ********/
        ignore_user_abort(true);//在关闭连接后，继续运行php脚本
        /******** background process ********/
        set_time_limit(0); //no time limit，不设置超时时间（根据实际情况使用）
    }
    
    /**
    * 是否验证通过
    * 
    * @param bool $isValid
    * @return void       
    */
    public function valid($isValid)
    {
        $result = [
            'valid' => $isValid === true,
        ];
        echo json_encode($result);
        exit();
    }
}