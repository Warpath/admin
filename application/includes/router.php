<?php
/**
 * 请求路由
 * author: warpath
 * 2012年 09月 10日 星期一 08:26:09 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class Router
{
	public function dispatch()
	{
		$m = isset($_GET['m']) ? $_GET['m'] : 'index';
		$a = isset($_GET['a']) ? $_GET['a'] : 'index';

		try {
			if (file_exists(_ACTION_DIR_ . $m . 'Action.php')) {
				require_once(_INCLUDES_DIR_ . 'action.php');
				require_once(_ACTION_DIR_ . $m . 'Action.php');
				$class = $m . 'Action';
				$action = new $class;
				if (method_exists($action, $a)) {
					$method = $a;
					$action->$method();
				} else {
					throw new Exception('Method: ' . $a . ' in controller: ' . $m . ' not found');
				}
			} else {
				throw new Exception('Controller not found: ' . $m);
			}
		} catch (Exception $e) {
			echo 'Message: ' . $e->getMessage(); 	
		}
	}

	private function authority($model)
	{
		if ($model !== 'index' && $model !== 'account') {
			if (!isset($_COOKIE['userId']) && empty($_COOKIE['userId'])) {
				header('Location:/login');
				exit;
			}
		}
	}
}
?>
