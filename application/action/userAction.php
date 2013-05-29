<?php
/*
 * 用户管理
 */
class UserAction extends Action
{
	public function index()
	{
		$user = self::getService('user')->getAllUser();

		$template = 'index.html';
		$smarty = self::getSmarty();
		$smarty->assign('user', $user);
		$smarty->display($template);
	}

	public function del()
	{
		$id = isset($_GET['id']) ? addslashes($_GET['id']) : null;

		$result = self::getService('user')->del($id);

		if ($result) {
			header('location: '.'/user');
		}
	}
}
?>
