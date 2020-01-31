<?php
include "static/header.html";
require "function.php";
/*输入变量*/
$id=$_GET['id'];
$type=$_GET['type'];
$page=(empty($_GET['page'])) ? 1 : $_GET['page'];
$mode=$_GET['mode'];
$quality=$_GET['quality'];
//quality可选参数[square_medium/medium/large]
/*处理变量*/
$error=(empty($id) && $type!="rank")?array("id is not defined"): array("");
(empty($type))?array_push($error,"type is not defined") : "";
$mistake=($page<1)?array("page can not fewer than 1") : array("");
if($type=="rank" && isset($mode)){
$mode_check=array("day","week","month","week_rookie","week_original","day_male","day_female");
	if(!in_array($mode,$mode_check)){
		array_push($mistake, "mode can not be {$mode}");
		$mode="week";
    }
}

if(empty($_GET['quality'])){
	$quality="large";
}else{
$quality_check=array("square_medium","medium","large");
if(!in_array($quality,$quality_check)){
	array_push($error, "quality can not be {$quality}");
	$quality="large";
    }
}
/*获取数据*/
$api = "https://api.imjad.cn/pixiv/v2/?type={$type}&id={$id}&page={$page}&mode={$mode}";
try{
     $data=get_data($api);
     $count=count($data["illusts"]);

?>
<script>
	document.title = "<?php echo "$type"." "."$id"; ?>";
</script>
        <h1><?php echo "$type"." "."$id"; ?></h1>
<div class="container">
  <div class="columns">
        	        <!--echo start-->
        	<?php
        for ($i = 0; $i < $count; $i++) {
    	$forlist=$data["illusts"][$i];  
    echo "<div class='column col-6 col-sm-12 col-xs-12'>
  <div class='mui-panel'>
    <img class='panel-img lazyload' alt='{$forlist["title"]}' src='https://cn.tql.ink:4443/images/fubuki_cute.gif'
    data-original='".image_proxy($forlist["image_urls"][$quality])."'>
    <div class='card-header'>
      <img class='card-header-avatar' src='".image_proxy($forlist["user"]["profile_image_urls"]["medium"])."'
      alt='{$forlist["user"]["name"]}'>
      <div class='card-header-title'>
        {$forlist["title"]}｜{$forlist["id"]}
      </div>
      <div class='card-header-subtitle'>
        {$forlist["user"]["name"]}｜{$forlist["user"]["id"]}
      </div>
    </div>
    <a class='mui-btn mui-btn--primary mui-btn--small mui-btn--flat' href='illust.php?id={$forlist["id"]}'>
      Detail
    </a>
    <a class='mui-btn mui-btn--primary mui-btn--small mui-btn--flat' href='list.php?type=related&id={$forlist["id"]}'>
      Related
    </a>
    <a class='mui-btn mui-btn--small mui-btn--primary mui-btn--raised' href='list.php?type=member_illust&id={$forlist["user"]["id"]}'>
    	illustrator
    </a>
  </div>
</div>";
}
?>
                    <!--echo end-->
  </div>
</div>
<?php 
}catch(Exception $e)
{
    echo 'Message: ' .$e->getMessage();
}
echo error_print($error).error_print($mistake);
include "debug.php";
include "static/footer.html";
 ?>