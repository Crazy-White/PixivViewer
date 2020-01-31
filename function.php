<?php
/*解析数据*/
function get_data($api){
$data=file_get_contents($api);
if(strlen($data)<37){
	throw new Exception("获取api异常<br />");
}
//$data=preg_replace('/i.pximg.net/i','i.pixiv.cat',$data);
$data=json_decode($data,true);
if(empty($data) || isset($data["error"])){
	throw new Exception("获取api失败<br />");
    }
    return $data;
}
/*图片代理*/
function image_proxy($url){
//$url_cat = preg_replace('/i.pximg.net/i', 'i.pixiv.cat', $url);
$url_self = "view.php/".$url;
return $url_self;
}
/*输出错误*/
function error_print($array){
	foreach($array as $msg_line){
		$msg_block=$msg_block."Message: ".$msg_line."<br />";
		return $msg_block;
    }
}
/*上下页*/
function pagination($page, $num)
{
    $page = intval($page);
    $page = $page + $num;
    return strval($page);
}
?>