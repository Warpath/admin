<?php
/*
 * 功能：sendemail Dao
 * author: warpath
 * date:2013年 05月 27日 星期一 05:54:57 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class SendemailDao extends BaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'send_email';
	}
}
?>
