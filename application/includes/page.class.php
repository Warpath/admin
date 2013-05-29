<?php
/*******************************************
 * 文件名： /include/page.class.php
 * 功能：     分页存类 
 * 版本：      2.0
 * 日期：      2011-05-11
 * 程序名： 
 * 作者：      quwancn
 * 版权：      Copyright@2008-2015 quwancn Inc All Rights Reserved
 *********************************************/
class page 
{
 /**
  * config ,public
  */
 var $page_name="p";//page标签，用来控制url页。比如说xxx.php?PB_page=2中的PB_page
 var $next_page='>';//下一页
 var $pre_page='<';//上一页
 var $first_page='First';//首页
 var $last_page='Last';//尾页
 var $pre_bar='<<';//上一分页条
 var $next_bar='>>';//下一分页条
 var $format_left='';
 var $format_right='';
 var $is_ajax=false;//是否支持AJAX分页模式 
 var $allow_key=array();//允许传入的URL参数
 var $uri = ''; //传入的合法URI
 
 /**
  * private
  *
  */ 
 var $pagebarnum=10;//控制记录条的个数。
 var $totalpage=0;//总页数
 var $ajax_action_name='';//AJAX动作名
 var $nowindex=1;//当前页
 var $url="";//url地址头
 var $offset=0;
 
 /**
  * constructor构造函数
  *
  * @param array $array['total'],$array['perpage'],$array['nowindex'],$array['url'],$array['ajax']...
  */
 function page($array)
 {
  if(is_array($array)){
     if(!array_key_exists('total',$array))$this->error(__FUNCTION__,'need a param of total');
     $total=intval($array['total']);
     $perpage=(array_key_exists('perpage',$array))?intval($array['perpage']):10;
     $nowindex=(array_key_exists('nowindex',$array))?intval($array['nowindex']):'';
     $url=(array_key_exists('url',$array))?$array['url']:'';
     //允许传入的URL参数
     if(array_key_exists('allow_key', $array)){
     	array_push($array['allow_key'],$this->page_name);
     	$this->allow_key = is_array($array['allow_key'])?$array['allow_key']:array($this->page_name);
     }
     $this->uri = (array_key_exists('uri', $array))?$array['uri']:'';
  }else{
     $total=$array;
     $perpage=10;
     $nowindex='';
     $url='';
     $uri='';
  }
  if((!is_int($total))||($total<0))$this->error(__FUNCTION__,$total.' is not a positive integer!');
  if((!is_int($perpage))||($perpage<=0))$this->error(__FUNCTION__,$perpage.' is not a positive integer!');
  if(!empty($array['page_name']))$this->set('page_name',$array['page_name']);//设置pagename
  $totalpage=ceil($total/$perpage);
  $this->totalpage=$totalpage<1?1:$totalpage;
  $this->_set_nowindex($nowindex);//设置当前页
  $this->_set_url($url);//设置链接地址
  $this->offset=($this->nowindex-1)*$perpage;
  if(!empty($array['ajax']))$this->open_ajax($array['ajax']);//打开AJAX模式
 }
 /**
  * 设定类中指定变量名的值，如果改变量不属于这个类，将throw一个exception
  *
  * @param string $var
  * @param string $value
  */
 function set($var,$value)
 {
  if(in_array($var,get_object_vars($this)))
     $this->$var=$value;
  else {
   $this->error(__FUNCTION__,$var." does not belong to PB_Page!");
  }
  
 }
 /**
  * 打开倒AJAX模式
  *
  * @param string $action 默认ajax触发的动作。
  */
 function open_ajax($action)
 {
  $this->is_ajax=true;
  $this->ajax_action_name=$action;
 }
 /**
  * 获取显示"下一页"的代码
  * 
  * @param string $style
  * @return string
  */
 function next_page($style='')
 {
  if($this->nowindex<$this->totalpage){
   return $this->_get_link($this->_get_url($this->nowindex+1),$this->next_page,$style);
  }
  return '<li><a href="#" class="">'.$this->next_page.'</a></li>';
  //return '<span class="'.$style.'" style=\'font-size:12px;\'>'.$this->next_page.'</span>';
 }
 
 /**
  * 获取显示“上一页”的代码
  *
  * @param string $style
  * @return string
  */
 function pre_page($style='')
 {
  if($this->nowindex>1){
   return $this->_get_link($this->_get_url($this->nowindex-1),$this->pre_page,$style);
  }
  return '<li><a href="#" class="">'.$this->pre_page.'</a></li>';
  //return '<span  class="'.$style.'"style=\'font-size:12px;\'>'.$this->pre_page.'</span>';
 }
 
 /**
  * 获取显示“首页”的代码
  *
  * @return string
  */
 function first_page($style='')
 {
  if($this->nowindex==1){
      //return '<span class="'.$style.'" style=\'font-size:12px;\'>'.$this->first_page.'</span>';
      return '<a href="#" class="">'.$this->first_page.'</a>';
  }
  return $this->_get_link($this->_get_url(1),$this->first_page,$style);
 }
 
 /**
  * 获取显示“尾页”的代码
  *
  * @return string
  */
 function last_page($style='')
 {
  if($this->nowindex==$this->totalpage){
      //return '<span class="'.$style.'" style=\'font-size:12px;\'>'.$this->last_page.'</span>';
      return '<a href="#" class="">'.$this->last_page.'</a>';
  }
  return $this->_get_link($this->_get_url($this->totalpage),$this->last_page,$style);
 }
 //-----------------------------------------------------------------------------------------------------------------
 function nowbar($style='',$nowindex_style='')
 {
  $plus=ceil($this->pagebarnum/2);
  if($this->pagebarnum-$plus+$this->nowindex>$this->totalpage)$plus=($this->pagebarnum-$this->totalpage+$this->nowindex);
  $begin=$this->nowindex-$plus+1;
  $begin=($begin>=1)?$begin:1;
  $return='';
  for($i=$begin;$i<$begin+$this->pagebarnum;$i++)
  {
   if($i<=$this->totalpage){
    if($i!=$this->nowindex)
        $return.=$this->_get_text($this->_get_link($this->_get_url($i),$i,$style));
    else 
        //$return.=$this->_get_text('<span style="font-size:16px;color:red;font-weight: bold;" class="'.$nowindex_style.'">'.$i.'</span>');
        $return.=$this->_get_text('<li><a href="#" class="cur">'.$i.'</a></li>');
   }else{
    break;
   }
   $return.="\n";
  }
  unset($begin);
  return $return;
 }
 /**
  * 获取显示跳转按钮的代码
  *
  * @return string
  */
 function select()
 {
   $return='<select name="PB_Page_Select">';
  for($i=1;$i<=$this->totalpage;$i++)
  {
   if($i==$this->nowindex){
    $return.='<option value="'.$i.'" selected>'.$i.'</option>';
   }else{
    $return.='<option value="'.$i.'">'.$i.'</option>';
   }
  }
  unset($i);
  $return.='</select>';
  return $return;
 }
 
 /**
  * 获取mysql 语句中limit需要的值
  *
  * @return string
  */
 function offset()
 {
  return $this->offset;
 }
 
 /**
  * 控制分页显示风格（你可以增加相应的风格）
  *
  * @param int $mode
  * @return string
  */
 function show($mode=1)
 {
	if($this->nowindex<=0){
		$this->nowindex=1;
	}
	
 	if($this->nowindex>$this->totalpage){
 		$this->nowindex=$this->totalpage;
 	}
 	if($this->totalpage<=1){
 		return;
 	}
  switch ($mode)
  {
   case '1':
   	 $this->first_page='首页';
    $this->last_page='尾页';
     // $this->next_page="<img src='img/table/next.gif' style='border:0;height:13px'/>&nbsp;";
   // $this->pre_page="&nbsp;<img src='img/table/up.png'style='border:0;height:13px'alt='上一页'/>&nbsp;";
    $this->next_page="下一页";
    $this->pre_page="上一页";
    return $this->first_page().$this->pre_page().$this->nowbar().$this->next_page().$this->last_page();
    break;
   case '2':
    $this->next_page='下一页';
    $this->pre_page='上一页';
    $this->first_page='首页';
    $this->last_page='尾页';
    return $this->first_page().$this->pre_page().'[第'.$this->nowindex.'页]'.$this->next_page().$this->last_page().'第'.$this->select().'页';
    break;
   case '3':
    $this->next_page='下一页';
    $this->pre_page='上一页';
    $this->first_page='首页';
    $this->last_page='尾页';
    return $this->first_page().$this->pre_page().$this->next_page().$this->last_page();
    break;
   case '4':
    $this->next_page='Next';
    $this->pre_page='Prev';
    return $this->pre_page().$this->nowbar().$this->next_page();
    break;
   case '5':
    return $this->pre_bar().$this->pre_page().$this->nowbar().$this->next_page().$this->next_bar();
    break;
  }
 }
/*----------------private function (私有方法)-----------------------------------------------------------*/
 /**
  * 设置url头地址
  * @param: String $url
  * @return boolean
  */
 private function _set_url($url="")
 {
  if(!empty($url)){
      //手动设置
   $this->url=$url.((stristr($url,'?'))?'&':'?').$this->page_name."=";
  }else{
      //自动获取
   /* if(empty($_SERVER['QUERY_STRING'])){
       //不存在QUERY_STRING时
    $this->url=$_SERVER['REQUEST_URI']."?".$this->page_name."=";
   }else{
    $this->_deal_url();
   } *///end if
      
  	$this->_deal_url();
  }//end if
 }
 
 /**
  * 处理URL为合法
  * @param : $urI   URI
  * */
 private function _deal_url()
 {
 	if(empty($this->url)){
 		$currentUrl = $this->_get_current_url();
 		$this->url = $currentUrl.(strpos($currentUrl,'?')?'':"?");
 	}
 	$urlArr = parse_url($this->url);
 	if(isset($urlArr['query'])){
 		parse_str($urlArr['query'],$params);
 		if(!empty($this->allow_key)){
 			foreach($params as $key=>$val){
 				$params[$key] = htmlspecialchars(strip_tags($val));
 				if(!in_array($key, $this->allow_key)){
 					unset($params[$key]);
 				}
 			}
 		}
 		
 		if(array_key_exists($this->page_name, $params)){
 			unset($params[$this->page_name]);
 		}
 		
 	}
 	$this->url = (empty($this->uri)?$urlArr['path']:$this->uri).'?'.(empty($params)?'':(http_build_query($params)));
 	
 	$last=$this->url[strlen($this->url)-1];
 	
 	if($last=='?'||$last=='&'){
 		$this->url.=$this->page_name."=";
 	}else{
 		$this->url.='&'.$this->page_name."=";
 	}
	
 }
 
 /**
  * 获取当前页面URL
  * @return : string url
  * */
 private function _get_current_url(){
 	$pageUrl = 'http';
 	if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on"){
 		$pageUrl .= "s";
 	}
 	$pageUrl .= "://";
 	if ($_SERVER["SERVER_PORT"] != "80"){
 		$pageUrl .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
 	}else{
 		$pageUrl .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
 	}
 	return $pageUrl;
 }
 
 /**
  * 设置当前页面
  *
  */
 private function _set_nowindex($nowindex)
 {
  if(empty($nowindex)){
   //系统获取
   
   if(isset($_GET[$this->page_name])){
    $nowindex=intval($_GET[$this->page_name]);
   }
  }else{
      //手动设置
   $nowindex=intval($nowindex);
  }
  $nowindex=$nowindex<1?1:$nowindex;
  $this->nowindex=$nowindex>$this->totalpage?$this->totalpage:$nowindex;
 }
  
 /**
  * 为指定的页面返回地址值
  *
  * @param int $pageno
  * @return string $url
  */
 function _get_url($pageno=1)
 {
  return $this->url.$pageno;
 }
 
 /**
  * 获取分页显示文字，比如说默认情况下_get_text('<a href="">1</a>')将返回[<a href="">1</a>]
  *
  * @param String $str
  * @return string $url
  */ 
 function _get_text($str)
 {
  return $this->format_left.$str.$this->format_right;
 }
 
 /**
   * 获取链接地址
 */
 function _get_link($url,$text,$style=''){
  $style=(empty($style))?'':'class="'.$style.'"';
  if($this->is_ajax){
      //如果是使用AJAX模式
   return '<a '.$style.' href="javascript:'.$this->ajax_action_name.'(\''.$url.'\')">'.$text.'</a>';
  }else{
   return '<li><a '.$style.' href="'.$url.'">'.$text.'</a></li>';
  }
 }
 /**
   * 出错处理方式
 */
 function error($function,$errormsg)
 {
     die('Error in file <b>'.__FILE__.'</b> ,Function <b>'.$function.'()</b> :'.$errormsg);
 }
}
?>
