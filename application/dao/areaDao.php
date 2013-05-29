<?php
/*
 * 功能：地区Dao
 * author: warpath
 * date:2013年 05月 19日 星期日 18:40:51 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class AreaDao extends BaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'area';
	}
}
?>
