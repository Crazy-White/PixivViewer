<?php
//判断mode
$mode=(isset($_GET['mode']))?$_GET['mode']:"week";
?>
<!DOCTYPE html>
<html>
<head> <title>Rank <?php echo"$mode" ?></title>
<?php include "src/header.html"; ?>
<style>
body{padding:5px;margin:auto;max-width:960px}
.card{margin-top:4px}
.breadcrumb-item>a{color:#66758c;}
 </style>
 </head>
<body>
<?php include "src/top.html"; ?>
<h1>Rank <small class="label"><?php echo"$mode" ?></small></h1>
<!--切换-->
<ul class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="rank.php?mode=day">Day</a>
  </li>
  <li class="breadcrumb-item">
    <a href="rank.php?mode=week">Week</a>
  </li>
  <li class="breadcrumb-item">
    <a href="rank.php?mode=month">Month</a>
  </li>
</ul>
<!--显示-->
<div class="container">
  <div class="columns">
<?php
$json="https://api.imjad.cn/pixiv/v2/?type=rank&mode=".$mode;
$data=file_get_contents($json);
//$data=preg_replace('/i.pximg.net/i','i.pixiv.cat',$data);
$data=json_decode($data,true);
$count=count($data["illusts"]);
for ($i=0;$i<$count;$i++){ //数组赋值
$forlist=$data["illusts"][$i];
$painting[$i]["title"]=$forlist["title"];
$painting[$i]["id"]=$forlist["id"];
$painting[$i]["urls"]=$forlist["image_urls"];
echo("<div class=\"column col-6 col-sm-12 col-xs-12\">
<div class=\"card\">
  <div class=\"card-image\">
    <img src=\"view.php/".$painting[$i]["urls"]["large"]."\" class=\"img-responsive\">
  </div>
  <div class=\"card-header\">
    <div class=\"card-title h5\">".$painting[$i]["title"]."</div>
    <div class=\"card-subtitle text-gray\">".$painting[$i]["id"]."</div>
  </div>
  <div class=\"card-body\">
  </div>
  <div class=\"card-footer\">
    <button onclick=\"window.open('painting.php?id=".$painting[$i]["id"]."')\" class=\"btn btn-primary\">Detail</button>
    <button onclick=\"window.open('related.php?id=".$painting[$i]["id"]."')\" class=\"btn btn-primary\">Related</button>
  </div>
</div>
</div>
");
}
?>
</div>
</div>
<?php include "src/footer.html"; ?>
</body>
</html>
