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
    const PATH_LOGO_IN_QRCODE = 'data/qrcode/logo_in_qrcode.jpg';
    const PATH_LOGO           = 'data/qrcode/logo.jpg';
    const PATH_TEMPLATE       = 'data/qrcode/template.png';
    const RECORD_INFO = '技术支持：北京京玖恒阳科技发展有限公司';
    /**
    * 存放巡逻点二维码文件路径
    * 以不同工地id分开保存
    */
    const PATH_QRCODE = 'data/qrcode/';
    
    public $MyOrm;
    public $FormFilter;
    public $WorkyardManager;
    private $width;
    private $height_angle_white;
    private $height_angle_blue;
    private $x_title;
    private $size_title;
    private $size_content;
    private $x_content;
    private $y_conten;
    private $y_qrcode;
    private $width_qrcode;
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
        $zipname = $base . $workyard_name . '_巡逻点.zip';
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
    public function generateQrCode($point_id, $workyard_id=null)
    {
        //info
        $Point       = $this->MyOrm->findOne($point_id);
        $workyard_id = $Point->getWorkyard_id();
        $Workyard = $this->WorkyardManager->MyOrm->findOne($workyard_id);
        $point_name = $Point->getName();
        //only 7 chars
        $point_name = mb_substr($point_name, 0, 6, 'utf-8');
        $point_addr = $Point->getAddress();
        //only 18 chars
        $point_addr = mb_substr($point_addr, 0, 17, 'utf-8');
        $workyard_name = $Workyard->getName();
        $workyard_name = mb_substr($workyard_name, 0, 50, 'utf-8');
        //delete old 
        $qrname = $Point->getQrcode_filename();
        $this->deleteQrcode($qrname);
        
        //如果没有创建保存文件夹，创建
        $dir = self::PATH_QRCODE . $workyard_id;
        if (!file_exists($dir))  mkdir($dir);
        //qrcode name
        $qrname = $point_id . '_' . rand(1, 100) . time() . '.png';
        $qrname = $dir . '/' . $qrname;
        
        //i create template img src
        $image = $this->createTemplate($qrname);
        //ii add logo point name
        $image = $this->addLogo($image, $qrname);
        $image = $this->addPointName($image, $qrname, $point_name);
        //iii add workyard name
        $image = $this->addWorkyardName($image, $qrname, $workyard_name);
        //iiii add point address
        $image = $this->addPointAddress($image, $qrname, $point_addr);
        //iiiii get and add qrcode
        $qrcode_temp = $this->getQrcode($point_id, $workyard_id);
        $image       = $this->addQrcode($image, $qrname, $qrcode_temp);
        //iiiiii add record
        $image = $this->addRecord($image, $qrname);
        //iiiiiii destroy img src
        unlink($qrcode_temp);
        imagedestroy($image);
        //iiiiiiii update mysql
        $set = [
            PointEntity::FILED_QRCODE_FILENAME => $qrname
        ];
        $this->MyOrm->update($point_id, $set);
        return ;
    }
    
    private function createTemplate($qrname)
    {
        //create image
        $this->width = $width = 595;//a4
        $height = 842;//a4
        $image = imageCreatetruecolor($width, $height);
        //为真彩色画布创建白色背景，再设置为透明
        $white = imagecolorallocate($image, 255, 255, 255);//白色背景
        $blue  = imagecolorallocate($image, 0, 148, 220);//blue
        //fill white
        imagefill($image, 0, 0, $white);
        //angle
        //height
        $this->height_angle_blue  = $angle_height_blue  = (690 - 200) / 690 * $height;
        $this->height_angle_white = $angle_blue_y = $height - $angle_height_blue;
        imagefilledrectangle ($image, 0, $angle_blue_y, $width, $height, $blue);
        imagepng ($image, $qrname);
        return $image;
    }
    
    private function addLogo($image, $qrname)
    {
        $width_image  = imagesx($image);
        $heigth_image = imagesy($image);
        $logo_path = self::PATH_LOGO;
        $logo = imagecreatefromjpeg($logo_path);
        
        //scaling logo if big
        $width_logo  = imagesx($logo);
        $heigth_logo = imagesy($logo);
        $width_logo_need = 95 / 488 * $width_image;
        if ($width_logo > $width_logo_need) {
            $logo = imagescale($logo, $width_logo_need);
            $width_logo  = imagesx($logo);
            $heigth_logo = imagesy($logo);
        }
        //get the x y 
        $logo_x = 65 / 488 * $width_image;
        $logo_y = ($this->height_angle_white - $width_logo) / 2;//保持居中
        
        $res = imagecopymerge(
            $image, 
            $logo, 
            $logo_x, 
            $logo_y, 
            0, 
            0, 
            (int)$width_logo, 
            (int)$heigth_logo, 
            100);
        
        imagedestroy($logo);
        imagepng ($image, $qrname);
        return $image;
    }
    
    private function addPointName($image, $qrname, $pointName)
    {
        $base_path = 'C:/Windows/Fonts/';
        $font_bd = $base_path . 'msyhbd.ttc';
        $black=imagecolorallocate($image ,0,0,0);
        $size = 50;
        $x = 190 / 488 * ($this->width);
        $y = ($this->height_angle_white + $size) / 2;
        imagettftext($image, $size, 0, $x, $y, $black, $font_bd, $pointName);
        imagepng ($image, $qrname);
        return $image;
    }
    
    private function addWorkyardName($image, $qrname, $workyardName)
    {
        $base_path = 'C:/Windows/Fonts/';
        $msyhbd  = $base_path . 'msyhbd.ttc';
        $msyh    = $base_path . 'msyh.ttc';
        $white=imagecolorallocate($image,255,255,255);
        
        //add workayrd title
        $title = '项目名称：';
        $this->size_title = $size_title = 20;
        $this->x_title = $x_title = 14 / 488 * ($this->width);
        $y_title = 8 / 100 * ($this->height_angle_blue) + $this->height_angle_white;
        imagettftext($image, $size_title, 0, $x_title, $y_title, $white, $msyh, $title);
        
        //add workyard name
        $this->size_content = $size_content = 20;
        $this->x_content = $x_content = $x_title + 130;
        $start = 0;
        $length = 16;
        $substr = mb_substr($workyardName, $start, $length, 'utf-8');
        $y_cntent = $y_title;
        while (!empty($substr)) {
            imagettftext($image, $size_content, 0, $x_content, $y_cntent, $white, $msyhbd, $substr);
            $this->y_conten = $y_cntent = $y_cntent + 35;
            $start = $start + $length;
            $substr = mb_substr($workyardName, $start, $length, 'utf-8');
        }
        imagepng ($image, $qrname);
        return $image;
    }
    
    private function addPointAddress($image, $qrname, $point_address)
    {
        $base_path = 'C:/Windows/Fonts/';
        $msyhbd  = $base_path . 'msyhbd.ttc';
        $msyh    = $base_path . 'msyh.ttc';
        $white=imagecolorallocate($image,255,255,255);
        
        //add workayrd title
        $title = '巡逻地点：';
        $size_title = $this->size_title;
        $x_title = $this->x_title;
        $y_title = $this->y_conten + 10;
        imagettftext($image, $size_title, 0, $x_title, $y_title, $white, $msyh, $title);
        
        //add point address
        $size_content = $this->size_content;
        $x_content = $this->x_content;
        $y_cntent  = $y_title;
        imagettftext($image, $size_content, 0, $x_content, $y_cntent, $white, $msyhbd, $point_address);
        
        imagepng ($image, $qrname);
        return $image;
    }
    
    private function deleteQrcode($qrname)
    {
        if (file_exists($qrname)) {
            unlink($qrname);
        }
    }
    
    
    private function getQrcode($point_id, $workyard_id)
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
            $qrCode->setLogoWidth(64);
        }
        //set qrcode size
        $width = $this->height_angle_blue + $this->height_angle_white - ($this->y_conten);
        $this->width_qrcode = $width = $width * 0.75;
        $qrCode->setSize($width);
        $temp_path = 'data/temp/qrcode_' . $point_id;
        $qrCode->writeFile($temp_path);
        //保存二维码
        return $temp_path;
    }
    
    private function addQrcode($image, $qrname, $qrcode_temp)
    {
        //imagecreatefrompng($filename)--由文件或 URL 创建一个新图象
        $image_qrcode   = imagecreatefrompng($qrcode_temp);
        //合成图片
        //imagecopymerge ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h , int $pct )---拷贝并合并图像的一部分
        //将 src_im 图像中坐标从 src_x，src_y 开始，宽度为 src_w，高度为 src_h 的一部分拷贝到 dst_im 图像中坐标为 dst_x 和 dst_y 的位置上。两图像将根据 pct 来决定合并程度，其值范围从 0 到 100。当 pct = 0 时，实际上什么也没做，当为 100 时对于调色板图像本函数和 imagecopy() 完全一样，它对真彩色图像实现了 alpha 透明。
        $width_qrcode = imagesx($image_qrcode);
        $width_template = imagesx($image);
        $width_offset = ($width_template - $width_qrcode) / 2; 
        $this->y_qrcode = $y = $this->y_conten + 40;
        imagecopymerge(
            $image, 
            $image_qrcode, 
            $width_offset, 
            $y, 
            0, 
            0, 
            imagesx($image_qrcode), 
            imagesy($image_qrcode), 100);
        // 输出合成图片
        imagepng($image, $qrname);
        imagedestroy($image_qrcode);
        return $image;
    }
    
    private function addRecord($image, $qrname)
    {
        $base_path = 'C:/Windows/Fonts/';
        $font_bd = $base_path . 'msyhbd.ttc';
        //add record
        $size = 17;
        $x = 140;
        $y = $this->y_qrcode + $this->width_qrcode + 55;
        $record = self::RECORD_INFO;
        $white=imagecolorallocate($image,255,255,255);
        imagettftext($image, $size, 0, $x, $y, $white, $font_bd, $record);
        imagepng($image, $qrname);
        return $image;
    }
}

