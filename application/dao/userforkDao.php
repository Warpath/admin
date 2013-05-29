<?php
/**
 * 功能：用户关注DAO
 * author: warpath
 * date:2013年 05月 03日 星期五 00:03:07 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class UserforkDao extends BaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'user_fork';
	}
}
?>
