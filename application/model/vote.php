<?php
/*
 * 投票服务层
 */
class Vote extends Model
{
	public function getAllVote()
	{
		return self::getDao('vote')->getAll('*', "Isvalid = 1");
	}

	public function adding($opts)
	{
		$result = false;
		$params = array(
			'Content' => $opts['content'],	
			'Isvalid' => 1,
			'AddTime' => time(),
		);

		$insertId = self::getDao('vote')->create($params);
		if ($insertId > 0) {
			$result = true;
		}
		return $result;
	}
}
?>
