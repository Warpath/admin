<?php
/*
 * 功能：标签服务层
 * author: warpath
 * date:2013年 05月 15日 星期三 18:43:06 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class Tags extends Model
{
	/*
	 * 标签Id对应数组
	 */
	public function getTagIdToName()
	{
		
		$tags = self::getDao('tag')->getAll('Id,Name', "Isvalid = 1");
		foreach($tags as $val) {
			$result[$val['Id']] = $val['Name'];
		}
		return $result;
	}
	/*
	 * 获取热门八个标签
	 * @return array $result
	 */
	public function getHotTags($userId)
	{
		$hotTags = self::getDao('tag')->join('t.*,c.Count as count', "tag as t left join tag_count as c on t.Id = c.TagId", " t.Isvalid = 1 order by c.Count limit 8", 'many');	
		if (!empty($hotTags)) {
			foreach($hotTags as $key => $val) {
				$isSelected = self::getDao('usertag')->getCount("UserId = $userId and TagId = ".$val['Id']." and Isvalid = 1");;
				if ($isSelected>0) {
					$hotTags[$key]['isSelected'] = 1;
				}
			}
		}
		return $hotTags;
	}

	/*
	 * 获取可以选择标签
	 * @return array $result
	 */
	public function getTags($userId)
	{
		$tags = self::getDao('tag')->getAll('*', "Isvalid = 1 order by Sort desc limit 20");
		if (!empty($tags)) {
			foreach($tags as $key=>$val){
				$isSelected = self::getDao('usertag')->getCount("UserId = $userId and TagId = ".$val['Id']." and Isvalid = 1");
				if ($isSelected>0) {
					$tags[$key]['isSelected'] = 1;
				}
			}
		}
		return $tags;
	}

	/*
	 * 获取用户选择标签数量
	 * @param int $userId
	 * @return array $result
	 */
	public function getTagsCount($userId)
	{
		if (!is_numeric($userId)) {
			return array();
		}
		return self::getDao('usertag')->getCount("UserId = $userId and Isvalid = 1");
	}

	/*
	 * 用户删除标签
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function delTag($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);
		
		$tag = self::getDao('tag')->getRow('*', "Id = ".$opts['tagId']." and Isvalid = 1");
		if (!empty($tag)) {
			$usertag = self::getDao('usertag')->getRow('*', "UserId = $userId and TagId = ".$opts['tagId']." and Isvalid = 1");
			if (!empty($usertag)) {
				$params = array(
					'Isvalid' => 0,
					'UpdateTime' => time()	
				);
				$rowCount = self::getDao('usertag')->update($params, "UserId = $userId and TagId = ".$opts['tagId']);
				if ($rowCount == 1) {
					$result['flg'] = true;
				}
			} else {
				$result['data'] = '错误';
			}
		} else {
			$result['data'] = 'illegality operate';
		}

		return $result;
	}


	/*
	 * 用户选择标签
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function addTag($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$tag = self::getDao('tag')->getRow('*', "Id = ".$opts['tagId']." and Isvalid = 1");
		if (!empty($tag)) {
			$usertag = self::getDao('usertag')->getRow('*', "UserId = $userId and TagId = ". $opts['tagId'] . " and Isvalid = 1");
			if (!empty($usertag)) {
				$result['data'] = '你已经选择此标签';
			} else {
				$params = array(
					'UserId' => $userId,
					'TagId' => $tag['Id'],	
					'IsRecommend' => 1,
					'AddTime' => time(),
				);
				$insertId = self::getDao('usertag')->create($params);
				if ($insertId > 0) {
					$tagCount = self::getDao('tagcount')->getRow('*', "TagId = " .$tag['Id']);
					if (!empty($tagCount)) {//已经有数据
						$params = array(
							'Count' => $tagCount['Count'] + 1,
							'UpdateTime' => time(),	
						);
						$rowCount = self::getDao('tagcount')->update($params, "TagId = ".$tag['Id']);
					} else {
						$params = array(
							'TagId' => $tag['Id'],	
							'Count' => 1,
							'UpdateTime' => time(),
						);
						$rowCount = self::getDao('tagcount')->create($params);
					}
					
					if ($rowCount > 0) {
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
	 * 获取用户选择标签
	 * @param int $userId
	 * @return array $result
	 */
	public function getUserTags($userId)
	{
		return self::getDao('tag')->join('t.*', 'tag as t left join user_tag as u on t.Id = u.TagId', "u.UserId = $userId and t.Isvalid = 1 and u.Isvalid = 1", 'many');
	}

	/*
	 * 获取所有标签
	 */
	public function getAllTags()
	{
		return self::getDao('tag')->getAll('*', "Isvalid = 1");
	}

	/*
	 * 删除标签
	 */
	public function del($id)
	{
		$params = array(
			'Isvalid' => 0,
			'UpdateTime' => time(),	
		);
		return self::getDao('tag')->update($params, "Id = $id and Isvalid = 1");
	}

	/*
	 * 添加标签
	 */
	public function add($opts)
	{
		$result = array(
			'flg' => false,	
		);
		$params = array(
			'Name' => $opts['name'],	
			'Des' => $opts['des'],
			'Img' => $opts['image'],
		);

		$insertId = self::getDao('tag')->create($params);
		if ($insertId > 0) {
			$result['flg'] = true;
		}
		return $result;
	}
}
?>
