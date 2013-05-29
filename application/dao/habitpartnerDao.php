<?php
/*
 * 功能：习惯搭档Dao
 * author: warpath
 * date:2013年 05月 15日 星期三 18:22:08 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class HabitpartnerDao extends BaseDao
{

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'habit_partner';
	}
}
?>
