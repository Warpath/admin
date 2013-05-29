<?php
/**
 * 功能：用户建议Dao
 * author: warpath
 * date:2013年 05月 24日 星期五 05:59:49 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class SuggestDao extends BaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'suggest';
	}
}
?>
