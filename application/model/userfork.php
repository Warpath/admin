<?php
/**
 * 功能：用户关注好友
 * author: warpath
 * date:2013年 05月 06日 星期一 06:03:20 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class Userfork extends Model
{
	/*
	 * 判断是否已经关注
	 * @param int $curUser
	 * @param int $userId
	 * @return bool
	 */
	public function isFork($curUser, $userId)
	{
		if (!is_numeric($userId)) {
			return false;
		}
		$result = self::getDao('userfork')->getRow('Id', "UserId = $curUser and ForkerId = $userId and Isvalid = 1");
		if (!empty($result)) {
			return $result;
		} else {
			return false;
		}
	}

	/*
	 * 获得关注用户的用户
	 * @param int $userId
	 * @return array $result
	 */
	public function getForkedUsers($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		return self::getDao('userfork')->getAll('*', "UserId = $userId and Isvalid = 1");
	}

	/*
	 * 获得用户关注用户
	 * @param int $userId
	 * @return array $result
	 */
	public function getUserForks($userId)
	{
		$result = array();
		$users = self::getDao('userfork')->getAll('ForkerId', "UserId = $userId and Isvalid = 1");;
		if (!empty($users)) {
			foreach($users as $key => $val) {
				$result[] = self::getDao('user')->join('u.Id,u.UserName,b.Avatars', 'user as u left join user_basic as b on u.Id = b.Id', "u.Id = ".$val['ForkerId']." and u.Isvalid != 0");
			}
		}
		return $result;
	}


	/**
	 * 关注好友
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function fork($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$userinfo = self::getService('user')->getUserInfo($opts['forkerId']);
		if (!empty($userinfo)) {
			$params = array(
				'UserId' => $userId,
				'ForkerId' => $opts['forkerId'],	
				'AddTime' => time(),
			);
			$insertId = self::getDao('userfork')->create($params);

			if ($insertId > 0) {
				$result['flg'] = true;
			}
		} else {
			$result['data'] = 'user not exist';
		}

		return $result;
	}

	/*
	 * 取消关注
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function cancelFork($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$userfork = self::getDao('userfork')->getRow('*', "Id = ".$opts['Id']." and UserId = $userId and Isvalid = 1");
		if (!empty($userfork)) {
			$params = array(
				'Isvalid' => 0,
				'UpdateTime' => time(),	
			);
			$rowCount = self::getDao('userfork')->update($params, "Id = ".$opts['Id']);
			if (1 == $rowCount) {
				$result['flg'] = true;
			}
		} else {
			$result['data'] = 'illegality operate';
		}

		return $result;
	}

}
?>
