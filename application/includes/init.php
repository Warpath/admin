<?php
/**
 * 功能：初始文件
 * author: warpath
 * date:2013年 05月 01日 星期三 18:48:01 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
define('_SITE_ROOT_',str_replace('/application/includes','',str_replace('\application\includes','',dirname(__FILE__))));
define('_INCLUDES_DIR_', _SITE_ROOT_.'/application/includes/');
define('_ACTION_DIR_', _SITE_ROOT_.'/application/action/');
define('_MODEL_DIR_', _SITE_ROOT_.'/application/model/');
define('_DAO_DIR_', _SITE_ROOT_.'/application/dao/');
define('_VALIDATE_DIR_', _SITE_ROOT_.'/application/validate/');
define('_LIBS_DIR_', _SITE_ROOT_.'/libs/');
?>
