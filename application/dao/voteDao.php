<?php
/*
 * 投票Dao
 */
class VoteDao extends BaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'vote';
	}
}
?>
