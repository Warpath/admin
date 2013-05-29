<?php
/*
 * 功能：评论Dao
 * author: warpath
 * date:2013年 05月 18日 星期六 22:43:49 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class CommentsDao extends BaseDao
{

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'comments';
	}
}
?>
