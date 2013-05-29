<?php
/*
 * 标签管理
 */
class TagsAction extends Action
{
	public function index()
	{
		$tags = self::getService('tags')->getAllTags();
		$template = 'tags.html';

		$smarty = self::getSmarty();
		$smarty->assign('tags', $tags);
		$smarty->display($template);
	}

	public function del()
	{
		$id = isset($_GET['id']) ? addslashes($_GET['id']) : null;

		$result = self::getService('tags')->del($id);
		if($result) {
			header('location:'.'/tags');
		}
	}

	public function add()
	{
		$template = 'add_tags.html';

		$smarty = self::getSmarty();
		$smarty->display($template);
	}

	public function adding()
	{
		$name = isset($_POST['name']) ? addslashes($_POST['name']) : null;
		$des = isset($_POST['des']) ? addslashes($_POST['des']) : null;
		$image = isset($_POST['image']) ? addslashes($_POST['image']) : null;
	
		$opts = array(
			'name' => $name,
			'des' => $des,
			'image' => $image,	
		);

		$result = self::getService('tags')->add($opts);
		echo json_encode($result);
		exit;
	}

	public function imgupload()
	{
		$result = self::getService('thanksgiving')->imgupload();	

		echo json_encode($result);
		exit;
	}
}
?>
