<?php
/**
 * 程序: 工厂类
 * 项目: breakfate
 * author: warpath
 * 2012年 09月 14日 星期五 07:50:52 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class Factory
{
	private static $_service = null;
	private static $_smarty  = null;
	private static $_dao     = null;
	private static $_validate= null;
	private static $_emailHander = null;
	private static $_sphinx  = null;
	private static $_imgHandler = null;
	private static $_page = null;

	/**
	 * desc:smarty模板
	 */
	public static function getSmarty()
	{
		if (empty(self::$_smarty)) {
			require(_LIBS_DIR_ . 'smarty/Smarty.class.php');
			self::$_smarty = new smarty();
			self::$_smarty->template_dir = _SITE_ROOT_ . '/templates/';
			self::$_smarty->compile_dir  = _SITE_ROOT_ . '/temp/templates_c/';
			self::$_smarty->cache_dir    = _SITE_ROOT_ . '/temp/cache/';
			self::$_smarty->cacheing     = false;
			self::$_smarty->left_delimiter = '<{';
			self::$_smarty->right_delimiter = '}>';

			global $siteurl;
			global $imgurl;
			self::$_smarty->assign('siteurl', $siteurl);
			self::$_smarty->assign('imgurl', $imgurl);
		}

		return self::$_smarty;
	}

	/**
	 * desc:业务逻辑层
	 */
	public static function getService($name)
	{
		if (empty(self::$_service[$name])) {
			require_once(_INCLUDES_DIR_ . 'model.php');
			require_once(_MODEL_DIR_ . $name . '.php');
			$classname = ucfirst($name);
			self::$_service[$name] = new $classname;
		}

		return self::$_service[$name];
	}

	/**
	 * desc:DAO层
	 */
	public static function getDao($name)
	{
		if (empty(self::$_dao[$name])) {
			require_once(_INCLUDES_DIR_ . 'baseDao.php');
			require_once(_DAO_DIR_ . $name . 'Dao.php');
			$classname = ucfirst($name);
			$classname .= 'Dao';
			self::$_dao[$name] = new $classname;
		}

		return self::$_dao[$name];
	}

	/**
	 * 验证类
	 */
	public static function getValidate($name)
	{
		if (empty(self::$_validate[$name])) {
			require_once(_INCLUDES_DIR_ . 'validate.php');
			require_once(_VALIDATE_DIR_ . $name . 'Validate.php');
			$classname = ucfirst($name);
			$classname .= 'Validate';
			self::$_validate[$name] = new $classname;
		}

		return self::$_validate[$name];
	}

	/**
	 * 邮件发送类
	 */
	public static function getEmailHandler()
	{
		if (empty(self::$_emailHander)) {
			require_once(_LIBS_DIR_ . 'email/phpmailer.class.php');
			self::$_emailHander = new phpmailer();
		}

		return self::$_emailHander;
	}

	/**
	 * 获得sphinx操作对象
	 */
	public static function getSphinxHandler()
	{
		if (empty(self::$_sphinx)) {
			require_once (_LIBS_DIR_ . 'sphinx/sphinxapi.php');
			self::$_sphinx = new SphinxClient();
			self::$_sphinx->SetServer('127.0.0.1', 9312);
			self::$_sphinx->SetArrayResult(true);
		}

		return self::$_sphinx;
	}

	/**
	 * 获得img操作对象
	 */
	public static function getImgHandler()
	{
		if (empty(self::$_imgHandler)) {
			require_once (_INCLUDES_DIR_ . 'Image.class.php');
			self::$_imgHandler = new Image;
		}

		return self::$_imgHandler;
	}

	/*
	 * 分页类
	 */
	public static function getPage($param)
	{
		if (empty(self::$_page)) {
			require_once (_INCLUDES_DIR_ . 'page.class.php');
			self::$_page = new page($param);
		}

		return self::$_page;
	}
}
?>
