<?php
$mode=(isset($_GET['mode']))?$_GET['mode']:"weekly";
$api="https://api.imjad.cn/pixiv/v1/?type=rank&mode=".$mode;

//设置保存到服务器本地的文件名
$filename="cache/".$mode.".json";

$somecontent = file_get_contents("$api");

// 首先
if(is_file($filename)){
if(unlink($filename)){
echo "删除成功";
}else{echo "删除失败";}
}
//然后
if(file_put_contents($filename,$somecontent)){
echo "写入成功";
}else{echo "写入失败";}
