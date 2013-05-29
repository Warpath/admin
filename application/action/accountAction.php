<?php
/*
 * 功能：用户账号相关
 * author: warpath
 * date:2013年 05月 02日 星期四 04:57:47 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class AccountAction extends Action
{
	/**
	 * 注册
	 */
	public function signup()
	{
		$template = 'signup.html';
		$smarty = self::getSmarty();
		$smarty->display($template);
	}

	/**
	 * 注册handler
	 */
	public function signupHandler()
	{
		$name     = isset($_POST['name'])     ? addslashes($_POST['name']) : null;
		$email    = isset($_POST['email'])    ? addslashes($_POST['email']) : null;
		$password = isset($_POST['password']) ? addslashes($_POST['password']) : null;
		$opts = array(
			'name' => $name,
			'email'    => $email,
			'password' => $password,	
		);
		$result = self::getService('user')->signup($opts);

		if (true == $result['flg']) {
			//TODO::发送邮件
			//$res = self::getService('email')->sendSignupEmail($name, $email);
			setcookie('userId', $result['userId'], time()+60*60*24*30, '/', 'breakfate.com');
			header('Location:'.'/');
		}
	}

	/**
	 * 登录
	 */
	public function login()
	{
		$key  = array('iu');
		$params = getUrlParam($key);

		$template = 'signin.html';
		$smarty = self::getSmarty();
		if (!empty($params)) {
			$smarty->assign('iu', $params['iu']);
		}

		$smarty->display($template);
	}

	/**
	 * 登录handler
	 */
	public function loginHandler()
	{
		$http_referer = $_SERVER['HTTP_REFERER'];
		$http_referer = preg_split("/\?/", $http_referer);
		if (isset($http_referer[1])) {
			$query_string = $http_referer[1];
			$query_string = explode('&', $query_string);
			if (!empty($query_string)) {
				foreach($query_string as $val) {
					$params = explode('=', $val);
					if($params[0] == 'iu') {
						$iu = $params[1];
						$iu = analyticUrl($iu);
					}
				}
			}
		}
		$name     = isset($_POST['name'])     ? addslashes($_POST['name']) : null;
		$password = isset($_POST['password']) ? addslashes($_POST['password']) : null;
		$opts = array(
			'name' => $name,
			'password' => $password,	
		);
		$result = self::getService('user')->login($opts);		

		if (true == $result['flg']) {
			if (isset($iu)) {
				header('Location: '."$iu");
			} else {
				header('Location: ' . '/');
			}
		} else {
			$template = 'signin.html';
			$smarty = self::getSmarty();
			$smarty->assign('error', $result['data']);
			$smarty->display($template);
		}
	}

	/**
	 * 登出
	 */
	public function signout()
	{
		$result = self::getService('user')->signout();

		if (true == $result['flg']) {
			header('Location:' . '/');
		}
	}

	/*
	 * 邮箱重复验证
	 */
	public function checkEmailExist()
	{
		$email = isset($_POST['email']) ? addslashes($_POST['email']) : null;
		$opts = array(
			'email' => $email,	
		);

		$result = self::getService('user')->checkEmailExist($opts);
		echo json_encode($result);
		exit;
	}

	/*
	 * 用户名重复验证
	 */
	public function checkUserExist()
	{
		$name = isset($_POST['name']) ? addslashes($_POST['name']) : null;
		$opts = array(
			'name' => $name,	
		);

		$result = self::getService('user')->checkUserExist($opts);
		echo json_encode($result);
		exit;
	}

	/*
	 * 忘记密码
	 */
	public function forgetPassword()
	{
		$template = 'forgetpassword.html';

		$smarty = self::getSmarty();
		$smarty->display($template);
	}

	/*
	 * 重置密码
	 */
	public function resetPassword()
	{
		$email = isset($_POST['email']) ? addslashes($_POST['email']) : null;
		$opts = array(
			'email' => $email,	
		);

		$result = self::getService('user')->resetPassword($opts);
		if ($result['flg'] == true) {
			$template = 'passwordsend.html';
			$smarty = self::getSmarty();
			$smarty->assign('email', $email);
			$smarty->display($template);
		}
	}

	/*
	 * 重置密码
	 */
	public function resetPasswd()
	{
		$key = array('name', 'code');
		$params = getUrlParam($key);

		$opts = array(
			'name' => $params['name'],	
			'code' => $params['code'],
		);
		$result = self::getService('user')->checkToken($opts);

		$smarty = self::getSmarty();
		if ($result['flg'] == true) {
			$template = 'passwordreset.html';
			$smarty->assign('name', $params['name']);
			$smarty->display($template);
		} else {
		
		}
	}

	/*
	 * 重置密码
	 */
	public function passwordReset()
	{
		$name = isset($_POST['name']) ? addslashes($_POST['name']) : null;
		$password = isset($_POST['password']) ? addslashes($_POST['password']) : null;
		$confirmpassword = isset($_POST['confirmpassword']) ? addslashes($_POST['confirmpassword']) : null;
		$opts = array(
			'name' => $name,
			'password' => $password,
			'confirmpassword' => $confirmpassword,	
		);

		$result = self::getService('user')->passwordReset($opts);
		dump($result);
		if ($result['flg'] == true) {
			header('location: '. '/signin');
		}
	}

	/**
	 * 邮箱验证
	 */
	//public function active()
	//{
	//	$request_uri = isset($_SERVER['REQUEST_URI']) ? addslashes($_SERVER['REQUEST_URI']) : null;
	//	$request_uri = preg_split("/\?/", $request_uri);
	//	$query_string = $request_uri[1];
	//	$query_string = explode('&', $query_string);
	//	$name = '';
	//	$code = '';
	//	foreach($query_string as $key => $val) {
	//		$params = explode('=', $val);
	//		if ('name' == $params[0]) {
	//			$name = $params[1];
	//		}
	//		if ('code' == $params[0]) {
	//			$code = $params[1];
	//		}
	//	}

	//	$opts = array(
	//		'name' => $name,
	//		'code' => $code,	
	//	);

	//	$result = self::getService('user')->active($opts);

	//	if (true == $result['flg']) {
	//		$template = 'active_success.html';
	//		$smarty = self::getSmarty();	
	//		$smarty->display($template);
	//	} else {
	//		$template = 'active_fail.html';
	//		$smarty = self::getSmarty();	
	//		$smarty->display($template);
	//	}
	//}

}
?>
