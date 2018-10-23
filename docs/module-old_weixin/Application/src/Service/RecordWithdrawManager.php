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

use Zend\Db\Sql\Sql;
use Application\Model\CommonModel;

class RecordWithdrawManager
{
    protected $CommonModel      = null;
    protected $table_withdraw   = 'record_withdraw';

    public function __construct(CommonModel $CommonModel)
    {
        $this->CommonModel=$CommonModel;
    }

    /**
     * get paginator
     *
     * @param            
     * @return
     */
    public function getPaginator($page = 1, $openid)
    {
        $sql    =$this->CommonModel->getSql();
        $select = $sql->select($this->table_withdraw);
        $select->where([
            'openid' => $openid
        ])->order([
            'status',
            'created DESC'
        ]);
        $this->CommonModel->setCountPerPage(3);
        return $this->CommonModel->paginator($select, $page);
    }
}

