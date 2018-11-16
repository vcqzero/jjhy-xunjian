<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Service\PointManager;
use Api\Entity\PointEntity;
use Api\Tool\MyDownload;
use Api\Service\WorkyardManager;

class PointController extends AbstractActionController
{
    private $PointManager;
    public function __construct(PointManager $PointManager, WorkyardManager $WorkyardManager)
    {
        $this->PointManager = $PointManager;
        $this->PointManager->setWorkyardManager($WorkyardManager);
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
        $this->ajax()->close($res);
        
        //删除原图片，重新生成
        $workyard_id = $this->params()->fromRoute('workyardID');
        $qrcode_name = $this->PointManager->generateQrCode($pointID, $workyard_id);
        exit();
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
        $point_id    = $this->PointManager->MyOrm->getLastInsertId();
        $workyard_id = $values[PointEntity::FILED_WORKYARD_ID];
        $qrcode_name = $this->PointManager->generateQrCode($point_id, $workyard_id);
        exit();
    }
    
    public function downloadAction()
    {
        $pointID = $this->params()->fromRoute('pointID');
        $workyard_id = $this->params()->fromRoute('workyardID');
        
        $Point    = $this->PointManager->MyOrm->findOne($pointID);
        $qrcode   = $Point->getQrcode_filename();
        $point_name = $Point->getName();
        $workyard_name = $this->params()->fromQuery('workyard_name');
        $point_name = str_replace(';', '', $point_name);
        $point_name = $workyard_name . '_' . "$point_name";
        MyDownload::download($qrcode, $point_name);
        exit();
    }
    
    public function downloadAllAction()
    {
        $workyard_id = $this->params()->fromQuery('workyard_id');
        $zip_name    = $this->PointManager->zip($workyard_id);
        if (empty($zip_name)) {
            exit();
        }
        MyDownload::download($zip_name);
        unlink($zip_name);
        exit();
    }
    
    public function deleteAction()  
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        $pointID = $this->params()->fromRoute('pointID');
        //先把二维码删除
        $Point = $this->PointManager->MyOrm->findOne($pointID);
        $qrcode= $Point->getQrcode_filename();
        if (file_exists($qrcode)) {
            unlink($qrcode);
        }
        
        $res = $this->PointManager->MyOrm->delete($pointID);
        $this->ajax()->success($res);
    }
    
    public function testAction()
    {
        $this->PointManager->generateQrCode(63, 33);
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump('ppp');
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
    }
}
