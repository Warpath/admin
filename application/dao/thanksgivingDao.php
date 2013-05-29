<?php
/*
 * 功能：感恩Dao
 * author: warpath
 * date:2013年 05月 13日 星期一 16:46:06 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class ThanksgivingDao extends BaseDao
{

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'thanksgiving';
	}
}
?>
