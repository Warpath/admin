<?php
/************
 * 程序：数据库操作类
 * author: warpath
 * 2012年 08月 22日 星期三 02:48:23 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class MysqlPdo
{
	//数据库连接
	private $_dbh = null;

	//PDOStatement对象
	private $_stmt = null;

	public function __construct()
	{
		$this->connect();
	}

	/**
	 * 连接数据库
	 */
	public function connect()
	{
		try {
			$this->_dbh = new PDO('mysql:host='._DBHOST_.';dbname='._DATABASE_.';charset=utf8', _DBUSER_, _DBPWD_);
			return $this->_dbh;
		} catch (PDOException $e) {
			print 'Error!: ' . $e->getMessage() . '<br/>';
		}
	}

	/**
	 * desc: fetch one result
	 * @param $sql 查询语句
	 * reutrn	   array
	 */
	public function fetchOne($sql, $fetchMode = PDO::FETCH_ASSOC)
	{
		$this->query($sql);
		return $this->_stmt->fetch($fetchMode);
	}

	/**
	 * desc: fetch all result
	 * @param $sql 查询语句
	 * return      array
	 */
	public function fetchAll($sql, $fetchMode = PDO::FETCH_ASSOC)
	{
		//$this->query($sql, $params);
		$this->query($sql);
		return $this->_stmt->fetchAll($fetchMode);
	}

	/**
	 * desc: 查询记录数
	 * @param $sql 查询语句
	 * @return int
	 */
	public function count($sql)
	{
		$this->query($sql);
		return $this->_stmt->fetchColumn();
	}

	/**
	 * desc: 数据表插入
	 * @param $tablename 数据库表名
	 * @param @params 数据
	 * return int 插入数据id
	 */
	public function insert($tablename, $params)
	{
		$fields = array_keys($params);
		$field  = implode(',', $fields);
		$question = str_repeat('?, ', count($params));
		$question = rtrim($question, ', ');
		$sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $tablename, $field, $question);
		$result = $this->query($sql, $params);
		return $this->lastInsertId();
	}

	/**
	 * desc: 数据表更新
	 * @param $tablename 数据库表名
	 * @param $params 数据
	 * @param $where 条件语句
	 * return int 影响行数
	 */
	public function update($tablename, $params, $where = 1)
	{
		$fields = array_keys($params);
		$field  = implode(' =?, ', $fields).' = ?';
		$sql = sprintf('UPDATE %s SET %s WHERE %s', $tablename, $field, $where);
		$result = $this->query($sql, $params);
		return $this->rowCount();
	}

	/**
	 * desc: 数据表删除
	 * @param $tablename 数据库表名
	 * @param $where 条件语句
	 * return int 影响行数
	 */
	public function delete($tablename, $where)
	{
		$sql = sprintf('DELETE FROM %s WHERE %s', $tablename, $where);
		$result = $this->query($sql);
		return $this->rowCount();
	}

	/**
	 * desc: rowCount
	 */
	private function rowCount()
	{
		try {
			return $this->_stmt->rowCount();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * desc: lastInsertId
	 */
	private function lastInsertId()
	{
		try {
			return $this->_dbh->lastInsertId();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * desc: query
	 * @param $sql 查询语句
	 * @param $params 参数
	 * return result
	 */
	private function query($sql, $params=null)
	{
		$this->_prepare($sql);
		if (!empty($params)) {
			$key = 1;
			foreach ($params as $param) {
				$this->_bindParam($key++, $param);
			}
		}
		return $this->_execute();
	}

	/**
	 * desc: execute
	 */
	private function _execute()
	{
		try {
			$result = $this->_stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		return $result;
	}

	/**
	 * desc: bindParam
	 * @param $key 参数序号
	 * @param $param 参数 
	 */
	private function _bindParam($key, $param) 
	{
		try {
			$this->_stmt->bindParam($key, $param);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * desc: prepare
	 */
	private function _prepare($sql)
	{
		try {
			$this->_stmt = $this->_dbh->prepare($sql);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}
?>
