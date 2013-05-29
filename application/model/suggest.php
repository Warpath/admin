<?php
/*
 * 建议服务层
 * author: warpath
 * date:2013年 05月 24日 星期五 05:51:34 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
class Suggest extends Model
{
	/*
	 * 添加建议
	 * @param int $userId
	 * @param array $opts
	 * @return array $result
	 */
	public function create($userId, $opts)
	{
		$result = array(
			'flg' => false,	
		);

		$flag = self::getValidate('suggest')->check($opts);
		if ($flag !== true) {
			$result['data'] = $flag;
			return $result;
		}

		$params = array(
			'Content' => $opts['content'],	
			'Email' => $opts['email'],
			'AddTime' => time()
		);
		if ($userId) {
			$params['UserId'] = $userId;
		}
		$insertId = self::getDao('suggest')->create($params);
		if($insertId > 0) {
			$result['flg'] = true;
		}

		return $result;
	}
}

?>
