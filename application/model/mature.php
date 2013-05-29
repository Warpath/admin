<?php
/**
 * 功能：成长服务层
 * author: warpath
 * date:2013年 05月 03日 星期五 00:51:37 PDT 
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class Mature extends Model
{

	/*
	 * 获取成长详情
	 * @param int $userId
	 * @param int $matureId
	 * @return array
	 */
	public function getMatureDetail($userId, $matureId)
	{
		if (!is_numeric($matureId)) {
			return array();
		}

		$mature = self::getDao('mature')->getRow('*', "Id = $matureId and UserId = $userId and Isvalid = 1");
		if ($mature['Tags'] !== '') {
			$mature['Tags'] = explode(',', $mature['Tags']);
		}
		return $mature;
	}

	/*
	 * 获取成长评论
	 * @param int $userId
	 * @param int $matureId
	 * @return array $result
	 */
	public function getMatureComments($userId, $matureId)
	{
		if (!is_numeric($matureId)) {
			return  array();
		}

		global $commentType;
		$comments = self::getDao('comments')->getAll('*', "Aid = $matureId and CommentType = ".$commentType[0]['mature']." and Isvalid = 1 order by AddTime desc");
		if (!empty($comments)) {
			foreach($comments as $key => $val) {
				$comments[$key]['userinfo'] = self::getService('user')->getUserInfo($val['UserId']);
			}
		}
		return $comments;
	}

	/*
	 * 获取用户关注的成长点滴
	 * @param int $userId
	 * @return array $result
	 */
	public function getMyFocusMature($userId)
	{
		$result = array();
		if (!is_numeric($userId)) {
			return $result;
		}

		$result = self::getDao('mature')->join('m.*', "mature as m left join mature_attention as a on m.Id = a.MatureId", "a.UserId = $userId and m.Isvalid = 1 and m.IsPrivate = 0 and a.Isvalid = 1", 'many');
		if (!empty($result)) {
			global $commentType;
			foreach($result as $key => $val) {
				$result[$key]['commentCount'] = self::getDao('comments')->getCount("Aid = ".$val['Id']." and UserId = $userId and CommentType = ".$commentType[0]['mature']." and Isvalid = 1");
				$result[$key]['attentionCount'] = self::getDao('matureattention')->getCount("MatureId = ".$val['Id']." and Isvalid = 1");
			}
		}

		return $result;
	}

	/*
	 * 获取用户成长点滴总数
	 * @param int $userId
	 * @return int $result
	 */
	public function getMatureCount($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		return self::getDao('mature')->getCount("UserId = $userId and Isvalid = 1");
	}

	/*
	 * 获取用户成长所有点滴
	 * @param int $userId
	 * @return array $result
	 */
	public function getAllMature($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		$mature = self::getDao('mature')->getAll('*', "UserId = $userId  and Isvalid = 1 order by Id desc limit 5");
		global $commentType;
		foreach($mature as $key => $val) {
			$mature[$key]['commentCount'] = self::getDao('comments')->getCount("Aid = ".$val['Id']." and CommentType = ".$commentType[0]['mature'] ." and Isvalid = 1");
			$mature[$key]['attentionCount'] = self::getDao('matureattention')->getCount("MatureId = ".$val['Id']." and Isvalid = 1");
		}

		return $mature;
	}
	/*
	 * 获取用户成长点滴
	 * @param int $userId
	 * @return array $result
	 */
	public function getMature($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		$mature = self::getDao('mature')->getAll('*', "UserId = $userId and IsPrivate = 0 and Isvalid = 1 order by Id desc limit 5");
		global $commentType;
		foreach($mature as $key => $val) {
			$mature[$key]['commentCount'] = self::getDao('comments')->getCount("Aid = ".$val['Id']." and CommentType = ".$commentType[0]['mature'] ." and Isvalid = 1");
			$mature[$key]['attentionCount'] = self::getDao('matureattention')->getCount("MatureId = ".$val['Id']." and Isvalid = 1");
		}

		return $mature;
	}

	/*
	 * 获取更多用户成长点滴
	 * @param int $userId
	 * @param int $lastmsg
	 * @return array $result
	 */
	public function getMoreMature($userId, $lastmsg)
	{
		if (!is_numeric($lastmsg)) {
			return array();
		}

		$mature = self::getDao('mature')->getAll('*', "UserId = $userId and Id < $lastmsg and Isvalid = 1 order by Id desc limit 5");
		global $commentType;
		foreach($mature as $key => $val) {
			$mature[$key]['commentCount'] = self::getDao('comments')->getCount("Aid = ".$val['Id']." and CommentType = ".$commentType[0]['mature'] ." and Isvalid = 1");
			$mature[$key]['attentionCount'] = self::getDao('matureattention')->getCount("MatureId = ".$val['Id']." and Isvalid = 1");
		}

		return $mature;
	}


	/**
	 * 用户关注成长点滴
	 * @param int $userId
	 * @param int $matureId
	 * @return array $result
	 */
	public function attention($userId, $matureId)
	{
		$result = array(
			'flg' => false,	
		);

		$mature = self::getDao('mature')->getRow('Id', "Id = $matureId and IsPrivate = 0 and Isvalid = 1");
		if (!empty($mature)) {
			$attention = self::getDao('matureattention')->getCount("MatureId = ".$mature['Id']." and UserId = $userId and Isvalid = 1");
			if ($attention > 0) {
				$result['data'] = '你已经关注此成长点滴';
			} else {
				$params = array(
					'UserId' => $userId,
					'MatureId' => $matureId,	
				);	
				$insertId = self::getDao('matureattention')->create($params);

				if ($insertId > 0) {
					$result['flg'] = true;
				}
			}
		} else {
			$result['data'] = 'illegality operate';
		}

		return $result;
	}

	/**
	 * 根据Id获取成长信息
	 * @param int $id
	 * @return array $result
	 */
	public function getMatureById($id)
	{
		if (!is_numeric($id)) {
			return array();
		}

		$result = self::getDao('mature')->getRow('*', "Id = $id and Isvalid = 1");
		if(isset($result['Tags'])) {
			$result['Tags'] = explode(',', $result['Tags']);
		}
		return $result;
	}

	/**
	 * 用户添加成长
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function addMature($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('mature')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		}

		$userinfo = self::getService('user')->getUserInfo($userId);
		if(0 === $userinfo['IsShowMature']) {
			$opts['isPrivate'] = 1;
		}

		$params = array(
			'UserId' => $userId,
			'Title' => $opts['title'],	
			'Content' => $opts['content'],
			'IsPrivate' => $opts['isPrivate'],
			'Isvalid' => 1,
			'Tags' => $opts['tags'],
			'AddTime' => time(),
		);
		$insertId = self::getDao('mature')->create($params);

		if ($insertId > 0) {
			global $event;
			$params = array(
				'UserId' => $userId,
				'Aid' => $insertId,
				'EventType' => $event[0]['createmature'],
				'Content' => '',
				'IsShow' => $opts['isPrivate'] == 1 ? 0 : 1,
				'AddTime' => time(),
			);
			$eventId = self::getDao('event')->create($params);
			if ($eventId > 0) {
				$result['flg'] = true;
			}
		}

		return $result;
	}

	/**
	 * 用户编辑成长
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function editMature($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('editMature')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		}

		$userinfo = self::getService('user')->getUserInfo($userId);
		if(0 === $userinfo['IsShowMature']) {
			$opts['isPrivate'] = 1;
		}

		$mature = self::getDao('mature')->getRow('*', "Id = " . $opts['id'] . " and UserId = ". $userId. " and Isvalid = 1");
		if (!empty($mature)) {
			$params = array(
				'Title' => $opts['title'],	
				'Content' => $opts['content'],
				'IsPrivate' => $opts['isPrivate'],
				'Tags' => $opts['tags'],
				'UpdateTime' => time(),
			);
			$rowCount = self::getDao('mature')->update($params, "Id = ".$opts['id']);

			if (1 == $rowCount) {
				global $event;
				$params = array(
					'UserId' => $userId,
					'Aid' => $opts['id'],
					'EventType' => $event[0]['editmature'],
					'Content' => '',
					'IsShow' => $opts['isPrivate'] == 1 ? 0 : 1,
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
	 * 邀请用户解答
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function invite($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$mature = self::getDao('mature')->getRow('*', "Id = ".$opts['matureId']." and UserId = ".$userId." and Isvalid = 1");
		if (!empty($mature)) {
			$invitee = self::getService('user')->getUserInfo($userId);
			if (!empty($invitee)) {
				if($mature['Invitee'] == 1) {
					$result['data'] = '你只能邀请一个人回答';
				} else {
					$params = array(
						'IsInvite' => 1,
						'Invitee' => $opts['invitee'],
						'UpdateTime' => time(),
						'InviteeTime' => time(),
					);
					$rowCount = self::getDao('mature')->update($params, "Id = ".$opts['matureId']);
					if ($rowCount == 1) {
						global $event;
						$params = array(
							'UserId' => $userId,
							'Aid' => $mature['Id'],	
							'EventType' => $event[0]['invitemature'],
							'Content' => $opts['invitee'],
							'IsShow' => $mature['IsPrivate'] == 1 ? 0 : 1,
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
		} else {
			$result['data'] = 'illegality operate';
		}

		return $result;
	}	

	/**
	 * 记录用户收获
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function harvest($userId, $opts)
	{
		$result = array(
			'flg' => false,
		);

		$flag = self::getValidate('harvest')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		}

		$mature = self::getDao('mature')->getRow('*', "Id = ".$opts['matureId']." and UserId = $userId and Isvalid = 1");
		if (!empty($mature)) {
			$params = array(
				'Harvest' => $opts['harvest'],	
				'UpdateTime' => time(),
				'HarvestTime' => time(),
			);
			$rowCount = self::getDao('mature')->update($params, "Id = ".$opts['matureId']." and Isvalid = 1");
			if (1 == $rowCount) {
				global $event;
				$params = array(
					'UserId' => $userId,
					'Aid' => $mature['Id'],	
					'EventType' => $event[0]['harvest'],
					'Content' => '',
					'IsShow' => $mature['IsPrivate'] == 1 ? 0 : 1,
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
	 * 用户评论
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function comment($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('comment')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		}

		$mature = self::getDao('mature')->getRow('*', "Id = ".$opts['matureId']." and Isvalid = 1");
		if (!empty($mature)) {
			if ($userId == $mature['UserId']) {
				$result['data'] = '你不能评论自己的成长点滴';
			} else {
				global $commentType;
				$params = array(
					'Aid' => $mature['Id'],	
					'UserId' => $userId,
					'Content' => $opts['content'],
					'CommentType' => $commentType[0]['mature'],
					'AddTime' => time(),
				);
				$insertId = self::getDao('comments')->create($params);
				if ($insertId > 0) {
					global $event;
					$params = array(
						'UserId' => $userId,
						'Aid' => $mature['Id'],	
						'EventType' => $event[0]['commentmature'],
						'Content' => $opts['content'],
						'IsShow' => $mature['IsPrivate'] == 1 ? 0 : 1,
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

	/*
	 * 检查标签是否存在
	 * @param array $opts
	 * @return array $result
	 */
	public function checkExist($opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('tag')->check($opts);
		if (true !== $flag)  {
			$result['data'] = $flag;
			return $result;
		}

		$tag = self::getDao('tag')->getRow('Id', "Name = '".$opts['tag']."' and Isvalid = 1");
		if (!empty($tag)) {
			$result['flg'] = true;
			$result['data'] = $tag['Id'];
		}

		return $result;
	}

	/*
	 * 标签
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function addTag($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('tag')->check($opts);
		if (true !== $flag)  {
			$result['data'] = $flag;
			return $result;
		}

		$params = array(
			'UserId' => $userId,
			'Name' => $opts['tag'],	
			'AddTime' => time(),
		);
		$insertId = self::getDao('tag')->create($params);

		if ($insertId > 0) {
			$result['flg'] = true;
			$result['data'] = $insertId;
		}

		return $result;
	}

	/*
	 * 获取邀请回答推荐用户
	 * @param int $matureId 
	 * @return array $result
	 */
	public function getInvitePeople($matureId)
	{
		$result = array();
		if(!is_numeric($matureId)) {
			return $result;
		}
		
		$mature = self::getDao('mature')->getRow('Tags', "Id = $matureId and Isvalid = 1");
		if (!empty($mature)) {
			if($mature['Tags'] !== '') {
				$userTag = self::getDao('usertag')->getAll('*', "TagId in (".$mature['Tags'].") and IsRecommend = 1 and Isvalid = 1 group by UserId limit 5");
				foreach($userTag as $key=>$val) {
					$result[] = self::getService('user')->getUserInfo($val['UserId']);
				}
			} else {//没有标签，选取回答问题最多的用户
			
			}
		} 

		return $result;
	}

	/*
	 * 获取邀请用户回答的成长
	 * @param int $userId
	 * @return array $result
	 */
	public function getInviteMe($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		$mature = self::getDao('mature')->getAll('*', "IsInvite = 1 and Invitee = $userId and Isvalid = 1");
		global $commentType;
		foreach($mature as $key=>$val) {
			$mature[$key]['commentCount'] = self::getDao('comments')->getCount("Aid = ".$val['Id']." and commentType = ".$commentType[0]['mature']." and Isvalid = 1");
			$mature[$key]['attentionCount'] = self::getDao('matureattention')->getCount("MatureId = ".$val['Id']." and Isvalid");
		}

		return $mature;
	}

	/*
	 * 根据Id获取成长数组
	 * @param array $ids
	 * @return array $result
	 */
	public function getMatureByIds($ids)
	{
		if (!is_array($ids)) {
			return array();
		}

		$ids = implode(',', $ids);
		$result = self::getDao('mature')->getAll('*', "Id in ($ids) and IsPrivate = 0 and Isvalid = 1");
		foreach($result as $key => $val) {
			$result[$key]['userinfo'] = self::getService('user')->getUserInfo($val['UserId']);
		}

		return $result;
	}

}
?>
