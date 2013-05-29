<?php
/**
 * 功能：用户业务逻辑层
 * author: warpath
 * date:2013年 05月 02日 星期四 05:39:21 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class User extends Model
{
	/*
	 * 登录
	 * @param array $opts
	 * @return array $result
	 */
	public function login($opts)
	{
		$result = array(
			'flg' => false,
		);

//		$flag = self::getValidate('login')->check($opts);
//		if (true !== $flag) {
//			$result['data'] = $flag;
//			return $result;
//		}

		$username = $opts['name'];
		$password = $opts['password'];
//		$password = md5($password.'camaro');
//		$userinfo = self::getDao('user')->getRow('Id,UserName,Password', "Username = '$username' and Isvalid = 1");

		if ($username == 'admin' && $password == '123456') {
			setcookie('signin', 1, time()+60*60*24*30, '/', 'breakfate.com');
			$result['flg'] = true;
		} else {
			$result['data'] = 'user not exists';
		}

		return $result;
	}

	/*
	 * 注册
	 * @param array $opts
	 * @return array $result
	 */
	public function signup($opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('signup')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		}
		$opts['password'] = md5($opts['password'].'camaro');//密码加盐

		$params = array(
			'UserName' => $opts['name'],	
			'Password' => $opts['password'],
			'Email'    => $opts['email'],
			'Isvalid'  => 1,//未激活
			'AddTime'  => time(),
		);
		$insertId = self::getDao('user')->create($params);

		if ($insertId > 0) {
			//user_base表
			$params = array(
				'Id' => $insertId,	
				'Avatars' => '/2013/05/2ecfb9c7310525a6b9ad31f755184a3c8.jpg',//默认头像
				'IsShowHabit' => 1,
				'IsShowMature' => 1,
				'IsHelpOthers' => 1,
			);
			self::getDao('userbasic')->create($params);

			$result['flg'] = true;
			$result['userId'] = $insertId;
		}

		return $result;
	}

	/**
	 * 登出
	 */
	public function signout()
	{
		$result = array(
			'flg' => false,	
		);

		if (isset($_COOKIE['userId'])) {
			setcookie('userId', false, time()-3600, '/', 'breakfate.com');
			$result['flg'] = true;
		}

		return $result;
	}


	/**
	 * 获得用户信息
	 * @param int $userId
	 * @return array
	 */
	public function getUserInfo($userId) 
	{
		if (!is_numeric($userId)) {
			return array();
		}

		return self::getDao('user')->join('u.*,b.*', 'user as u left join user_basic as b on u.Id = b.Id', "u.Id = $userId and u.Isvalid != 0");
	}

	/**
	 * 用户激活
	 * @param array $opts
	 * @return array
	 */
	public function active($opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('active')->check($opts);
		if ($flag !== true) {
			$result['data'] = $flag;
			return $result;
		}
		$userinfo = self::getService('user')->getUserInfoByName($opts['name']);
		if (!empty($userinfo)) {
			if (1 == $userinfo['Isvalid']) {
				$result['data'] = 2;//用户已经激活
			} elseif(0 == $userinfo['Isvalid']) {
				$result['data'] = 4;//用户已删除
			}else {
				$code = md5($userinfo['AddTime']);
				if ($code !== $opts['code']) {
					$result['data'] = 3;//时间错误
				} else {
					//激活
					$params = array(
						'Isvalid' => 1,
						'UpdateTime' => time(),	
					);
					$rowCount = self::getDao('user')->update($params, "Id = ".$userinfo['Id']);
					if (1 == $rowCount) {
						$result['flg'] = true;
					}
				}
			}
		} else {
			$result['data'] = 1;//用户信息不存在
		}

		return $result;
	}

	/*
	 * 用户信息设置
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function seting($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('seting')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		}

		$userinfo = self::getService('user')->getUserInfo($userId);
		if (!empty($userinfo)) {
			$params = array(
				'RealName' => $opts['realName'],	
				'IsShowRname' => $opts['isShowRname'],
				'Province' => $opts['province'],
				'City' => $opts['city'],
			);
			$rowCount = self::getDao('userbasic')->update($params, "Id = $userId");
			if (1 == $rowCount) {
				$user = array(
					'UpdateTime' => time(),	
				);
				if (isset($opts['userName']) && $opts['userName'] !== null || isset($opts['email']) && $opts['email'] !== null) {
					if ($opts['userName'] !== null) {
						$user['UserName'] = $opts['userName'];
					}
					if ($opts['email'] !== null) {
						$user['Email'] = $opts['email'];
					}
				}
				$rowCount = self::getDao('user')->update($user, "Id = $userId and Isvalid = 1");
				if (1 == $rowCount) {
					$result['flg'] = true;
				}
			}
		} else {
			$result['data'] = 'illegality operate';
		}

		return $result;
	}

	/**
	 * 用户隐私设置
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function privacy($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('privacy')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		}

		$userinfo = self::getService('user')->getUserInfo($userId);
		if (!empty($userinfo)) {
			$params = array(
				'IsShowHabit' => $opts['isShowHabit'],	
				'IsShowMature' => $opts['isShowMature'],
				'IsHelpOthers' => $opts['isHelpOthers'],
			);
			$rowCount = self::getDao('userbasic')->update($params, "Id = $userId");
			if (1 == $rowCount) {
				$params = array(
					'UpdateTime' => time(),	
				);
				$rowCount = self::getDao('user')->update($params, "Id = $userId and Isvalid = 1");
				if (1 == $rowCount) {
					$result['flg'] = true;
				}
			}
		} else {
			$result['data'] = 'illegality operate';
		}

		return $result;
	}

	/*
	 * 用户密码设置
	 * @param int $userId
	 * @param array $opts
	 * @return array
	 */
	public function updatePassword($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('password')->check($opts);
		if ($flag !== true) {
			$result['data'] = $flag;
			return $result;
		}

		$userinfo = self::getService('user')->getUserInfo($userId);
		if (!empty($userinfo)) {
			if (md5($opts['oldPasswd'].'camaro') == $userinfo['Password']) {
				$params = array(
					'Password' => md5($opts['newPasswd'].'camaro'),
					'UpdateTime' => time(),
				);
				$rowCount = self::getDao('user')->update($params, "Id = $userId and Isvalid = 1");
				if ($rowCount == 1) {
					$result['flg'] = true;
				}
			} else {
				$result['data'] = '旧密码有错误';
			}
		} else {
			$result['data'] = 'illegality operate';
		}
		return $result;
	}

	/*
	 * 获取推荐用户
	 * @param int $userId
	 * @return array $result
	 */
	public function getRecommendUser($userId)
	{
		$result = array();
		if (!is_numeric($userId)) {
			return $result;
		}

		$userTags = self::getDao('usertag')->getAll('TagId', "UserId= $userId and IsRecommend = 1 and Isvalid = 1");
		if (!empty($userTags)) {
			$tags = array();
			foreach($userTags as $val) {
				$alikeUser = self::getDao('user')->join('u.*,t.TagId,b.Avatars', "user as u join user_tag as t on u.Id = t.UserId join user_basic as b on u.Id = b.Id", " t.TagId = ".$val['TagId']." and t.UserId != $userId");
				if(!empty($alikeUser)) {
					$result[$alikeUser['Id']] = $alikeUser;
					$result[$alikeUser['Id']]['tag'][] = $val['TagId'];
				}
			}
		} 

		return $result;
	}

	/*
	 * 根据用户名获取用户信息
	 * @param str $username
	 * @return array $result
	 */
	public function getUserInfoByName($username)
	{
		return self::getDao('user')->join('u.*,b.*', 'user as u left join user_basic as b on u.Id = b.Id', "u.UserName = '$username' and u.Isvalid != 0");
	}

	/*
	 *
	 * 获取用户关注tag
	 * @param int $userId
	 * @return array $result
	 */
	public function getUserTags($userId)
	{
		if(!is_numeric($userId)) {
			return array();
		}

		$userTags = self::getDao('usertag')->getAll('*', "UserId = $userId and Isvalid = 1");
		if (!empty($userTags)) {
			foreach($userTags as $key => $val) {
				$tag =  self::getDao('tag')->getRow('Name', "Id = ".$val['TagId']." and Isvalid = 1");
				$userTags[$key]['tagName'] = $tag['Name'];
			}
		}

		return $userTags;
	}

	/*
	 * 删除用户关注标签
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function delTag($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('delTag')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		} 

		$params = array(
			'Isvalid' => 0,	
			'UpdateTime' => time(),
		);
		$rowCount = self::getDao('usertag')->update($params, "UserId = $userId and TagId = ".$opts['userTagId']). " and Isvalid = 1";
		if (1 == $rowCount) {
			$result['flg'] = true;
		}

		return $result;
	}

	/*
	 * 用户标签用于推荐 
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function tagRecommend($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('delTag')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		} 

		$params = array(
			'IsRecommend' => 1,	
			'UpdateTime' => time(),
		);
		$rowCount = self::getDao('usertag')->update($params, "UserId = $userId and TagId = ".$opts['userTagId']). " and Isvalid = 1";
		if (1 == $rowCount) {
			$result['flg'] = true;
		}

		return $result;

	}

	/*
	 * 用户标签不用于推荐 
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function tagNotRecommend($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('delTag')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		} 

		$params = array(
			'IsRecommend' => 0,	
			'UpdateTime' => time(),
		);
		$rowCount = self::getDao('usertag')->update($params, "UserId = $userId and Id = ".$opts['userTagId']. " and Isvalid = 1");
		if (1 == $rowCount) {
			$result['flg'] = true;
		}

		return $result;

	}

	/*
	 * 获取一组用户
	 * @param array $ids
	 * @return array $result
	 */
	public function getUserByIds($ids)
	{
		if (!is_array($ids)) {
			return array();
		}

		$ids = implode(',', $ids);
		$result = self::getDao('user')->join('u.*,b.*', "user as u join user_basic as b on u.Id = b.Id", "u.Id in ($ids) and u.Isvalid = 1", 'many');
		return $result;
	}

	/*
	 * 删除帐号
	 * @param int $userId
	 * @param array $opts
	 */
	public function delaccount($userId, $opts)
	{
		$result = array(
			'flg' => false	
		);

		$flag = self::getValidate('delaccount')->check($opts);
		if ($flag !== true) {
			$result['data'] = $flag;
			return $result;
		}
		
		$userinfo = self::getService('user')->getUserInfo($userId);
		if (!empty($userinfo)) {
			if ($opts['userName'] == $userinfo['UserName']) {
				$params = array(
					'Isvalid' => 0,
					'UpdateTime' => time()	
				);
				$rowCount = self::getDao('user')->update($params, "Id = $userId and Isvalid = 1");
				if ($rowCount == 1) {
					$result['flg'] = true;
				}
			} else {
				$result['data'] = '你输入的用户名错误';
			}
		} else {
			$result['data'] = 'illegality operate';
		}

		return $result;
	}

	/*
	 * 检查邮箱是否存在
	 * @param array $opts
	 * @return array $result
	 */
	public function checkEmailExist($opts)
	{
		$result = array(
			'flg' => false	
		);

		$flag = self::getValidate('checkemailexist')->check($opts);
		if ($flag !== true) {
			$result['data'] = $flag;
			return $result;
		}
		$user = self::getDao('user')->getCount("Email = '".$opts['email']."' and Isvalid = 1");
		if ($user > 0) {
			$result['flg'] = true;
		}

		return $result;
	}

	/*
	 * 检查用户名是否存在
	 * @param array $opts
	 * @return array $result
	 */
	public function checkUserExist($opts)
	{
		$result = array(
			'flg' => false	
		);

		$flag = self::getValidate('checkuserexist')->check($opts);
		if ($flag !== true) {
			$result['data'] = $flag;
			return $result;
		}
		$user = self::getService('user')->getUserInfoByName($opts['name']);
		if (!empty($user)) {
			$result['flg'] = true;
		}

		return $result;
	}

	/*
	 * 重设密码
	 * @param array $opts
	 * @return array $result
	 */
	public function resetPassword($opts)
	{
		$result = array(
			'flg' => false	
		);

		$flag = self::getValidate('checkemailexist')->check($opts);
		if ($flag !== true) {
			$result['data'] = $flag;
			return $result;
		}

		$user = self::getDao('user')->getRow('*', "Email = '".$opts['email']."' and Isvalid = 1");
		if (!empty($user)) {
			$res = self::getService('email')->sendResetPassword($user['UserName'], $opts['email']);
			if($res['flg'] = true) {
				$params = array(
					'Email' => $opts['email'],	
					'Token' => $res['token'],
					'Period' => $res['period'],
				);
				$insertId = self::getDao('sendemail')->create($params);
				if ($insertId > 0) {
					$result['flg'] = true;
				}
			}
		} else {
			$result['data'] = '没有此邮箱的注册信息';
		}

		return $result;
	}

	/*
	 * 检测token
	 * @param array $opts
	 */
	public function checkToken($opts)
	{
		$result = array(
			'flg' => false,	
		);

		$user = self::getService('user')->getUserInfoByName($opts['name']);
		if (!empty($user)) {
			$email = $user['Email'];
			$sendEmail = self::getDao('sendemail')->getRow('*', "Email = '$email' and Token = '".$opts['code']."'");
			if (!empty($sendEmail)) {
				if ($sendEmail['Period'] > time()) {
					$result['flg'] = true;
				} else {
					$result['data'] = '链接已过期';
				}
			}
		} else {
			$result['data'] = '没有此用户';
		}

		return $result;
	}

	/*
	 *
	 * 重置密码 
	 * @param array $opts
	 * @return array $result
	 */
	public function passwordReset($opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('passwordreset')->check($opts);
		if ($flag !== true) {
			$result['data'] = $flag;
			return $result;
		}

		$params = array(
			'Password' => md5($opts['password'].'camaro'),	
			'UpdateTime' => time(),
		);
		$rowCount = self::getDao('user')->update($params, "UserName = '".$opts['name']."' and Isvalid = 1");
		if ($rowCount == 1) {
			$result['flg'] = true;
		}

		return $result;
	}

	/*
	 * 获取所有用户信息
	 */
	public function getAllUser()
	{
		return self::getDao('user')->join('u.*,b.*', 'user as u left join user_basic as b on u.Id = b.Id', "u.Isvalid = 1", 'many');
	}

	/*
	 * 删除用户
	 */
	public function del($id)
	{
		$params = array(
			'Isvalid' => 0,
			'UpdateTime' => time(),	
		);
		return self::getDao('user')->update($params, "Id = $id and Isvalid = 1");
	}
}
?>
