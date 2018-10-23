<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\ShiftTimePointManager;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class ShiftTimePointHelper extends AbstractHelper 
{
    private $ShiftTimePointManager;
    
    public function __construct(
        ShiftTimePointManager $ShiftTimePointManager
        )
    {
        $this->ShiftTimePointManager = $ShiftTimePointManager;
    }
}
