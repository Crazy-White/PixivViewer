<?php
function image_proxy($url){
//$url_cat = preg_replace('/i.pximg.net/i', 'i.pixiv.cat', $url);
$url_self = "view.php/".$url;
return $url_self;
}//代理函数
?>