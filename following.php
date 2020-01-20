<?php
$id=$_GET["id"];
function pagination($page, $num)
{//上下页
    $page = intval($page);
    $page = $page + $num;
    return strval($page);
}
function confirm($word, $default)
{//确认变量
    if (!isset($word)) {
        if (isset($default)) {
            $word = $default;
        } else {
            $word = "";
        }
    }
    return $word;
}
//上面函数
$page = confirm($_GET["page"], "1");
if ($page == "1"||$page==1) {
    $disable = "disable";
} else {
    $disable = "";
}
?>
<html>
<head> <title>Following <?php echo "$id"; ?></title>
<?php include "src/header.html"; ?>
<style>
body{margin:5px}
 </style>
 </head>
<body>
<header class="navbar">
  <section class="navbar-section">
    <a href="https://blog.poo.li" class="btn btn-link">博客</a>
    <a href="https://support.qq.com/products/97039" class="btn btn-link">留言</a>
  </section>
  <section class="navbar-center">
    <!--图标--><img class="avatar" src="https://images.weserv.nl/?url=https://avatars3.githubusercontent.com/u/24633623?s=400&v=4">
  </section>
  <section class="navbar-section">
    <a href="https://space.bilibili.com/37090102/" class="btn btn-link">Bilibili</a>
    <a href="https://github.com/Crazy-White/" class="btn btn-link">GitHub</a>
  </section>
</header>
<!--上面头部-->
<?php include "src/top.html"; ?>
<!--下面内容--> 
<h1>Following <small class="label"><?php echo"$id" ?></small></h1>
<div class="container">
<div class="columns">
<?php 
//include_once("config.php");
$api = "https://api.imjad.cn/pixiv/v2/?type=following&id=".$id."&page={$page}";
$json = file_get_contents($api);
//获取完json后，检测是否出错
//if (strlen($json)<50){header("Location: error.php")};
//从pixiv.cat获取icon
$json = preg_replace('/i.pximg.net/i', 'i.pixiv.cat', $json);
$data = json_decode($json, true);
//var_dump($data["user_previews"][0]["user"]);
//echo($json);
$count = count($data["user_previews"]);
for ($i = 0; $i < $count; $i++) {
    //数组赋值
    $forlist = $data["user_previews"][$i]["user"];
    $painter[$i]["name"] = $forlist["name"];
    $painter[$i]["id"] = $forlist["id"];
    $painter[$i]["avatar"] = $forlist["profile_image_urls"]["medium"];
    //var_dump($painter);
    echo "<div class=\"column col-6 col-sm-12 col-xs-12\"><a href=\"painter.php?id=" . $painter[$i]["id"] . "\"><p class=block>\r\n<figure class=\"avatar avatar-xl\">\r\n  <img src=\"" . $painter[$i]["avatar"] . "\" alt=\"" . $painter[$i]["name"] . "\">\r\n</figure>\r\n<span class=name>" . $painter[$i]["name"] . "</span></p></a></div>";
}
?>
</div>
<ul class="pagination">
  <li class="page-item page-prev">
    <a href="<?php 
echo "?id={$id}&page=".pagination($page, -1);
?>">
      <div class="page-item-subtitle">Previous</div>
      <div class="page-item-title h5">第<?php 
echo pagination($page, -1); ?>页</div>
    </a>
  </li>
  <li class="page-item page-next">
    <a href="<?php 
echo "?id={$id}&page=".pagination($page, 1);
?>">
      <div class="page-item-subtitle">Next</div>
      <div class="page-item-title h5">第<?php 
echo pagination($page, 1); ?>页</div>
    </a>
  </li>
</ul>
</div>
 <?php include"src/debug.php"; ?>
 <?php include "src/footer.html"; ?>
</body>
</html>