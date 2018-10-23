<?php
/**
 * @insruction:返现管理
 * @desc 从有赞服务器提取已完成订单列表，从列表中选取所有参加返现的订单列表
 *       以商品id为判断是否参加返现
 *
 * @fileName  :PointTradeManager.php
 * @author: 秦崇
 */
namespace Application\Service;

use Application\Model\CommonModel;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;

class PointManager
{
    protected $CommonModel  = null;
    protected $table_point_traders = 'point_traders';
    
    public function __construct(CommonModel $CommonModel)
    {
        $this->CommonModel      = $CommonModel;
    }

    public function getPaginator($openid, $page = 1)
    {
        if (empty($openid))
        {
            throw new \Exception('系统错误，openid 不可为空');
        }
        $select = new Select($this->table_point_traders);
        $select->where  ->equalTo('openid', $openid)
                        ->greaterThanOrEqualTo('end_date', strtotime(date('Y-m-01 00:00:00')));
        $this->CommonModel->setCountPerPage(3);
        $paginator = $this->CommonModel->paginator($select, $page);
        return $paginator;
    }
}

