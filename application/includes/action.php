<?php
/**
 * 程序: 业务层基类
 * 项目: breakfate
 * author: warpath
 * 2012年 09月 14日 星期五 07:49:38 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
require_once(_INCLUDES_DIR_ . 'factory.php');
class Action
{
	protected static function getService($name)
	{
		return Factory::getService($name);		
	}

	protected static function getSmarty()
	{
		return Factory::getSmarty();	
	}

	protected static function getPage($param)
	{
		return Factory::getPage($param);
	}
}
?>
