<?php 
$id=$_GET["id"];
$api = "https://api.imjad.cn/pixiv/v2/?type=illust&id={$_GET["id"]}";
$json = file_get_contents($api);
//$json = preg_replace('/i.pximg.net/i', 'i.pixiv.cat', $json);
$painting = json_decode($json, true);
//读完json后，检测是否出错
if (isset($painting['error']['user_message'])){header("Location: error.php");}
$painting = $painting['illust'];
$tag=$painting['tags'];
//多p图片
if(empty($painting['meta_single_page'])){
$painting['meta_single_page']['original_image_url']=$painting['meta_pages'][0]['image_urls']['original'];
$count_pages=count($painting['meta_pages']);
$count_value=true;
}
?>
<!DOCTYPE HTML>
<html>
<head> <title>Painting <?php echo "{$_GET["id"]}"; ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/picturepan2/icons.css/dist/icons.min.css">
<?php include "src/header.html"; ?>
<style>
body{padding:5px;margin:auto;max-width:960px}
 </style>
<script>
function download{
var href = window.location.href;
window.open(href+"&dl=yes");
}
</script>
 </head>
<body>
<?php include "src/top.html"; ?>
              <div class="card">
                  <div class="card-image">
<img class="img-responsive" src="view.php/<?php echo "{$painting['meta_single_page']['original_image_url']}"; ?>" alt="p0" download="pixiv">
<?php
//这里输出分p图片
if($count_value==true){
for ($j=1;$j<$count_pages;$j++){
echo "<img src=\"view.php/{$painting['meta_pages'][$j]['image_urls']['original']}\" class=\"img-responsive\" alt=\"p{$j}\">";
     }
//尾部
}
 ?>

</div>
                  <div class="card-header">
                    <button id="ajaxButton" type="button" class="btn btn-primary float-right"><i class="icon icon-download"></i></button>
                    <div class="card-title h5"><?php echo "{$painting['title']}"; ?></div>
                    <div class="card-subtitle text-gray"><?php echo "$id"; ?></div>
                  </div>
                  <div class="card-body">
<i class="icon icon-time"></i> <?php echo "{$painting['create_date']}"; ?><br />
<?php
foreach ($tag as $value)
{
    echo "<span class=\"chip\">".$value['name']."</span>";
}

?>
<br />
 <div class="chip">
   <figure class="avatar avatar-sm" data-initial="宽" style="background-color: #5755d9;"></figure><?php echo "{$painting['width']}"; ?>
                </div>
<div class="chip">
   <figure class="avatar avatar-sm" data-initial="高" style="background-color: #5755d9;"></figure><?php echo "{$painting['height']}"; ?>
                </div>
<div class="chip">
   <figure class="avatar avatar-sm" data-initial="查看" style="background-color: #5755d9;"></figure><?php echo "{$painting['total_view']}"; ?>
                </div>
<div class="chip">
   <figure class="avatar avatar-sm" data-initial="收藏" style="background-color: #5755d9;"></figure><?php echo "{$painting['total_bookmarks']}"; ?>
                </div>
<!--下面输出画师info-->
<div class="divider text-center" data-content="画师"></div>
<div class="tile tile-centered">
  <div class="tile-icon">
<!--      <figure class="avatar avatar-lg" data-initial="" style="background-color: #fff;">
  <img src="p.png" alt="icon">
</figure>
--><i class="icon icon-2x icon-photo"></i>
  </div>
  <div class="tile-content">
    <div class="tile-title"><?php echo "{$painting['user']['name']}"; ?></div>
    <small class="tile-subtitle text-gray"><?php echo "{$painting['user']['id']}"; ?> | <?php echo "{$painting['user']['account']}"; ?></small>
  </div>
  <div class="tile-action">
    <button class="btn btn-link" onclick="window.open('painter.php?id=<?php echo "{$painting['user']['id']}"; ?>')">
      <i class="icon icon-link"></i>
    </button>
<button class="btn btn-link" onclick="window.open('related.php?id=<?php echo "{$id}"; ?>')">
      <i class="icon icon-bookmark"></i>
    </button>
  </div>
</div>
<!--输出完毕-->
 <div class="card-footer">
<div id="showMsg"></div>

</div>
                </div>
<?php include('src/debug.php'); ?>
              </div>


<script>
(function() {
  var httpRequest;
  document.getElementById("ajaxButton").addEventListener('click', makeRequest);

  function makeRequest() {
    httpRequest = new XMLHttpRequest();

    if (!httpRequest) {
      alert('Giving up :( Cannot create an XMLHTTP instance');
      return false;
    }
    httpRequest.onreadystatechange = alertContents;
    httpRequest.open('GET', 'dl.php?url=<?php echo "{$painting['meta_single_page']['original_image_url']}"; ?>');
    httpRequest.send();
  }

  function alertContents() {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
//获取数据
        var json=httpRequest.responseText;
        var jsons=JSON.parse(json);
//输出内容
document.getElementById("showMsg").innerHTML= "<div class='toast'>" + jsons['msg'] + "</div>" ;

//结束
      } else {
        document.getElementById("showMsg").innerHTML="<div class='toast'>出错</div>";
      }
    }
  }
})();
</script>
<?php include "src/footer.html"; ?>
</body>
</html>
