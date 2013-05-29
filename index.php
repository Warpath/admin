<?php
/**
 * 程序名：网站入口
 * author: warpath
 * date: 2013年 05月 01日 星期三 18:46:49 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
header("content-type:text/html;charset=utf-8");
error_reporting(E_ALL);
require (dirname ( __FILE__ ) . '/application/includes/init.php');
require_once(_SITE_ROOT_.'/config/config.php');
require_once(_INCLUDES_DIR_.'public.func.php');
require_once(_INCLUDES_DIR_.'router.php');
include_once(_INCLUDES_DIR_.'dic.em.php');
include_once(_INCLUDES_DIR_.'userHandler.class.php');

$iu = encryptionUrl("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$userHandler = new userHandler($iu);

$router = new Router();
$router->dispatch();
?>
