<?php
namespace Api\Service;

use Api\Filter\FormFilter;
use Api\Model\MyOrm;
use Api\Entity\PointEntity;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;

/**
* 所有的配置信息，都从此读取
*/
class PointManager
{
    const PATH_FORM_FILTER_CONFIG = 'module/Api/src/Filter/rules/Point.php';
    
    /**
    * 存放巡检点二维码文件路径
    * 以不同工地id分开保存
    */
    const PATH_RECODE = 'data/qrcode/';
    
    public $MyOrm;
    public $FormFilter;
    public function __construct(
        MyOrm $MyOrm,
        FormFilter $FormFilter)
    {
        $MyOrm->setEntity(new PointEntity());
        $MyOrm->setTableName(PointEntity::TABLE_NAME);
        $this->MyOrm = $MyOrm;
        $FormFilter->setRules(include self::PATH_FORM_FILTER_CONFIG);
        $this->FormFilter = $FormFilter;
    }
    
    /**
    * 生成二维码，返回生成的二维码文件名称
    * 
    * @param array $values 要保存到二维码中的信息
    * @return string 二维码名称       
    */
    public function generateQrCode($point_id, $workyard_id)
    {
        //获取生产二维码对象
        $text   = json_encode(['point_id' => $point_id]);
        $qrCode = new QrCode($text);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::MEDIUM);
        
        //获取该二维码名称
        //如果没有创建保存文件夹，创建
        $dir = self::PATH_RECODE . $workyard_id;
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        $qr_name = $workyard_id . '_' . rand(1, 100) . time() . '.png';
        $qr_name = $dir . '/' . $qr_name;
        
        //保存二维码
        $qrCode->writeFile($qr_name);
        
        return $qr_name;
    }
    
}

