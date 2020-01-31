<?php
require "function.php"; 
include "static/header.html"; 
//获取变量
$id=$_GET['id'];
//处理变量
$page=(empty($_GET['page'])) ? 1 : $_GET['page'];
$mistake=($page<1)?array("page can not fewer than 1") : array("");
(empty($id))?$error=array("id is not defined"):"";
if(isset($error)){
    echo error_print($error);
}
    echo error_print($mistake);
$api = "https://api.imjad.cn/pixiv/v2/?type=following&id={$id}&page={$page}";
try{
$data=get_data($api);
$count = count($data["user_previews"]);
?>
<script>
	document.title = "following <?php echo "$id"; ?>";
</script>
        <h1>following <?php echo "$id"; ?></h1>
        	  <div class="mui-row">
        	        <!--输出数据start-->
        	<?php
        for ($i = 0; $i < $count; $i++) {
    //数组赋值
    $forlist = $data["user_previews"][$i]["user"];
    //输出
    echo "<div class='mui-col-md-6'>
  <a href='list.php?type=member_illust&id={$forlist["id"]}'>
    <div class='mui-panel'>
      <div class='card-header'>
        <img class='card-header-avatar' src='".image_proxy($forlist["profile_image_urls"]["medium"])."'
        alt='{$forlist["name"]}'>
        <div class='card-header-title'>
          {$forlist["name"]}
        </div>
        <div class='card-header-subtitle'>
          {$forlist["id"]}
        </div>
      </div>
     </div>
  </a>
  </div>";
}
        
?>
     
                <!--输出数据end-->
        </div>
<!--上下页-->
	<div class="mui-container-fluid">
  
<table width="100%">
  <tr>
    <td width="50%" style="padding-bottom:20px;">
      
      <a href="<?php 
echo "?id={$id}&page=".pagination($page, -1);
?>" style="text-decoration:none;font-size:20px;">&laquo; Previous <small><?php 
echo pagination($page, -1); ?></small></a>
      
    </td>
    <td width="50%" class="mui--text-right" style="padding-bottom:20px;">
      
      <a href="<?php 
echo "?id={$id}&page=".pagination($page, 1);
?>" style="text-decoration:none;font-size:20px;"><small><?php 
echo pagination($page, 1); ?></small> Next &raquo;</a>
      
    </td>
  </tr>
</table>

</div>
<?php 
}catch(Exception $e)
{
    echo 'Message: ' .$e->getMessage();
}
include "debug.php";
include "static/footer.html"; 
?>