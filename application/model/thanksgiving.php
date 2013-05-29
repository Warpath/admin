<?php
/**
 * 功能：感恩服务层
 * author: warpath
 * date:2013年 05月 05日 星期日 18:22:50 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class Thanksgiving extends Model
{
	/**
	 * 获取用户感恩
	 * @param int $userId
	 * @return array $result
	 */
	public function getThanksgiving($userId, $limit)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		return self::getDao('thanksgiving')->getAll('*', "UserId = $userId and Isvalid = 1 order by Id desc $limit");
	}

	/**
	 * 获得用户感恩总数
	 * @param int $userId
	 * @return array $result
	 */
	public function getThanksgivingCount($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}

		return self::getDao('thanksgiving')->getCount("UserId = $userId and Isvalid = 1");
	}

	/**
	 * 添加用户感恩
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function add($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('thanksgiving')->check($opts);
		if (true !== $flag) {
			$result['data'] = $flag;
			return $result;
		} 

		$recId = '';
		preg_match_all("/@\w+\s/", $opts['content'], $matches);
		//用户验证
		if (isset($matches[0]) && !empty($matches[0])) {
			global $siteurl;
			foreach($matches[0] as $key => $val) {
				$name = trim($val);
				$pattern = $name;
				$name = preg_replace("/^@/i", '', $name);
				$userinfo = self::getService('user')->getUserInfoByName($name);
				if (empty($userinfo)) {
					$result['data'] = 'user not exist';
					return $result;
				}
				$opts['content'] = preg_replace("/$pattern/", "<a href='$siteurl/home/".$name."'>".$pattern."</a>", $opts['content']);
				$recId .= $userinfo['Id'].',';
			}
			$recId = preg_replace("/,$/", '', $recId);
		}

		$params = array(
			'UserId' => $userId,
			'Content' => $opts['content'],	
			'RecId' => $recId,
			'Image' => $opts['image'],
			'AddTime' => time()
		);	
		$insertId = self::getDao('thanksgiving')->create($params);
		if ($insertId > 0) {
			global $event;
			$params = array(
				'UserId' => $userId,
				'Aid' => $insertId,
				'EventType' => $event[0]['createthanksgiving'],
				'Content' => '',
				'AddTime' => time(),
			);
			$eventId = self::getDao('event')->create($params);

			//站内信
			if ($recId !== '') {
				global $messageType;
				$params = array(
					'Title' => '有感恩提到你',	
					'Content' => $insertId,
					'Type' => $messageType[0]['thanksgiving'],
					'AddTime' => time()
				);
				$messageTextId = self::getDao('messageText')->create($params);
				if ($messageTextId > 0) {
					$recId = explode(',', $recId);
					foreach($recId as $key => $val) {
						$params = array(
							'MessageId' => $messageTextId,
							'SendId' => $userId,
							'RecId' => $val,
						);
						$messageId = self::getDao('message')->create($params);
					}
				}
			}
			
			if ($eventId > 0) {
				$result['flg'] = true;
			}
		}

		return $result;
	}

	/*
	 * 图片上传
	 */
	public function imgupload()
	{
		$error = "";
		$msg = "";
		$fileElementName = 'fileToUpload';
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{

				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;

				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
		{
			$error = 'No file was uploaded..';
		}else 
		{
			set_time_limit(0);	
			$tmppath = _IMG_PATH_;
			$time =	time();
			$year = date('Y', $time);
			$month = date('m', $time);
			if(!is_dir($tmppath.'/'.$year)) {
				@mkdir($tmppath.'/'.$year, 0777);
			}
			if(!is_dir($tmppath.'/'.$year.'/'.$month)) {
				@mkdir($tmppath.'/'.$year.'/'.$month, 0777);
			}
			$path = $tmppath.'/'.$year.'/'.$month.'/';
			if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
				$name = $_FILES['fileToUpload']['name'];
				$name = preg_match("/\.\w{3,4}$/", $name, $matchs);
				$imgname = $_COOKIE['userId'] . md5(time().mt_rand(0,10000));
				$path = $path.$imgname.$matchs[0];
				if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $path)) {
					$res['flg'] = true;
					$res['data'] = '/'.$year.'/'.$month.'/'.$imgname.$matchs[0];
					return $res;
				}
			} else {
				$res['data'] = '非法访问';
			}
			//$msg .= " File Name: " . $_FILES['fileToUpload']['name'] . ", ";
			//$msg .= " File Size: " . @filesize($_FILES['fileToUpload']['tmp_name']);
			//for security reason, we force to remove all uploaded file
			@unlink($_FILES['fileToUpload']);		
		}		

		$res['msg'] = $msg;
		$res['error'] = $error;
		return $res;
	}

	/*
	 * 获取更多的信息
	 * @param int $userId
	 * @param int $lastmsg
	 * @return array $result
	 */
	public function getMoreMsg($userId, $lastmsg)
	{
		if (!is_numeric($lastmsg)) {
			return array();
		}

		return self::getDao('thanksgiving')->getAll('*', "UserId = $userId and Id < $lastmsg and Isvalid = 1 order by Id desc limit 5");
	}

	/*
	 * 根据Id获取感恩数组
	 * @param array $ids
	 * @return array $result
	 */
	public function getThanksgivingByIds($ids)
	{
		if (!is_array($ids)) {
			return array();
		}

		$ids = implode(',', $ids);
		$result = self::getDao('thanksgiving')->getAll('*', "Id in ($ids) and Isvalid = 1");
		foreach($result as $key => $val) {
			$result[$key]['userinfo'] = self::getService('user')->getUserInfo($val['UserId']);
		}

		return $result;
	}

	/*
	 * 根据Id获取感恩信息
	 * @param int $id
	 * @return array $result
	 */
	public function getThanksgivingById($id)
	{
		if (!is_numeric($id)) {
			return array();
		}

		$result = self::getDao('thanksgiving')->getRow('*', "Id = $id and Isvalid = 1");
		return $result;
	}

	/*
	 * 获取@我的感恩
	 * @param int $userId
	 * @param bool $isRead//是否标记为已读
	 * @return array $result
	 */
	public function thanksgivingAtMe($userId, $IsRead = false)
	{
		$result = array();
		if (!is_numeric($userId)) {
			return $result;
		}

		global $messageType;
		$message = self::getDao('message')->join('t.*,m.Id as mid,m.SendId', "messageText as t left join message as m on t.Id = m.MessageId", "t.Type = ".$messageType[0]['thanksgiving']." and t.Isvalid = 1 and RecId = $userId and IsRead = 0", 'many');
		if (!empty($message)) {
			global $event;
			foreach($message as $key => $val) {
				$result[] = array(
					'EventType' => $event[0]['createthanksgiving'],
					'event' => self::getService('thanksgiving')->getThanksgivingById($val['Content']),
					'userinfo' => self::getService('user')->getUserInfo($val['SendId']),
				);

				if ($IsRead == true) {
					$params = array(
						'IsRead' => 1	
					);
					$rowCount = self::getDao('message')->update($params, "Id = ".$val['mid']);
				}
			}
		}
		return $result;
	}
}
?>
