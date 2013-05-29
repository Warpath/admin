<?php
/**
 * 程序：业务层基类
 * 项目：breakfate
 * author: warpath
 * 2012年 09月 29日 星期六 19:05:56 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
require_once('factory.php');
class Model
{
	protected static function getDao($name)
	{
		return factory::getDao($name);
	}

	protected static function getValidate($name)
	{
		return factory::getValidate($name);
	}

	protected static function getService($name)
	{
		return Factory::getService($name);		
	}

	protected static function getEmailHandler()
	{
		return Factory::getEmailHandler();
	}

	protected static function getSphinxHandler()
	{
		return Factory::getSphinxHandler();
	}

	protected static function getImgHandler()
	{
		return Factory::getImgHandler();
	}
}
?>
