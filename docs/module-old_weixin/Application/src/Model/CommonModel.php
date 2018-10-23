<?php
namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Form\Annotation\Instance;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\View\Helper\PaginationControl;
use Exception;
use Zend\Log\Logger;

class CommonModel
{
    protected static $_instanse=null;
    
    protected $dbAdapter    = null;
    protected $sql          = null;
    protected $logger       = null;
    
    protected $page             = 1;
    protected $countPerPage     = 12;
    protected $scrollingStyle   = 'Sliding';
    protected $viewPartial      = 'partial/partial/my_pagination_control';
    

    /**
     * @return the $dbAdapter object
     */
    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }

    /**
     * @return the $sql object
     */
    public function getSql()
    {
        return $this->sql;
    }

    protected final function __construct(Sql $sql, Adapter $dbAdapter, Logger $logger=null)
    {
        $this->sql          = $sql;
        $this->dbAdapter    = $dbAdapter;
        $this->logger       = $logger;
    }
    private function __clone(){}
    
    /**
    * get the instanse
    * 
    * @param  object $sql
    * @param  object $dbAdapter
    * @return object $instanse       
    */
    public static function getInstanse(Sql $sql, Adapter $dbAdapte, Logger $loggerr)
    {
        return empty(self::$_instanse) ? new self($sql, $dbAdapte, $loggerr) : self::$_instanse;
    }
    /**
     *
     * @return the $page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     *
     * @return the $scrollingStyle
     */
    public function getScrollingStyle()
    {
        return $this->scrollingStyle;
    }

    /**
     *
     * @return the $viewPartial
     */
    public function getViewPartial()
    {
        return $this->viewPartial;
    }

    /**
     *
     * @param number $page            
     */
    protected final function setPage($page)
    {
        $page = (int) $page;
        if (!empty($page)) 
        {
            $this->page = $page;
        }
    }

    /**
     *
     * @param string $scrollingStyle            
     */
    public function setScrollingStyle($scrollingStyle)
    {
        $this->scrollingStyle = $scrollingStyle;
    }

    /**
     *
     * @param string $viewPartial            
     */
    public function setViewPartial($viewPartial)
    {
        $this->viewPartial = $viewPartial;
    }

    /**
     *
     * @return the $countPerPage
     */
    public function getCountPerPage()
    {
        return $this->countPerPage;
    }

    /**
     *
     * @param number 
     * $countPerPage
     * and 2 <= $countPerPage <= 10
     */
    public function setCountPerPage($countPerPage)
    {
        $countPerPage       = (int) $countPerPage;
        if ($countPerPage >= 2 && $countPerPage <= 10) 
        {
            $this->countPerPage = $countPerPage;
        }
    }

    /**
     *
     * get paginator
     * 
     * @param            
     *
     * @return
     *
     */
    public function paginator($select, $page)
    {
        $this->setPage($page);
        // get paginator
        $paginator  = new Paginator(new DbSelect($select, $this->dbAdapter));
        // setting page
        $paginator  ->setCurrentPageNumber($this->page);
        $paginator  ->setItemCountPerPage($this->countPerPage);
        // setting style
        Paginator::setDefaultScrollingStyle($this->scrollingStyle);
        PaginationControl::setDefaultViewPartial($this->viewPartial);
        
        return $paginator;
    }

    /**
     * my:fetch result by select(object)
     *
     * @param object $select object Instance of select
     * @return array $res(double dimensional array) or []
     */
    public function fethchAll($select)
    {
        $sth    = $this->sql->prepareStatementForSqlObject($select);
        $result = $sth->execute(); // or $res=iterator_to_array($res);
                                 
        // get zhe result of select by using resultSet object
        $restulSet  = new ResultSet();
        $res        = $restulSet->initialize($result)->toArray();
        
        return empty($res) ? [] : $res;
    }

    /**
     * fetch one from result
     *
     * @param            
     *
     * @return array or false if fail
     */
    public function fetchOne(\Zend\Db\Sql\Select $select)
    {
        $select->limit(1);
        $sth    = $this->sql->prepareStatementForSqlObject($select);
        $result = $sth->execute(); // or $res=iterator_to_array($res);
        // get zhe result of select by using resultSet object
        
        $restulSet  = new ResultSet();
        $res        = $restulSet->initialize($result)->toArray();
        
        return empty($res) ? false : $res[0];
    }

    /**
     * insert one item
     *
     * @param object $insert            
     * @return int | or false
     */
    public function insertItem($insert)
    {
        try {
            if (empty($insert instanceof \Zend\Db\Sql\Insert))
            {
                throw new \Exception('INSERT MUST BE INSERT OBJECT');
            }
            $sth = $this->sql->prepareStatementForSqlObject($insert);
            $res = $sth->execute()->getGeneratedValue();
        } catch (\Exception $e) {
            $this->log($e->getMessage());
            $res = false;
        }
        return $res;
    }

    /**
     * update or delete one item
     *
     * @param object $insert            
     * @return int the affected rows
     */
    public function updateOrDeleteItem($updateOrDelete)
    {
        try {
            $flag   = true;
            $sth    = $this->sql->prepareStatementForSqlObject($updateOrDelete);
            $res    = $sth->execute()->getAffectedRows();
        } catch (\Exception $e) {
            $flag   = false;
            $sql    = $this->sql->buildSqlString($updateOrDelete);
            $mess   = $e->getMessage() . 'sql=' . $sql;
            $this->log($mess);
        }
        return $flag;
    }
    
    /**
    * 获取错误异常，保存到文件中
    * 
    * @param object Logger $logger 
    * @return void       
    */
    private function log($message)
    {
        $logger=$this->logger;
        if (empty($logger instanceof Logger))
        {
            return ;
        }
        $logger->log(Logger::DEBUG, $message);
    }
}

