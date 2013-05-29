<?php
/*
 * 功能：登录验证
 * author: warpath
 * date:2013年 05月 24日 星期五 04:51:17 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class UserHandler 
{
	public function __construct($iu)
	{
		$m = isset($_GET['m']) ? $_GET['m'] : 'index';
		$a = isset($_GET['a']) ? $_GET['a'] : 'index';

		$allowAction = array(
			'index', 'tags', 'user', 'vote'
		);
		if (in_array($m, $allowAction)) {
			if(!isset($_COOKIE['signin'])) {
				global $siteurl;
				header('location: '.$siteurl.'/signin?iu='.$iu);
			} 
		}
	}
}
?>
