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
    const PATH_LOGO_IN_QRCODE = 'data/qrcode/logo_qrcode.jpg';
    const PATH_TEMPLATE       = 'data/qrcode/template.png';
    const RECORD_INFO = '技术支持：北京京玖恒阳科技发展有限公司';
    /**
    * 存放巡检点二维码文件路径
    * 以不同工地id分开保存
    */
    const PATH_QRCODE = 'data/qrcode/';
    
    public $MyOrm;
    public $FormFilter;
    public $WorkyardManager;
    /**
     * @param field_type $WorkyardManager
     */
    public function setWorkyardManager(WorkyardManager $WorkyardManager)
    {
        $this->WorkyardManager = $WorkyardManager;
    }

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
    
    public function findAllEnable($workyard_id)
    {
        $where = [
            PointEntity::FILED_WORKYARD_ID => $workyard_id
        ];
        $res  =$this->MyOrm->findAll($where);
        return $res;
    }
    
    public function zip($workyard_id)
    {
        $where = [
            PointEntity::FILED_WORKYARD_ID => $workyard_id
        ];
        $Points  =$this->MyOrm->findAll($where);
        if (empty(count($Points))) {
            return false;
        }
        //zip
        $base = 'data/temp/';
        if (!file_exists($base)) {
            mkdir($base);
        }
        //zip name 
        $Workyard = $this->WorkyardManager->MyOrm->findOne($workyard_id);
        $workyard_name = $Workyard->getName();
        $zipname = $base . $workyard_name . '_巡检点.zip';
        $zip = new \ZipArchive();
        $res = $zip->open($zipname, \ZipArchive::CREATE);
        if (empty($res)) {
            return false;
        }
        foreach ($Points as $Point)
        {
            $qrcode_file = $Point->getQrcode_filename();
            if (file_exists($qrcode_file))
            {
                $ext = strrchr($qrcode_file,'.');
                $qrcode_name = $Point->getName() . $ext;
                $zip->addFile($qrcode_file, $qrcode_name);
            }
        }
        $zip->close();
        return $zipname;
    }
    
    /**
    * 生成二维码，返回生成的二维码文件名称
    * 
    * @param int $point_id
    * @param int $workyard_id
    * @return string 二维码名称       
    */
    public function generateQrCode($point_id, $workyard_id)
    {
        $this->deleteQrcode($point_id);
        $qrcode = $this->qrcode($point_id, $workyard_id);
        $this->addToTemplate($qrcode);
        $this->addText($qrcode, $point_id, $workyard_id);
        
        //update table
        $set = [
            PointEntity::FILED_QRCODE_FILENAME => $qrcode
        ];
        $this->MyOrm->update($point_id, $set);
    }
    
    private function deleteQrcode($point_id)
    {
        $Point    = $this->MyOrm->findOne($point_id);
        $qrcode   = $Point->getQrcode_filename();
        if (file_exists($qrcode)) {
            unlink($qrcode);
        }
    }
    
    private function qrcode($point_id, $workyard_id)
    {
        //generate qrcode
        $data = [
            'point_id'      => $point_id,
            'workyard_id'   => $workyard_id,
        ];
        $string   = json_encode($data);
        $qrCode   = new QrCode($string);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
        //set logo
        $logoPath = self::PATH_LOGO_IN_QRCODE;
        if (file_exists($logoPath))
        {
            $qrCode->setLogoPath($logoPath);
            $qrCode->setLogoWidth(80);
        }
        //获取该二维码名称
        //如果没有创建保存文件夹，创建
        $dir = self::PATH_QRCODE . $workyard_id;
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        //qrcode name
        $qr_name = $point_id . '_' . rand(1, 100) . time() . '.png';
        $qr_name = $dir . '/' . $qr_name;
        //保存二维码
        $qrCode->writeFile($qr_name);
        return $qr_name;
    }
    
    private function addToTemplate($qrcode)
    {
        $template = self::PATH_TEMPLATE;
        if(!file_exists($template)){
            return ;
        }
        //imagecreatefrompng($filename)--由文件或 URL 创建一个新图象
        $image_qrcode   = imagecreatefrompng($qrcode);
        $image_template = imagecreatefrompng($template);
        //合成图片
        //imagecopymerge ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h , int $pct )---拷贝并合并图像的一部分
        //将 src_im 图像中坐标从 src_x，src_y 开始，宽度为 src_w，高度为 src_h 的一部分拷贝到 dst_im 图像中坐标为 dst_x 和 dst_y 的位置上。两图像将根据 pct 来决定合并程度，其值范围从 0 到 100。当 pct = 0 时，实际上什么也没做，当为 100 时对于调色板图像本函数和 imagecopy() 完全一样，它对真彩色图像实现了 alpha 透明。
        $width_qrcode = imagesx($image_qrcode);
        $width_template = imagesx($image_template);
        $width_offset = ($width_template - $width_qrcode) / 2; 
        imagecopymerge(
            $image_template, 
            $image_qrcode, 
            $width_offset, 339, 0, 0, 
            imagesx($image_qrcode), 
            imagesy($image_qrcode), 100);
        // 输出合成图片
        imagepng($image_template, $qrcode);
        imagedestroy($image_template);
        imagedestroy($image_qrcode);
    }
    
    private function addText($qrcode, $point_id, $workyard_id)
    {
        if(!file_exists($qrcode)){
            return ;
        }
        //info
        $Point    = $this->MyOrm->findOne($point_id);
        $Workyard = $this->WorkyardManager->MyOrm->findOne($workyard_id);
        $point_name = $Point->getName();
        $point_addr = $Point->getAddress();
        $workyard_name = $Workyard->getName();
        
        $image_qrcode = imagecreatefrompng($qrcode);
        $black=imagecolorallocate($image_qrcode,0,0,0);
        $white=imagecolorallocate($image_qrcode,255,255,255);
        //putenv('GDFONTPATH=' . realpath('.'));
        //add point name
        //指定字体内容
        //给图片添加文字
        $base_path = 'C:/Windows/Fonts/';
        $font_bd = $base_path . 'msyhbd.ttc';
        $size = 40;
        $x = 190;
        $y = 125;
        imagettftext($image_qrcode, $size, 0, $x, $y, $black, $font_bd, $point_name);
        
        //add workyard name, point addresee
        $font = $base_path . 'msyhl.ttc';
        $size = 16;
        $x = 155;
        $y = 261;
        imagettftext($image_qrcode, $size, 0, $x, $y, $white, $font_bd, $workyard_name);
        $y = 297;
        imagettftext($image_qrcode, $size, 0, $x, $y, $white, $font_bd, $point_addr);
        //add record
        $size = 10;
        $x = 214;
        $y = 690;
        $font = $base_path . 'msyh.ttc';
        $record = self::RECORD_INFO;
        imagettftext($image_qrcode, $size, 0, $x, $y, $white, $font, $record);
        imagepng($image_qrcode, $qrcode);
        imagedestroy($image_qrcode);
    }
}

