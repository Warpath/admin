<?php
/*
 * 投票管理
 */
class VoteAction extends Action
{
	public function index()
	{
		$template = 'vote.html';
		$vote = self::getService('vote')->getAllVote();

		$smarty = self::getSmarty();
		$smarty->assign('vote', $vote);
		$smarty->display($template);
	}

	public function add()
	{
		$template = 'add_vote.html';

		$smarty = self::getSmarty();
		$smarty->display($template);
	}

	public function del()
	{
		$id = isset($_GET['id']) ? addslashes($_GET['id']) : null;

		$result = self::getService('vote')->del($id);
		if($result) {
			header('location:'.'/vote');
		}
	}
	public function adding()
	{
		$content = isset($_POST['content']) ? addslashes($_POST['content']) : null;

		$opts = array(
			'content' => $content,	
		);

		$result = self::getService('vote')->adding($opts);
		if ($result) {
			header('location:'.'/vote');
		}
	}
}
?>
