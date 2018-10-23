<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Service\SignatureManager;
use Admin\Service\TokenManager;
use Application\Model\CommonModel;
use Admin\Api\YouzanApi;

class IndexController extends AbstractActionController
{
    protected $commonModle = null;
    protected $youzan = null;
    public function __construct(CommonModel $commonModle, $mySession, YouzanApi $youzan)
    {
        $this->commonModle=$commonModle;
        $this->youzan = $youzan;
    }

    public function indexAction()
    {
        exit();
    }
    
    /**
    * @test
    */
    public function testAction()
    {
        $token=$this->params()->fromQuery('token');
        if ($token != 'qinchong')
        {
            $this->redirect()->toRoute('point');
        }
        $signature  =SignatureManager::getInstanse();
        $array      =$signature->getSignatureArray('/seller');
        echo "<pre>";
        var_dump($array);
        echo "</pre>";
        exit('debug information');
    }
    public function getTokenAction()
    {
        $token=TokenManager::getInstace();
        $youzan_token=$token->getToken('youzan');
        echo "<pre>";
        var_dump($youzan_token);
        echo "</pre>";
        exit('debug information');
    }
    
}
