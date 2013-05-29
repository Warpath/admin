<?php
/*
 * 功能：user_basic表DAO
 * author: warpath
 * date:2013年 05月 03日 星期五 17:40:03 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class UserbasicDao extends BaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'user_basic';
	}

}
?>
