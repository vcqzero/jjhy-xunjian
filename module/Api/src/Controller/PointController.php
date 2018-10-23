<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Service\PointManager;
use Api\Entity\PointEntity;

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
        $name  = $this->params()->fromPost('name');
        $workyard_id = $this->params()->fromPost('workyard_id');
        $point_id    = $this->params()->fromPost('point_id');
        $where = [
            PointEntity::FILED_NAME => $name,
            PointEntity::FILED_WORKYARD_ID => $workyard_id
        ];
        $Entity = $this->PointManager->MyOrm->findOne($where);
        $count  = $this->PointManager->MyOrm->getCount();
        if ($count && $point_id == $Entity->getId())
        {
            $this->ajax()->valid(true);
        }
        $this->ajax()->valid(empty($count));
    }
    
    //edit the website infomation
    public function editAction()
    {
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
        //获取用户提交表单
        $values = $this->params()->fromPost();
        
        //do filter
        $values = $this->PointManager->FormFilter->getFilterValues($values);
        //执行增加操作
        $res = $this->PointManager->MyOrm->insert($values);
        //生成二维码图片
        //获取id
        $point_id = $this->PointManager->MyOrm->getLastInsertId();
        $workyard_id = $values[PointEntity::FILED_WORKYARD_ID];
        //获取二维码文件名称
        $qrcode_name = $this->PointManager->generateQrCode($point_id, $workyard_id);
        //将名称和地址增加到数组中
        $set[PointEntity::FILED_QRCODE_FILENAME] = $qrcode_name;
        
        //然后更新
        $res = $this->PointManager->MyOrm->update($point_id, $set);
        $this->ajax()->success($res);
    }
    
    public function downloadAction()
    {
        
        $pointID = $this->params()->fromRoute('pointID');
        $Entity  = $this->PointManager->MyOrm->findOne($pointID); 
        $qrcode_name = $Entity->getQrcode_filename();
        $name    = $Entity->getName();
        $this->Download()->download($qrcode_name, $name);
        exit();
    }
}
