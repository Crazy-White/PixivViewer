<?php
//检测curl(可选)
/*
if (!in_array('curl', get_loaded_extensions())) {
            throw new Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');
        }
*/

$url = "{$_SERVER['REQUEST_URI']}";
preg_match('/[a-zA-z]+:\/\/[^\s]*/', $url, $url);
//$confirm确定是否输入url，是则1否0。 方法var_dump($confirm,$url);
$url=$url[0];

//设置header
header('Content-type:image/jpeg');

//初始化curl
$ch = curl_init();

//设置选项
curl_setopt($ch, CURLOPT_URL, "$url");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 40);
curl_setopt($ch, CURLOPT_REFERER, 'https://www.pixiv.net/');  
curl_setopt($ch, CURLOPT_USERAGENT, "PixivIOSApp/6.7.1 (iOS 10.3.1; iPhone8,1)");                      

//直接输出内容(图片)
curl_exec($ch);

//释放curl句柄
curl_close($ch);