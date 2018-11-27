<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Service\ShiftManager;
use Api\Service\ShiftGuardManager;
use Api\Service\ShiftTypeManager;
use Api\View\Helper\ShiftGuardHelper;
use Api\View\Helper\ShiftTimeHelper;
use Api\View\Helper\UserHelper;
use Api\Tool\MyCSV;
use Api\Service\WorkyardManager;
use Api\View\Helper\PointHelper;
use Api\View\Helper\ShiftTimePointHelper;

class ShiftController extends AbstractActionController
{

    private $ShiftManager;
    private $ShiftGuardManager;
    private $ShiftTypeManager;
    private $ShiftGuardHelper;
    private $ShiftTimeHelper;
    private $UserHelper;
    private $WorkyardManager;
    private $PointHelper;
    private $ShiftTimePointHelper;
    public function __construct(
        ShiftManager $ShiftManager, 
        ShiftGuardManager $ShiftGuardManager, 
        ShiftTypeManager $ShiftTypeManager,
        ShiftGuardHelper $ShiftGuardHelper,
        ShiftTimeHelper $ShiftTimeHelper,
        UserHelper $UserHelper,
        WorkyardManager $WorkyardManager,
        PointHelper $PointHelper,
        ShiftTimePointHelper $ShiftTimePointHelper
        )
    {
        $this->ShiftManager = $ShiftManager;
        $this->ShiftGuardManager = $ShiftGuardManager;
        $this->ShiftTypeManager = $ShiftTypeManager;
        $this->ShiftGuardHelper = $ShiftGuardHelper;
        $this->ShiftTimeHelper  = $ShiftTimeHelper;
        $this->UserHelper       = $UserHelper;
        $this->WorkyardManager       = $WorkyardManager;
        $this->PointHelper = $PointHelper;
        $this->ShiftTimePointHelper = $ShiftTimePointHelper;
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

    public function addAction()
    {
        $token = $this->params()->fromQuery('token');
        if (! $this->Token()->isValid($token)) {
            $this->ajax()->success(false);
        }
        
        // 获取用户提交表单
        $values = $this->params()->fromPost();
        $date_range = $this->params()->fromPost('date-range');
        $shift_type_id = $this->params()->fromPost('shift_type_id');
        $values = $this->params()->fromPost();
        
        $dates = $this->ShiftManager->getDatesFromDateRange($date_range);
        $ShiftTypeEntity = $this->ShiftTypeManager->MyOrm->findOne($shift_type_id);
        $connection = $this->ShiftManager->MyOrm->getConnection();
        try {
            $connection->beginTransaction();
            foreach ($dates as $date) {
                $values = $this->ShiftManager->processShiftType($values, $date, $ShiftTypeEntity);
                $shift_id = $this->ShiftManager->add($values);
                if (empty($shift_id)) {
                    throw new \Exception('insert shift error');
                }
                
                // 然后保存到shift_guard 数据表中
                $guard_ids = $this->params()->fromPost('guard_ids');
                foreach ($guard_ids as $key => $guard_id) {
                    $res = $this->ShiftGuardManager->add($guard_id, $shift_id);
                    if (empty($res)) {
                        throw new \Exception('insert shift_guard error');
                    }
                }
            }
            $connection->commit();
            $res = true;
        } catch (\Exception $e) {
            $res = false;
            $connection->rollback();
        }
        $this->ajax()->success($res);
    }

    public function deleteAction()
    {
        $token = $this->params()->fromQuery('token');
        if (! $this->Token()->isValid($token)) {
            $this->ajax()->success(false);
        }
        
        $shift_id = $this->params()->fromRoute('shiftID');
        $connection = $this->ShiftManager->MyOrm->getConnection();
        $connection->beginTransaction();
        
        try {
            // 从shift表中删除
            $res = $this->ShiftManager->MyOrm->delete($shift_id);
            if (empty($shift_id)) {
                throw new \Exception('delete shift error');
            }
            
            // 从shift_guard表中删除
            $res = $this->ShiftGuardManager->deleteBy($shift_id);
            if (empty($shift_id)) {
                throw new \Exception('delete shift guard error');
            }
            
            $connection->commit();
            $res = true;
        } catch (\Exception $e) {
            $res = false;
            $connection->rollback();
        }
        $this->ajax()->success($res);
    }

    public function editAction()
    {
        //验证token 
        $token = $this->params()->fromQuery('token');
        if (! $this->Token()->isValid($token)) {
            $this->ajax()->success(false);
        }
        
        $shift_id = $this->params()->fromRoute('shiftID');
        // 获取用户提交表单
        $values = $this->params()->fromPost();
        $connection = $this->ShiftManager->MyOrm->getConnection();
        $connection->beginTransaction();
        try {
            // 更新shift仅可更新note
            // 其他信息不可更新
            $values = $this->ShiftManager->FormFilter->getFilterValues($values);
            // 执行更新
            $res = $this->ShiftManager->MyOrm->update($shift_id, $values);
            if (empty($res)) {
                throw new \Exception('update shift error');
            }
            
            // 先将shift_guard 中关于shift_id 的全部删掉
            $res = $this->ShiftGuardManager->deleteBy($shift_id);
            if (empty($res)) {
                throw new \Exception('delete shift_guard error');
            }
            // 然后保存到shift_guard 数据表中
            $guard_ids = $this->params()->fromPost('guard_ids');
            foreach ($guard_ids as $key => $guard_id) {
                $res = $this->ShiftGuardManager->add($guard_id, $shift_id);
                if (empty($res)) {
                    throw new \Exception('insert shift_guard error');
                }
            }
            $connection->commit();
            $res = true;
        } catch (\Exception $e) {
            $res = false;
            $connection->rollback();
        }
        $this->ajax()->success($res);
    }
    
    /**
    * 导出巡逻员值班记录
    * 
    * @param  
    * @return        
    */
    public function csvAction() 
    {
        $workyard_id   = $this->params()->fromQuery('workyard_id');
        $Workyard      = $this->WorkyardManager->MyOrm->findOne($workyard_id);
        $workyard_name = $Workyard->getName();
        $query  = $this->params()->fromQuery();
        $csv_name = $workyard_name . '_值班考勤统计表.csv';
        $desc = [
            ['值班考勤统计表'],
            ['项目名称', $workyard_name],
            ['项目地址', $Workyard->getAddress()],
            ['考勤统计时间', date('Y-m-d H:i:s')],
        ];
        $head_title = [
            '巡逻员',
            '值班日期',
            '班次',
            '值班时间',
            '需巡逻次数',
            '已巡逻次数',
            '是否完成',
            '统计时间',
        ];
        
        $paginator = $this->ShiftGuardHelper->getAllGuardsPaginator($workyard_id, 1, $query);
        $count = $paginator->count();
        $data = [];
        $page = 1;
        while ($page <= $count)
        {
            $paginator_page = $paginator->getItemsByPage($page);
            
            foreach ($paginator_page as $Entity)
            {
                //about shift
                $shiftID         = $Entity->getId();
                $shift_type_name = $Entity->getShift_type_name();
                $start_time      = $Entity->getStart_time();
                $end_time        = $Entity->getEnd_time();
                $times           = $Entity->getTimes();
                $note            = $Entity->getNote();
                //shift date
                $start_day   = date('Y-m-d', $start_time);
                $end_day     = strtotime($start_day) + 60 * 60 * 24;
                $is_next_day = $end_time > $end_day;
                $format_start_time = date('H:i', $start_time);
                $format_end_time   = date('H:i', $end_time);
                
                $format_time = "$format_start_time - $format_end_time";
                $format_time = $is_next_day ? $format_time . ' （次日）' : $format_time;
                
                //about guard
                $guard_id   = $Entity->getGuard_id();
                $UserEntity = $this->UserHelper->getEntity($guard_id);
                $realName   = $UserEntity->getRealName();
                $username   = $UserEntity->getUsername();
                
                //has done
                //当前shift已完成巡逻次数
                $done_count = $this->ShiftTimeHelper->getDoneCount($guard_id, $shiftID);
                //是否完成巡逻
                //是否完成规定的巡逻次数
                $has_done = $done_count >= $times;
                if ($has_done) {
                    $status = '已完成';
                }else if ($end_time >= time()) {
                    $status = '巡逻中...';
                }else {
                    $status = '未完成';
                }
                
                //the item data
                $data_item = [
                    $username,
                    $start_day,
                    $shift_type_name,
                    $format_time,
                    $times,
                    $done_count,
                    $status,
                    date('Y-m-d H:i:s')
                ];
                $data[] = $data_item;
            }
            
            $page++;
        }
        
        MyCSV::export_csv($data, $head_title, $csv_name, $desc);
        exit();
    }
    
    public function csvDetailAction() 
    {
        $guard_id   = $this->params()->fromQuery('guard_id');
        $shift_id   = $this->params()->fromQuery('shift_id');
        //user
        $UserEntity = $this->UserHelper->getEntity($guard_id);
        $realName   = $UserEntity->getRealName();
        $username   = $UserEntity->getUsername();
        //shift
        $ShiftEntity = $this->ShiftManager->MyOrm->findOne($shift_id);
        $workyard_id = $ShiftEntity->getWorkyard_id();
        //workyard
        $Workyard      = $this->WorkyardManager->MyOrm->findOne($workyard_id);
        $workyard_name = $Workyard->getName();
        // about shift info
        $shift_type_name    = $ShiftEntity->getShift_type_name();
        $start_time         = $ShiftEntity->getStart_time();
        $end_time           = $ShiftEntity->getEnd_time();
        $times              = $ShiftEntity->getTimes();
        $note               = $ShiftEntity->getNote();
        // shift date
        $start_day      = date('Y-m-d', $start_time);
        $end_day        = strtotime($start_day) + 60 * 60 * 24;
        $is_next_day    = $end_time > $end_day;
        $format_start_time  = date('H:i', $start_time);
        $format_end_time    = date('H:i', $end_time);
        if($is_next_day) $format_end_time = $format_end_time . '（次日）';
        $csv_name = $start_day . '_' . $shift_type_name . '_' . $username . '_考勤明细.csv';
        $desc = [
            ['值班考勤明细表'],
            ['巡逻员', $username],
            ['真实姓名', $realName],
            ['项目名称', $workyard_name],
            ['项目地址', $Workyard->getAddress()],
            ['值班时间', $start_day],
            ['班次', $shift_type_name],
            ['具体时间', "$format_start_time - $format_end_time"],
            ['需巡逻次数', $times],
            ['备注', $note],
            ['考勤统计时间', date('Y-m-d H:i:s')],
        ];
        $head_title = [
            '序号',
            '巡逻点',
            '位置',
            '巡逻时间',
            '是否巡逻',
            '统计时间',
        ];
        
        //shift times
        $shfit_time_ids = $this->ShiftTimeHelper->getShfitTimeIDs($shift_id, $guard_id);//array
        $desc[] = ['已巡逻次数', count($shfit_time_ids)];
        $data = [];
        if (empty(count($shfit_time_ids))) $data[] = ['没有巡逻记录'];
        
        foreach ($shfit_time_ids as $key1=>$shfit_time_id)
        {
            $count = $key1 + 1;
            $data[] = ['第' . $count . '次巡逻记录'];
            $PointEntities = $this->PointHelper->getEntitiesOnShift($workyard_id, $shift_id);
            foreach ($PointEntities as $key2=>$PointEntity)
            {
                $point_id    = $PointEntity->getId();
                $pointName   = $PointEntity->getName();
                $address     = $PointEntity->getAddress();
                // 该巡逻点巡逻信息
                $ShiftTimePointEntity = $this->ShiftTimePointHelper->getEntity($shfit_time_id, $point_id);
                $has_done = !empty($ShiftTimePointEntity->getId());
                $time = $ShiftTimePointEntity->getTime();
                $note = $ShiftTimePointEntity->getNote();
                $address_path = $ShiftTimePointEntity->getAddress_path();
                $time = $time ? date('Y-m-d H:i:s', $time) : '-';
                $note = $note ? $note : '-';
                $has_done  = $has_done ? '已巡逻' : '未巡逻';
                $item = [
                    ++$key2,
                    $pointName,
                    $address,
                    $time,
                    $has_done,
                    date('Y-m-d H:i:s')
                ];
                $data[] = $item;
            }
        }
        MyCSV::export_csv($data, $head_title, $csv_name, $desc);
        exit();
    }
}
