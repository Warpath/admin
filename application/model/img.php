<?php
/*
 * 功能：图片处理服务
 * author: warpath
 * date:2013年 05月 16日 星期四 20:49:46 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */ 
class Img extends Model
{
	/*
	 * 上传用户头像
	 * @param int $uid
	 * @param int $userId
	 * @return str
	 */
	public function avatarUpload($uid, $userId)
	{
		$siteurl = 'http://img1.breakfate.com';

		$requestUri = $_SERVER['REQUEST_URI'];
		$params = preg_split("/\?/", $requestUri);
		$params = explode('&', $params[1]);
		foreach($params as $val) {
			$ext = explode('=', $val);
			$data[$ext[0]] = $ext[1];
		}
		define('MY_ROOT', substr(dirname(__FILE__), 0, -8));
		if($data['data'] && $data['s'] && $data['mime'] && $data['ct']){
			@header("Expires: 0");
			@header("Cache-Control: private, post-check=0, pre-check=0, max-age=0", FALSE);
			@header("Pragma: no-cache");
			$dir=_IMG_PATH_.'/avatar/'.date('Y').'/'.date('m').'/';
			if(!is_dir($dir)){
				mkdir(_IMG_PATH_.'/avatar/'.date('Y').'/'.date('m').'/', 0777, true);
			}
			$filepath=_IMG_PATH_.'/avatar/'.date('Y').'/'.date('m').'/'.$uid.'.jpg';
			$arr = get_defined_vars();
			$len = file_put_contents($filepath,file_get_contents("php://input"));
			if($len>2*1024*1024){
				return '{"code":"M01108"}';		//请上传文件大小不超过2M的图片。
			}elseif($len>0){
				$image = self::getImgHandler();
				$smallthumb=Image::thumb($filepath,'','',30,30,true,'_s');
				$params = array(
					'Avatar' => $this->imgFilter($arr['filepath']),	
					'Avatars' => $this->imgFilter($smallthumb),
				);
				$rowCount = self::getDao('userbasic')->update($params, "Id = $userId");
				if ($rowCount == 1) {
					$params = array(
						'UpdateTime' => time(),	
					);
					$rowCount = self::getDao('user')->update($params, "Id = $userId");
					return '{"code":"A00006"}';		//保存成功
				}
			}else return '{"code":"E00002"}';	//参数错误
	
		}else return '{"code":"E00002"}';		

	}

	public function imgFilter($imgurl){
		$img=explode(_IMG_PATH_,$imgurl);
		if(empty($img)){
			return null;
		}else{
			return $img[1];
		}
	}
}
?>
