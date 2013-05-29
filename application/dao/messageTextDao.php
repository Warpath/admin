<?php
/*
 * author: warpath
 * date:2013年 05月 23日 星期四 02:23:42 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class MessageTextDao extends BaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'messageText';
	}
}
?>
