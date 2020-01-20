<?php include "static/header.html"; ?>
<?php
/*输入变量*/
$id=$_GET['id'];
$type=$_GET['type'];
$page=(empty($_GET['page'])) ? 1 : $_GET['page'];
$mode=$_GET['mode'];
$quality=(empty($_GET['quality'])) ? "large" : $_GET['quality'];
//quality可选参数[square_medium/medium/large]
/*获取数据*/
require "function.php";//函数
$api = "https://api.imjad.cn/pixiv/v2/?type={$type}&id={$id}&page={$page}&mode={$mode}";
$data = file_get_contents($api);
//$data=preg_replace('/i.pximg.net/i','i.pixiv.cat',$data);
$data=json_decode($data,true);
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
<?php include "debug.php"; ?>
<?php include "static/footer.html"; ?>