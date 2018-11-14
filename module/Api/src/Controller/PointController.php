<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Service\PointManager;
use Api\Entity\PointEntity;
use Api\Tool\MyDownload;

class PointController extends AbstractActionController
{
    private $PointManager;
    public function __construct(PointManager $PointManager)
    {
        $this->PointManager = $PointManager;
    }
    /**
     * We override the parent class' onDispatch() method to
     * set an alternative layout for all actions in this controller.
     */
    public function onDispatch(MvcEvent $e)
    {
        // Call the base class' onDispatch() first and grab the response
        $response = parent::onDispatch($e);
        
        // Set alternative layout
        $this->layout()->setTemplate('layout/blank.phtml');
        
        // Return the response
        return $response;
    }
    
    
    public function validNameAction()
    {
        $name    = $this->params()->fromPost('name');
        $workyard_id = $this->params()->fromPost('workyard_id');
        $point_id    = $this->params()->fromPost('point_id');
        
        if (!empty($point_id))
        {
            //代表编辑
            //如果新名称和原名称一致则返回true
            $Entity = $this->PointManager->MyOrm->findOne($point_id);
            $name_old = $Entity->getName();
            if ($name_old == $name)
            {
                $this->ajax()->valid(true);
            }
        }
        
        $where = [
            PointEntity::FILED_NAME => $name,
            PointEntity::FILED_WORKYARD_ID => $workyard_id
        ];
        $count  = $this->PointManager->MyOrm->count($where);
        $this->ajax()->valid(empty($count));
    }
    
    //edit the website infomation
    public function editAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        $pointID = $this->params()->fromRoute('pointID');
        //获取用户提交表单
        $values = $this->params()->fromPost();
        //do filter
        $values = $this->PointManager->FormFilter->getFilterValues($values);
        //执行增加操作
        $res = $this->PointManager->MyOrm->update($pointID, $values);
        $this->ajax()->success($res);
    }
    
    public function addAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        
        //获取用户提交表单
        $values = $this->params()->fromPost();
        //set created
        $values[PointEntity::FILED_CREATED] = time();
        
        //do filter
        $values = $this->PointManager->FormFilter->getFilterValues($values);
        //执行增加操作
        $res = $this->PointManager->MyOrm->insert($values);
        $this->ajax()->close($res);
        //生成二维码图片
        //获取id
        $point_id = $this->PointManager->MyOrm->getLastInsertId();
        $workyard_id = $values[PointEntity::FILED_WORKYARD_ID];
        $name = $values[PointEntity::FILED_NAME];
        //获取二维码文件名称
        $qrcode_name = $this->PointManager->generateQrCode($point_id, $workyard_id, $name);
        //将名称和地址增加到数组中
        $set[PointEntity::FILED_QRCODE_FILENAME] = $qrcode_name;
        
        //然后更新
        $res = $this->PointManager->MyOrm->update($point_id, $set);
        $this->ajax()->success($res);
    }
    
    public function downloadAction()
    {
        $qrcode_name = $this->params()->fromQuery('qrcode_name');
        $download_name= $this->params()->fromQuery('download_name');
        MyDownload::download($qrcode_name, $download_name);
        exit();
    }
    
    public function testAction()
    {
        $this->ajax()->close(false);
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump('ok');
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
    }
}
