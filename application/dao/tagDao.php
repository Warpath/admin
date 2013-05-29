<?php
/*
 * 功能：标签Dao
 * author: warpath
 * date:2013年 05月 13日 星期一 23:50:01 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class TagDao extends BaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'tag';
	}
}
?>
