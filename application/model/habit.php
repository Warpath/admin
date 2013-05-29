<?php
/**
 * 功能：习惯服务层
 * author: warpath
 * date:2013年 05月 03日 星期五 00:45:31 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class Habit extends Model
{
	/**
	 * 获得用户习惯
	 * @param int $userId
	 * @return array $result
	 */
	public function getHabit($userId)
	{
		$result = array();
		if (!is_numeric($userId)) {
			return $result;
		}

		return self::getDao('habit')->getAll('*', "UserId = $userId and Isvalid = 1");
	}

	/*
	 * 根据Id获得习惯详情
	 * @param int $habitId
	 * @return array $result
	 */
	public function getHabitDetail($habitId)
	{
		if (!is_numeric($habitId)) {
			return array();
		}

		return self::getDao('habit')->getRow('*', "Id = $habitId ");
	}

	/*
	 * 获得习惯记录
	 * @param int $habitId
	 * @return array $result
	 */
	public function getHabitRecord($habitId)
	{
		if (!is_numeric($habitId)) {
			return array();
		}

		$habit = self::getService('habit')->getHabitDetail($habitId);

		//习惯计划次数
		$planCount = 0;
		global $period;
		if ($habit['Period'] == $period[0]['day']) {
			$planCount = (int)(($habit['EndTime'] - $habit['StartTime']) / 86400);
		} elseif ($habit['Period'] == $period[0]['week']) {
			$planCount = (int)(($habit['EndTime'] - $habit['StartTime']) / (86400 * 7));
		}
		global $habitOperate;
		$completeCount = self::getDao('habitoperate')->getCount("HabitId = $habitId and Operate = ".$habitOperate[0]['wancheng']);
		$bufenCount = self::getDao('habitoperate')->getCount("HabitId = $habitId and Operate = ".$habitOperate[0]['bufenwancheng']);
		$weiwanchengCount = self::getDao('habitoperate')->getCount("HabitId = $habitId and Operate = ".$habitOperate[0]['weiwancheng']);
		$result = array(
			0 => array(
				'complete' => $completeCount,
				'bufenCount' => $bufenCount,
				'weiwanchengCount' => $weiwanchengCount,	
			),
			1 => array(
				'complete' => $completeCount > 0 ? (int)($completeCount/$planCount*100) : 0,	
				'bufenwancheng' => $bufenCount > 0 ? (int)($bufenCount/$planCount*100) : 0,	
				'weiwancheng' => $weiwanchengCount > 0 ? (int)($weiwanchengCount/$planCount*100) : 0,	
			),
		);
		return $result;
	}

	/**
	 * 获得用户公开习惯
	 * @param int $userId
	 * @return array $result
	 */
	public function getPublicHabit($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		$habit = self::getDao('habit')->getAll('*', "UserId = $userId and IsPrivate = 0 and Isvalid = 1");
		if (isset($habit)) {
			foreach($habit as $key => $val) {
				$habit[$key]['record'] = self::getService('habit')->getHabitRecord($val['Id']);
			}
		}

		return $habit;
	}

	/**
	 * 编辑用户习惯
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function editHabit($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);
		$flag = self::getValidate('habit')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		}

		$habit = self::getDao('habit')->getRow('*', "Id = ".$opts['habitId']." and UserId = $userId and Isvalid = 1");
		if (!empty($habit)) {
			//TODO::正在养成习惯检查
			$where = "Id = ".$opts['habitId'];
			$params = array(
				'Title' => $opts['title'],	
				'Content' => $opts['content'],
				'Status' => $opts['status'],
				'isPrivate' => $opts['isPrivate'],
				'period' => $opts['period'],
				'rate' => $opts['rate'],
				'StartTime' => strtotime($opts['starttime']),
				'EndTime' => strtotime($opts['endtime']),
				'UpdateTime' => time(),
			);

			$rowCount = self::getDao('habit')->update($params, $where);

			if (1 == $rowCount) {
				global $event;
				$params = array(
					'UserId' => $userId,
					'Aid' => $habit['Id'],	
					'EventType' => $event[0]['edithabit'],
					'Content' => '',
					'IsShow' => $opts['isPrivate'] == 1 ? 0 : 1,
					'AddTime' => time(),
				);
				$insertId = self::getDao('event')->create($params);
				if ($insertId > 0) {
					$result['flg'] = true;
				}
			}
		} else {
			$result['data'] = 'illegality operate!';
		}

		return $result;
		
	}

	/**
	 * 添加用户习惯
	 * @param int $userId
	 * @param array $opts
	 * @return arrry $result
	 */
	public function addHabit($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);
		$flag = self::getValidate('habit')->check($opts);
		if (true !== $flag) {
			return $result;
		}

		global $habitStatus;
		$habitCount = self::getDao('habit')->getCount("UserId = $userId and Isvalid = 1 and Status = " . $habitStatus[0]['zhengzaiyangcheng']);

		if ($opts['status']==$habitStatus[0]['zhengzaiyangcheng'] && $habitCount >= 2) {
			$result['data'] = '我们建议正在养成的习惯不超过两个';
			return $result;
		} else {
			$userinfo = self::getService('user')->getUserInfo($userId);
			if (0 == $userinfo['IsShowHabit']) {//如果用户设置不公开习惯，所有习惯为不公开
				$opts['isPrivate'] = 1;
			}
			$params = array(
				'UserId' => $userId,
				'Title' => $opts['title'],	
				'Content' => $opts['content'],
				'Period' => $opts['period'],
				'rate' => $opts['rate'],
				'isPrivate' => $opts['isPrivate'],
				'StartTime' => strtotime($opts['starttime']),
				'EndTime' => strtotime($opts['endtime']),
				'Status' => $opts['status'],
				'AddTime' => time(),
			);

			$insertId = self::getDao('habit')->create($params);

			if ($insertId > 0) {
				global $event;
				$params = array(
					'UserId' => $userId,
					'Aid' => $insertId,
					'EventType' => $event[0]['createhabit'],
					'Content' => '',
					'IsShow' => $opts['isPrivate'] == 1 ? 0 : 1,
					'Isvalid' => 1,
					'AddTime' => time(),
				);
				$eventId = self::getDao('event')->create($params);
				if ($eventId > 0) {
					$result['flg'] = true;
				}
			}

		}

		return $result;
	}

	/**
	 * 获取用户习惯统计信息
	 * @param int $userId
	 * @return array $result
	 */
	public function getHabitCount($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		global $habitStatus;
		$result['ongoing'] = self::getDao('habit')->getCount("UserId = $userId and Status = ".$habitStatus[0]['zhengzaiyangcheng']." and Isvalid =1");
		$result['target'] = self::getDao('habit')->getCount("UserId = $userId and Status = ".$habitStatus[0]['mubiaoxiguan']." and Isvalid =1");
		$result['existing'] = self::getDao('habit')->getCount("UserId = $userId and Status = ".$habitStatus[0]['yiyouxiguan']." and Isvalid =1");
		return $result;
	}

	/**
	 * 获得用户习惯总数
	 * @param int $userId
	 * @return array $result
	 */
	public function getHabitsCount($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		return self::getDao('habit')->getCount("UserId = $userId and Isvalid = 1");
	}

	/**
	 * 获得用户正在执行习惯
	 * @param int $userId
	 * @return array $result
	 */
	public function getDoingHabit($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		global $habitStatus;
		return self::getDao('habit')->getAll('*', "UserId = $userId and Status = ".$habitStatus[0]['zhengzaiyangcheng']."  and Isvalid = 1");
	}

	/**
	 * 获得用户目标习惯
	 * @param int $userId
	 * @return array $result
	 */
	public function getAimHabit($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		global $habitStatus;
		return self::getDao('habit')->getAll('*', "UserId = $userId and Status = ".$habitStatus[0]['mubiaoxiguan']." and Isvalid = 1");
	}

	/**
	 * 获得用户已有习惯
	 * @param int $userId
	 * @return array $result
	 */
	public function getExistingHabit($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		global $habitStatus;
		return self::getDao('habit')->getAll('*', "UserId = $userId and Status = ".$habitStatus[0]['yiyouxiguan']."  and Isvalid = 1");
	}

	/**
	 * 获得用户搁浅习惯
	 * @param int $userId
	 * @return array $result
	 */
	public function getAgroundHabit($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		global $habitStatus;
		return self::getDao('habit')->getAll('*', "UserId = $userId and Status = ".$habitStatus[0]['geqianxiguan']." and IsPrivate = 0 and Isvalid = 1");
	}

	/**
	 * 修改习惯状态
	 * @param int $userId
	 * @param array $opts
	 * @reutrn array $result
	 */
	public function editStatus($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('editHabitStatus')->check($opts);
		if (true !== $flag) {
			return $result;
		}

		$habit = self::getDao('habit')->getRow('*', "Id = ".$opts['Id']." and UserId = $userId and Isvalid = 1");
		if (!empty($habit)) {
			global $habitStatus;
			$habitCount = self::getDao('habit')->getCount("UserId = $userId and Isvalid = 1 and Status = " . $habitStatus[0]['zhengzaiyangcheng']);
			if ($opts['status'] == $habitStatus[0]['zhengzaiyangcheng'] && $habitCount >= 2) {
				$result['data'] = '我们建议正在养成的习惯不超过两个';
				return $result;
			} else {
				$params = array(
					'Id' => $opts['Id'],	
					'Status' => $opts['status'],
					'UpdateTime' => time(),
				);			
				$rowCount = self::getDao('habit')->update($params, "Id = " . $opts['Id']);
				if (1 == $rowCount) {
					global $event;
					$params = array(
						'UserId' => $userId,
						'Aid' => $habit['Id'],	
						'EventType' => $event[0]['changehabitstatus'],
						'Content' => $event[1][$opts['status']],	
						'IsShow' => $habit['IsPrivate'] == 1 ? 0 : 1,
						'AddTime' => time(),
					);
					$eventId = self::getDao('event')->create($params);
					if ($eventId > 0) {
						$result['flg'] = true;
					}
				}
			}
		} else {
			$result['data'] = 'illegality operate';
		}

		return $result;
	}

	/**
	 * 删除习惯
	 * @param int $userId
	 * @param int $id
	 * @return array $result
	 */
	public function delHabit($userId, $id)
	{
		$result = array(
			'flg' => false,	
		);
		if (!is_numeric($id)) {
			return $result;
		}

		$habit = self::getDao('habit')->getRow('*', "Id = ".$id." and UserId = $userId and Isvalid = 1");
		if (!empty($habit)) {
			$params = array(
				'Isvalid' => 0,
			);
			$rowCount = self::getDao('habit')->update($params, "Id = $id");
			if (1 == $rowCount) {
				global $event;
				$params = array(
					'UserId' => $userId,
					'Aid' => $habit['Id'],	
					'EventType' => $event[0]['delhabit'],
					'Content' => '',	
					'IsShow' => $habit['IsPrivate'] == 1 ? 0 : 1,
					'AddTime' => time(),
				);
				$eventId = self::getDao('event')->create($params);
				if ($eventId > 0) {
					$result['flg'] = true;
				}
			}
		} else {
			$result['data'] = 'illegality operate';
		}

		return $result;
	}


	/**
	 * 习惯记录
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function operate($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);
		$flag = self::getValidate('habitoperate')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		}

		$habit = self::getDao('habit')->getRow('Id,UserId,Status,IsPrivate', "Id = ".$opts['habitId']." and UserId = $userId and Isvalid = 1");
		if (!empty($habit)) {
			global $habitStatus;
			if ($habit['Status'] == $habitStatus[0]['zhengzaiyangcheng']) {
				global $habitOperate;
				$params = array(
					'HabitId' => $habit['Id'],				
					'Operate' => $opts['operate'],
					'AddTime' => time(),
				);
				$insertId = self::getDao('habitoperate')->create($params);
				if ($insertId > 0 ) {
					global $event;
					switch($opts['operate']) {
						case '10001':
							$eventType = $event[0]['completehabit'];
							break;
						case '10002':
							$eventType = $event[0]['bufenwanchengxiguan'];
							break;
						case '10003':
							$eventType = $event[0]['weiwanchengxiguan'];
							break;
					}
					$params = array(
						'UserId' => $userId,
						'Aid' => $habit['Id'],	
						'EventType' => $eventType,
						'Content' => '',	
						'IsShow' => $habit['IsPrivate'] == 1 ? 0 : 1,
						'AddTime' => time(),
					);
					$eventId = self::getDao('event')->create($params);
					if ($eventId > 0) {
						$result['flg'] = true;
					}
				}
			} else {
				$result['data'] = 'illegality operate';
			}
		} else {
			$reuslt['data'] = 'illegality operate';
		}

		return $result;
	}

	/**
	 * 用户鼓励
	 * @param array $opts
	 * @return array $result
	 */
	public function encourage($opts)
	{
		$result = array(
			'flg' => false,	
		);
		
		$flag = self::getValidate('encourage')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		}
		$userinfo = self::getService('user')->getUserInfo($opts['userId']);

		if (!empty($userinfo)) {
			$habit = self::getDao('habit')->getRow('UserId', "Id = ".$opts['id']." and Isvalid = 1");
			if (!empty($habit)) {
				$params = array(
					'HabitId' => $opts['id'],
					'UserId' => $opts['userId'],	
					'Encourage' => $opts['encourage'],
				);
				$insertId = self::getDao('habitencourage')->create($params);

				if ($insertId > 0) {
					$result['flg'] = true;
				}
			} else {
				$result['data'] = 'habit not exist';
			}
		} else {
			$result['data'] = 'user not exist';
		}

		return $result;
	}

	/**
	 * 习惯搭档
	 * @param int $userId
	 * @param array $opts
	 * @return $result
	 */
	public function partner($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		if (!is_numeric($opts['habitId'])) {
			return array();
		}
		$habit = self::getDao('habit')->getRow('*', "Id = ".$opts['habitId']." and Isvalid = 1");

		if (!empty($habit)) {
			if ($userId !== $habit['UserId']) {
				$firsthand = self::getService('user')->getUserInfo($habit['UserId']);
				
				//clone habit
				global $habitStatus;
				$params = array(
					'UserId' => $userId,
					'Title' => $habit['Title'],	
					'Content' => $habit['Content'],
					'Period' => $habit['Period'],
					'Rate' => $habit['Rate'],
					'Status' => $habitStatus[0]['mubiaoxiguan'],
					'StartTime' => $habit['StartTime'],
					'EndTime' => $habit['EndTime'],
					'IsPrivate' => $habit['IsPrivate'],
					'IsClone' => 1,
					'CloneHabitId' => $habit['Id'],
					'AddTime' => time(),
				);
				$insertId = self::getDao('habit')->create($params);

				if ($insertId > 0) {
					$params = array(
						'HabitId' => $habit['Id'],	
						'PartnerId' => $userId,
						'AddTime' => time(),
					);
					$res = self::getDao('habitpartner')->create($params);
					if ($res > 0) {
						global $event;
						$params = array(
							'UserId' => $userId,
							'Aid' => $insertId,
							'EventType' => $event[0]['clonehabit'],
							'Content' => $firsthand['UserName'],
							'IsShow' => $habit['IsPrivate'] == 1 ? 0 : 1,
							'AddTime' => time(),
						);
						$eventId = self::getDao('event')->create($params);
						if ($eventId > 0) {
							$result['flg'] = true;
						}
					}
				}
			} else {
				$result['data'] = '不能自己克隆';
			}
		} else {
			$result['data'] = 'habit not exist';
		}

		return $result;
	}

	/*
	 * 记录习惯故事
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function addStory($userId, $opts)
	{
		$result = array(
			'flg' => false,
		);

		$habit = self::getDao('habit')->getRow('*', "Id = ".$opts['habitId']." and UserId = $userId and Isvalid = 1");
		if (!empty($habit)) {
			$params = array(
				'HabitId' => $opts['habitId'],	
				'Story' => $opts['story'],
				'AddTime' => time()
			);
			$insertId = self::getDao('habitstory')->create($params);
			if ($insertId > 0) {
				global $event;
				$params = array(
					'UserId' => $userId,
					'Aid' => $insertId,	
					'EventType' => $event[0]['habitstory'],
					'Content' => $opts['story'],
					'IsShow' => $habit['IsPrivate'] == 1 ? 0 : 1,
					'AddTime' => time(),
				);
				$eventId = self::getDao('event')->create($params);
				if ($eventId > 0) {
					$result['flg'] = true;
				}
			}
		} else {
			$result['data'] = 'illegality operate';
		}

		return $result;
	}

	/*
	 * 根据Id获取习惯数组
	 * @param array $ids
	 * @return array $result
	 */
	public function getHabitByIds($ids)
	{
		if (!is_array($ids)) {
			return array();
		}

		$ids = implode(',', $ids);
		$result = self::getDao('habit')->getAll('*', "Id in ($ids) and IsPrivate = 0 and Isvalid = 1");
		foreach($result as $key => $val) {
			$result[$key]['userinfo'] = self::getService('user')->getUserInfo($val['UserId']);
		}

		return $result;
	}
}
?>
