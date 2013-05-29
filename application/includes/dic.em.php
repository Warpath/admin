<?php
/**
 * 功能：数据字典
 * author: warpath
 * date:2013年 05月 04日 星期六 03:14:55 PDT
 * 版权：Copyright@2013-2015 breakfate.com ALL Rights Reserved
 */
$habitStatus = array(
	0 => array(
		'mubiaoxiguan' => 10001,
		'zhengzaiyangcheng' => 10002,
		'yiyouxiguan' => 10003,
	),	
	1 => array(
		'10001' => '目标习惯',	
		'10002' => '正在养成',
		'10003' => '已有习惯',
	),
);
$event = array(
	0 => array(
		'createhabit' => 10001,
		'edithabit' => 10002,
		'completehabit' => 10003,
		'changehabitstatus' => 10004,
		'delhabit' => 10005,
		'clonehabit' => 10006,
		'createthanksgiving' => 10007,
		'createmature' => 10008,	
		'editmature' => 10009,
		'invitemature' => 10010,
		'harvest' => 10011,
		'bufenwanchengxiguan' => 10012,
		'weiwanchengxiguan' => 10013,
		'habitstory' => 10014,
		'commentmature' => 10015,
	),	
	1 => array(
		'10001' => 'createhabit', 
		'10002' => 'edithabit', 
		'10003' => 'completehabit', 
		'10004' => 'changehabitstatus',
		'10005' => 'delhabit',
		'10006' => 'clonehabit',
		'10007' => 'createthanksgiving',
		'10008' => 'createmature',
		'10009' => 'editmature',
		'10010' => 'invitemature',
		'10011' => 'harvest',
		'10012' => 'bufenwanchengxiguan',
		'10013' => 'weiwanchengxiguan',
		'10014' => 'habitstory',
		'10015' => 'commentmature',
	)
);
$commentType = array(
	0 => array(
		'habit' => 10001,
		'mature' => 10002,	
	),	
	1 => array(
		'10001' => 'habit',	
		'10002' => 'mature',
	),
);
$period = array(
	0 => array(
		'week' => 10001,
		'day'  => 10002,	
	),	
	1 => array(
		'10001' => '周',	
		'10002' => '日',
	),
);
$habitOperate = array(
	0 => array(
		'wancheng' => 10001,
		'bufenwancheng' => 10002,
		'weiwancheng' => 10003,
	),	
	1 => array(
		'10001' => '完成',	
		'10002' => '部分完成',
		'10003' => '未完成',
	),
);
$messageType = array(
	0 => array(
		'thanksgiving' =>  10001,
		'private' => 10002,
		'system' => 10003,	
	),	
	1 => array(
		'10001' => 'thanksgiving',	
		'10002' => 'private',
		'10003' => 'system',
	),
);
?>
