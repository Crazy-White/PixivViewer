<?php 
include "static/header.html"; 
require "function.php";
/*输入变量*/
$id = $_GET["id"];
(empty($id))?$error=array("id is not defined"):"";
if(isset($error)){
    echo error_print($error);
}
$api = "https://api.imjad.cn/pixiv/v2/?type=illust&id={$id}";
/*获取数据*/
try{
$painting = get_data($api);
//数据变量
$painting = $painting["illust"];
//多p图片
$painting["meta_single_page"]["original_image_url"]=(empty($painting["meta_single_page"])) ? $painting["meta_pages"][0]["image_urls"]["original"] : $painting["meta_single_page"]["original_image_url"];
//判断p数
$count_pages=(empty($painting["meta_pages"])) ? 0 : $painting["meta_pages"];
$count_value=true;
?> 
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<h1>illust <?php echo "$id"; ?></h1>
<ul class="mui-tabs__bar mui-tabs__bar--justified">
  <li class="mui--is-active"><a data-mui-toggle="tab" data-mui-controls="pane-api-1">插画详情</a></li>
  <li><a data-mui-toggle="tab" data-mui-controls="pane-api-2">画师信息</a></li>
  <li><a data-mui-toggle="tab" data-mui-controls="pane-api-3">相关作品</a></li>
</ul>
<div class="mui-tabs__pane mui--is-active" id="pane-api-1">
<!--插画详情start-->
<img src="https://cn.tql.ink:4443/images/fubuki_cute.gif" class="illust-img lazyload mui--z1" data-original="<?php echo image_proxy($painting["meta_single_page"]["original_image_url"]); ?>" alt="p0">
<?php
//这里输出分p图片
if($count_value==true){
for ($j=1;$j<$count_pages;$j++){
echo "<img src='https://cn.tql.ink:4443/images/fubuki_cute.gif' data-original='".image_proxy($painting["meta_pages"][$j]["image_urls"]["original"])."' class='illust-img lazyload mui--z1' alt='p{$j}'>";
     }
}


foreach ($painting["tags"] as $value)
{
    echo "<div class=\"chip\"><span class=\"chip-icon\">tag</span><span class=\"chip-title\">{$value["name"]}</span></div>";
}
?>
	<br />
	<div class="chip"><span class="chip-icon">宽</span><span class="chip-title"><?php echo "{$painting['width']}"; ?></span></div>
	<div class="chip"><span class="chip-icon">高</span><span class="chip-title"><?php echo "{$painting['height']}"; ?></span></div>
	<div class="chip"><span class="chip-icon">⊙</span><span class="chip-title"><?php echo "{$painting["total_view"]}"; ?></span></div>
	<div class="chip"><span class="chip-icon">☆</span><span class="chip-title"><?php echo "{$painting["total_bookmarks"]}"; ?></span></div>
<!--插画详情end--></div>
<div class="mui-tabs__pane" id="pane-api-2"><!--画师信息start-->
	  <div class="mui-panel">
		    <div class="card-header">
      <img class="card-header-avatar" src="<?php echo image_proxy($painting["user"]["profile_image_urls"]["medium"]); ?>"
      alt="icon">
      <div class="card-header-title">
        <?php echo $painting["user"]["name"]; ?>
      </div>
      <div class="card-header-subtitle">
        id:<?php echo $painting["user"]["id"]; ?>｜account:<?php echo $painting["user"]["account"]; ?>
      </div>
    </div>
    <div id="panel2-gender"></div>
    <div id="panel2-region"></div>
    <div id="panel2-birth"></div>
    <div id="panel2-total_follow_users"></div>
    <div id="panel2-total_mypixiv_users"></div>
    <div id="panel2-twitter_account"></div>
    <div id="panel2-twitter_url"></div>
    <div id="panel2-background_image_url"></div>
    <div class="mui-divider"></div>
    For more illustrations,visit <a href="list.php?type=member_illust&id=<?php echo $painting["user"]["id"]; ?>">this.</a>
	</div>
<!--画师信息end--></div>
<div class="mui-tabs__pane" id="pane-api-3"><!--相关作品start-->
	<div class="mui-panel">
		<div id="panel3-title"></div>
	For more illustrations,visit <a href="list.php?type=related&id=<?php echo $id; ?>">this.</a>
	</div>
<!--相关作品end--></div>
<script>
	document.title="illust <?php echo "$id"; ?>";
  var paneIds = ['pane-api-1', 'pane-api-2', 'pane-api-3'],
      currPos = 0;

  function activateNext() {
    // increment id
    currPos = (currPos + 1) % paneIds.length;

    // activate tab
    mui.tabs.activate(paneIds[currPos]);
  }
  
  //$('#pane-api-2')
  //axios获取数据
  axios.get('https://api.imjad.cn/pixiv/v2/?type=member&id=<?php echo $painting["user"]["id"]; ?>')
  .then(function (response) {
    $('#panel2-gender').html('gender:'+response.data.profile.gender);
    $('#panel2-region').html('region:'+response.data.profile.region);
    $('#panel2-total_follow_users').html('total_follow_users:'+response.data.profile.total_follow_users);
    $('#panel2-total_mypixiv_users').html('total_mypixiv_users:'+response.data.profile.total_mypixiv_users);
    $('#panel2-twitter_account').html('twitter_account:'+response.data.profile.twitter_account);
    $('#panel2-birth').html('birth:'+response.data.profile.birth);
    $('#panel2-twitter_url').html('twitter_url:'+response.data.profile.twitter_url);
    $('#panel2-background_image_url').html('background_image_url:'+response.data.profile.background_image_url);
    //结束
  })
  .catch(function (error) {
    console.log(error);
  });
</script>
<button class="mui-btn mui-btn--primary mui--pull-right" onclick="activateNext();">Next</button>
    <div class="mui--clearfix"></div>
<?php
}catch(Exception $e)
{
    echo 'Message: ' .$e->getMessage();
}
include "debug.php";
include "static/footer.html"; 
 ?>