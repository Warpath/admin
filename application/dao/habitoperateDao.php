<?php
/*
 * 功能：habit操作Dao
 * author: warpath
 * date:2013年 05月 15日 星期三 05:53:23 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class HabitoperateDao extends BaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'habit_operate';
	}
}	
?>
