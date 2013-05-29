<?php
/*
 * 首页
 */
class IndexAction extends Action
{
	public function index()
	{
		$smarty = self::getSmarty();
		$template = 'index.html';
		$smarty->display($template);
	}
}
?>
