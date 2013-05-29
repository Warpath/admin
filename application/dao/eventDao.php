<?php
/**
 * 功能：事件DAO
 * author: warpath
 * date:2013年 05月 03日 星期五 00:35:24 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class EventDao extends BaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'event';
	}
}
?>
