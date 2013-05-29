<?php
/*
 * 功能：搜索服务层
 * author: warpath
 * date:2013年 05月 20日 星期一 22:13:17 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class Search extends Model
{
	private $cl;
	public function __construct()
	{
		$this->cl = self::getSphinxHandler();
	}

	/*
	 * 用户搜索
	 */

	/*
	 * 搜索
	 * @param str $keyword
	 * $params str $type
	 * @return array
	 */
	public function getSearchResult($keyword, $type) {
		$result = array();

		//TODO::违禁词过滤

		switch($type) {
			case 'habit':
				$ids = $this->getDocIds($keyword, 'habit');
				$result['habit'] = self::getService('habit')->getHabitByIds($ids);
			break;
			case 'thanksgiving':
				$ids = $this->getDocIds($keyword, 'thanksgiving');
				$result['thanksgiving'] = self::getService('thanksgiving')->getThanksgivingByIds($ids);
			break;
			case 'mature':
				$ids = $this->getDocIds($keyword, 'mature');
				$result['mature'] = self::getService('mature')->getMatureByIds($ids);
			break;
			case 'user':
				$ids = $this->getDocIds($keyword, 'user');
				$result['user'] = self::getService('user')->getUserByIds($ids);
			break;
			case 'all':	
				$ids = $this->getDocIds($keyword, 'user');
				$user = self::getService('user')->getUserByIds($ids);
				if(!empty($user)) {
					foreach($user as $key => $val) {
						$user[$key]['forkCount'] = self::getDao('userfork')->getCount("ForkerId = ".$val['Id']." and Isvalid = 1");;
					}
				}
				$result['user'] = $user;

				$ids = $this->getDocIds($keyword, 'habit');
				$habit = self::getService('habit')->getHabitByIds($ids);
				if (!empty($habit)) {
					foreach($habit as $key => $val) {
						$result[] = array(
							'type' => 'habit',	
							'item' => $val,
						);
					}
				}
				$ids = $this->getDocIds($keyword, 'thanksgiving');
				$thanksgiving = self::getService('thanksgiving')->getThanksgivingByIds($ids);
				if (!empty($thanksgiving)) {
					foreach($thanksgiving as $key => $val) {
						$result[] = array(
							'type' => 'thanksgiving',	
							'item' => $val,
						);
					}
				}
				$ids = $this->getDocIds($keyword, 'mature');
				$mature = self::getService('mature')->getMatureByIds($ids);
				if (!empty($mature)) {
					foreach($mature as $key => $val) {
						$result[] = array(
							'type' => 'mature',	
							'item' => $val,
						);
					}
				}
			break;
		}
		
		return $result;
	}

	private function getDocIds($keyword, $type) {
		$ids = '';
		$res = $this->cl->Query("$keyword", "$type");	
		if (isset($res['matches'])) {
			$res = $res['matches'];
			$ids = $this->getIds($res);
		}
		return $ids;
	}

	/*
	 *获取结果Id
	 * @param array $res
	 * @return array $ids
	 */
	public function getIds($res)
	{
		$ids = array();
		foreach($res as $key => $val) {
			$ids[] = $val['id'];
		}

		return $ids;
	}

}
?>
