<?php
/*
 * 功能：公共函数
 * author: warpath
 * date: 2013年 05月 02日 星期四 19:37:29 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */

/*
 * deprecated   判断是否是字符
 * @param $str  参数
 * @return      bool
 */
function isCharacters($str)
{
	if (preg_match('/^\w+$/', $str)) {
		return true;
	} else {
		return false;
	}
}

/*
 * deprecated   判断是否符合长度限制
 * @param $str  参数
 * @return      bool
 */
function checkLengthLimit($min, $max, $str)
{
	if (mb_strlen($str, 'UTF-8') > $max || mb_strlen($str, 'UTF-8') < $min) {
		return false;
	} else {
		return true;
	}
}

/**
 * 验证邮箱格式
 *
 * @param string $email
 * @return boolean
 */
function checkEmail($email){
	return preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',$email);
}

/**
* 计算字符串的长度（汉字按照两个字符计算）
* @param   string      $str        字符串
* @return  int
*/
function myStrLen($str){
	$length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));

	if ($length){
		return strlen($str) - $length + intval($length / 3) * 2;
	}else{
		return strlen($str);
	}
}


/*
 * 打印调试
 * @param mixed
 */
function dump($mixed)
{
	echo '<pre>';
	var_dump($mixed);
	echo '</pre>';
}

function create_password($pw_length = 8)
{
	$randpwd = '';
	for ($i = 0; $i < $pw_length; $i++) 
	{
		$randpwd .= chr(mt_rand(33, 126));
	}
	return $randpwd;
} 

/*
 * 显示时间
 * @param int $time
 * @return str $str
 */
function timing($time)
{
	$curtime = time();
	$differTime = $curtime - $time;
	$str = '';
	if ($differTime < 60) {
		$str = "just now";
	}elseif ($differTime < 60*60) {
		$differTime = (int)($differTime/60);
		$str = "$differTime minutes ago";
	} elseif ($differTime >= 60*60 && $differTime < 60*60*24) {
		$differTime = (int)($differTime/(60*60));
		$str = "$differTime hours ago";
	} elseif ($differTime >= 60*60*24 && $differTime < 60*60*24*7) {
		$differTime = (int)($differTime/(60*60*7));
		$str = "$differTime days ago";
	} elseif ($differTime >= 60*60*24*30 && $differTime < 60*60*24*30*12){
		$differTime = (int)($differTime/(60*60*24*30));
		$str = "$differTime months ago";
	} elseif ($differTime > 60*60*24*30*12) {
		$differTime = (int)($differTime/(60*60*24*30*12));
		$str = "$differTime years ago";
	}
	return $str;
}

//加密上一个页面传递过来的加密地址参数
function encryptionUrl($url) {
	return urlencode(base64_encode($url));
}

//解析上一个页面传递过来的加密参数的地址
function analyticUrl($iu)
{
	return urldecode(base64_decode($iu));
}

//解析url参数
function getUrlParam($key) {
	$result = array();

	$request_uri = $_SERVER['REQUEST_URI'];
	$request_uri = preg_split("/\?/", $request_uri);
	if (isset($request_uri[1])) {
		$query_string = $request_uri[1];
		$query_string = explode('&', $query_string);
		if(!empty($query_string)) {
			foreach($query_string as $val) {
				$params = explode('=', $val);
				if (in_array($params[0], $key)) {
					$result[$params[0]] = $params[1];
				}
			}
		}
	}
	
	return $result;
}

/**
 * 转换特殊字符
 *
 * @access      public
 * @param 		$string   字符串
 * @return string
 **/
function safeReplace($string)
{
	$string = str_replace('%20','',$string);
	$string = str_replace('%27','',$string);
	$string = str_replace('*','',$string);
	$string = str_replace('"','&quot;',$string);
	$string = str_replace("'",'',$string);
	$string = str_replace("\"",'',$string);
	$string = str_replace('//','',$string);
	$string = str_replace(';','',$string);
	$string = str_replace('<','&lt;',$string);
	$string = str_replace('>','&gt;',$string);
	$string = str_replace('(','',$string);
	$string = str_replace(')','',$string);
	$string = str_replace("{",'',$string);
	$string = str_replace('}','',$string);
	return $string;
}

//字符串转义
function escapeQuotes($string, $islike = false)
{
	if (!get_magic_quotes_gpc()) {
		$string = addslashes($string);
	}

	if($islike) {//需要转义%,_的处理
		$string = strtr($string, array('%'=>'\%', '_'=>'\_')); 
	}

	return $string;
}
?>
