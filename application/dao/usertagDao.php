<?php
/*
 * 功能：用户标签Dao
 * author: warpath
 * date:2013年 05月 15日 星期三 19:35:33 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class UsertagDao extends BaseDao
{

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'user_tag';
	}
}
?>
