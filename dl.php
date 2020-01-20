<?php
require('src/dl.class.php');
//设置下载文件的url
$url = $_GET['url'];

//设置分段下载的字节大小(1M)
$burst = 1048576*10;

//设置保存到服务器本地的文件名
preg_match('/\w+(.jpg|.png|.jpeg|.gif|.bmp)/', $url, $filename);
//$url = preg_replace('/i.pximg.net/i', 'i.pixiv.cat', $url);

//调用
try
{
    //初始化下载器
    $download = new download();

    //开始下载
    $download->setUrl($url)->setBurst($burst)->saveFile('download/'.$filename[0]);
}
catch (Exception $exception)
{
    //var_dump($exception->getMessage());

}


    $err=$exception->getMessage();
    if(isset($err)){
    $msg = array("statue"=>"fail","err"=>"$err","msg"=>"下载失败");}
    else{$msg=array("statue"=>"success","msg"=>"下载成功");}
    echo json_encode($msg);

//echo "<script>alert('任务执行完毕！');history.go(-1);</script>";  