<?php
/*
 * 功能：标签统计Dao
 * author: warpath
 * date:2013年 05月 15日 星期三 19:37:12 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class TagcountDao extends BaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'tag_count';
	}
}
?>
