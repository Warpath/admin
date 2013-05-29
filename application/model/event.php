<?php
/**
 * 功能：事件服务层
 * author: warpath
 * date:2013年 05月 02日 星期四 23:41:17 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class Event extends Model
{
	/**
	 * 获取用户feed
	 * @param int $userId
	 * @return array $result
	 */
	public function getFeed($userId)
	{
		$result = array();
		if (!is_numeric($userId)) {
			return $result;
		}	
		
		//我关注的用户
		$forkerId = array();
		$myFocusUsers = self::getService('userfork')->getForkedUsers($userId);
		if (!empty($myFocusUsers)) {
			foreach($myFocusUsers as $key => $val) {
				$forkerId[] = $val['ForkerId'];
			}
		}
		$forkerId = implode(',', $forkerId);
		$result = self::getDao('event')->getAll('*', "UserId in ($forkerId) AND Isvalid = 1 and IsShow = 1 order by Id desc limit 10");
		global $event;
		foreach($result as $key=>$val) {
			$result[$key]['userinfo'] = self::getService('user')->getUserInfo($val['UserId']);
			if($val['EventType'] == $event[0]['createthanksgiving']) {
				$result[$key]['event'] = self::getService('thanksgiving')->getThanksgivingById($val['Aid']);
			} else if($val['EventType'] == $event[0]['createmature'] || $val['EventType'] == $event[0]['editmature'] || $val['EventType'] == $event[0]['invitemature'] || $val['EventType'] == $event[0]['harvest']|| $val['EventType'] == $event[0]['commentmature']){
				$mature = self::getService('mature')->getMatureById($val['Aid']);
				$result[$key]['event'] = $mature;
				if ($val['EventType'] == $event[0]['invitemature']) {
					$result[$key]['event']['invitee'] = self::getService('user')->getUserInfo($val['Content']);
				}
			} else {
				$result[$key]['event'] = self::getService('habit')->getHabitDetail($val['Aid']);
			}
		}

		return $result;
	}

	/*
	 * 获取更多的Feed
	 * @param int $userId
	 * @param int $lastmsg
	 * @return array $result
	 */
	public function getMoreFeed($userId, $lastmsg)
	{
		$result = array();
		if (!is_numeric($userId)) {
			return $result;
		}	
		
		//我关注的用户
		$forkerId = array();
		$myFocusUsers = self::getService('userfork')->getForkedUsers($userId);
		if (!empty($myFocusUsers)) {
			foreach($myFocusUsers as $key => $val) {
				$forkerId[] = $val['ForkerId'];
			}
		}
		$forkerId = implode(',', $forkerId);
		$result = self::getDao('event')->getAll('*', "UserId in ($forkerId) and Id < $lastmsg AND Isvalid = 1 and IsShow = 1 order by Id desc limit 10");
		global $event;
		foreach($result as $key=>$val) {
			$result[$key]['userinfo'] = self::getService('user')->getUserInfo($val['UserId']);
			if($val['EventType'] == $event[0]['createthanksgiving']) {
				$result[$key]['event'] = self::getService('thanksgiving')->getThanksgivingById($val['Aid']);
			} else if($val['EventType'] == $event[0]['createmature'] || $val['EventType'] == $event[0]['editmature'] || $val['EventType'] == $event[0]['invitemature'] || $val['EventType'] == $event[0]['harvest']|| $val['EventType'] == $event[0]['commentmature']){
				$mature = self::getService('mature')->getMatureById($val['Aid']);
				$result[$key]['event'] = $mature;
				if ($val['EventType'] == $event[0]['invitemature']) {
					$result[$key]['event']['invitee'] = self::getService('user')->getUserInfo($val['Content']);
				}
			} else {
				$result[$key]['event'] = self::getService('habit')->getHabitDetail($val['Aid']);
			}
		}

		return $result;

	}

	/*
	 * 获取习惯事件
	 * @param int $userId
	 * @param int $habitId
	 */
	public function getHabitEvent($habitId)
	{
		if (!is_numeric($habitId)) {
			return array();
		}

		global $event;
		$habitEvent = array(
			'0' => $event[0]['createhabit'],
			'1' => $event[0]['edithabit'],
			'2' => $event[0]['completehabit'],
			'3' => $event[0]['changehabitstatus'],
			'4' => $event[0]['delhabit'],
			'5' => $event[0]['bufenwanchengxiguan'],
			'6' => $event[0]['weiwanchengxiguan'],
			'7' => $event[0]['habitstory'],
		);

		$habitEvent = implode(',', $habitEvent);
		return self::getDao('event')->getAll('*', " Aid = $habitId and EventType in ($habitEvent) and IsShow = 1 and Isvalid = 1 order by AddTime desc");
	}
}
?>
