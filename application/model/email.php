<?php
/*
 * 功能：邮件发送服务层
 * author: warpath
 * date:2013年 05月 03日 星期五 17:47:03 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class Email extends Model
{
	/*
	 * 注册邮件发送
	 * @param $str $name
	 * @param $str $email
	 */
	public function sendSignupEmail($name, $email)
	{
		$userinfo = self::getService('user')->getUserInfoByName($name);
		$code = md5($userinfo['AddTime']);
		$url = "http://www.breakfate.com/active?name=$name&code=$code";

		$mail = self::getEmailHandler();
		$mail->CharSet = "utf8";
		$mail->IsSMTP();
		$mail->SMTPDebug = 1;
		$mail->SMTPAuth = true;
		$mail->STMPSecure = 'ssl';
		$mail->Host = 'smtp.sina.cn';
		$mail->Port = 25;
		$mail->Username = 'breakfate@sina.cn';
		$mail->Password = 'sina2006341121';
		$mail->From = 'breakfate@sina.cn';
		$mail->FromName = 'breakfate';
		$mail->Subject = '恭喜你注册成功';
		$mail->AltBody = 'text/html';
		$mail->Body = "亲爱的,$name<br/>欢迎加入<strong>Breakfate</strong>。<br/>请点击下面的链接完成激活：$url<br/><h2>如果以上链接无法点击，请将上面的地址复制到你的浏览器（如IE）的地址栏进行帐号验证激活。</h2><br/>这是一封自动产生的邮件，请勿回复！";
		$mail->IsHTML(true);
		$mail->AddReplyTo('breakfate@sina.cn', 'breakfate');
		$mail->AddAddress($email, '');
		return $mail->send();
	}

	/*
	 * 重置密码邮件发送
	 * @param str $email
	 * @return array $res
	 */
	public function sendResetPassword($name, $email)
	{
		$result = array(
			'flg' => false,	
		);
		$time = time() + 60*60*24;
		$code = md5($time);
		$url = "http://www.breakfate.com/resetpasswd?name=$name&code=$code";

		$mail = self::getEmailHandler();
		$mail->CharSet = "utf8";
		$mail->IsSMTP();
		$mail->SMTPDebug = 1;
		$mail->SMTPAuth = true;
		$mail->STMPSecure = 'ssl';
		$mail->Host = 'smtp.sina.cn';
		$mail->Port = 25;
		$mail->Username = 'breakfate@sina.cn';
		$mail->Password = 'sina2006341121';
		$mail->From = 'breakfate@sina.cn';
		$mail->FromName = 'breakfate';
		$mail->Subject = '请重置你的密码';
		$mail->AltBody = 'text/html';
		$mail->Body = "请点击下面的链接重置密码：$url<br/><h2>此链接在24小时之内有效，超过24小时请重新申请。</h2><br/>这是一封自动产生的邮件，请勿回复！";
		$mail->IsHTML(true);
		$mail->AddReplyTo('breakfate@sina.cn', 'breakfate');
		$mail->AddAddress($email, '');
		$res = $mail->send();
		if ($res == true) {
			$result['flg'] = true;
			$result['token'] = $code;
			$result['period'] = $time;
		}

		return $result;
	}

}	
?>
