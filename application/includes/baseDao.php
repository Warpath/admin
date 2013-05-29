<?php
/**
 * 功能：数据访问层基类
 * author: warpath
 * date:2013年 05月 02日 星期四 18:39:43 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
require_once(_INCLUDES_DIR_.'mysqlpdo.php');
class BaseDao
{
	var $_db = null;
	var $_table = null;

	public function __construct()
	{
		$this->_db = new MysqlPdo();	
	}

	public function getRow($fields='*', $where)
	{
		$sql = "SELECT $fields FROM $this->_table WHERE $where LIMIT 1";
		return $this->_db->fetchOne($sql);
	}

	public function getAll($fields='*', $where)
	{
		$sql = "SELECT $fields FROM $this->_table WHERE $where";
		return $this->_db->fetchAll($sql);
	}

	public function getCount($where)
	{
		$sql = "SELECT count(*) AS num FROM $this->_table WHERE $where";
		return $this->_db->count($sql);	
	}

	public function join($fields, $table, $where, $model='')
	{
		$sql = "SELECT $fields FROM $table WHERE $where";
		if ($model=='many') {
			return $this->_db->fetchAll($sql);
		} else {
			return $this->_db->fetchOne($sql);
		}
	}

	public function query($sql)
	{
		return $this->_db->query($sql);
	}

	/**
	 * @param array $opts
	 */
	public function create($params)
	{
		return $this->_db->insert($this->_table, $params);
	}

	public function update($params, $where)
	{
		return $this->_db->update($this->_table, $params, $where);
	}

	
}
?>
