<?php
/**
 * @文件名称：IndexController.php
 * @编写时间: 2017年10月19日
 * @作者: 秦崇
 * @版本:
 * @说明: 
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
// use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    protected $dbAdapter        = null;
    protected $sessionContainer = null;

    public function __construct($dbAdapter, $sessionContainer)
    {
        $this->dbAdapter        = $dbAdapter;
        $this->sessionContainer = $sessionContainer;
    }

    public function indexAction()
    {
        
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump('DEFUALT_DEBUG_INFORMATION');
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
    }
}
