<?php
$painter=$_GET['id'];
?>
<!DOCTYPE html>
<html>
<head> <title>Painter <?php echo"$painter" ?></title>
<?php include "src/header.html"; ?>
<style>
body{padding:5px;margin:auto;max-width:960px}
.card{margin-top:4px}
 </style>
 </head>
<body>
<script>
function ajax(options){
    var xhr = null;
    var params = formsParams(options.data);
    //创建对象
    if(window.XMLHttpRequest){
        xhr = new XMLHttpRequest()
    } else {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }
    // 连接
    if(options.type == "GET"){
        xhr.open(options.type,options.url + params,options.async);
        xhr.send(null)
    } else if(options.type == "POST"){
        xhr.open(options.type,options.url,options.async);
        xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        xhr.send(params);
    }
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            options.success(xhr.responseText);
        }
    }
    function formsParams(data){
        var arr = [];
        for(var prop in data){
            arr.push(prop + "=" + data[prop]);
        }
        return arr.join("&");
    }
 
}
ajax({
    url : "https://api.imjad.cn/pixiv/v2/?type=member&id=<?php echo"$painter" ?>",  // url---->地址
    type : "GET",   // type ---> 请求方式
    async : true,   // async----> 同步：false，异步：true 
    success : function(json){   //返回接受信息
       let data=JSON.parse(json);
       document.getElementById("get").innerHTML=
""
+"<div class='card' style='background:url(view.php/"
+data['profile']['background_image_url']
+");background-size:100% 100%;background-repeat:no-repeat;'>"
+"<div class='card-header'><figure class='avatar avatar-xl'><img src='view.php/"
+data['user']['profile_image_urls']['medium']
+"'></figure>"
+"<span class='avatar-title'>"
+data['user']['name']
+"</span></div>"
+"<div class='card-body'>"
+"<div><strong>id</strong>:"
+data['user']['id']
+"</div>"
+"<div><strong>account</strong>:"
+data['user']['account']
+"</div>"
+"<div><strong>comment</strong>:"
+data['user']['comment']
+"</div>"
+"<div><strong>gender</strong>:"
+data['profile']['gender']
+"</div>"
+"<div><strong>region</strong>:"
+data['profile']['region']
+"</div>"
+"<div><strong>twitter_account</strong>:"
+data['profile']['twitter_account']
+"</div>"
+"<div><strong>twitter_url</strong>:"
+data['profile']['twitter_url']
+"</div>"
+"</div>"
+"</div>";
    }
})
</script>
<?php include "src/top.html"; ?>
<h1>Painter <small class="label"><?php echo"$painter" ?></small></h1>
<div class="container">
  <div class="columns">
<div id="get"></div>
<?php
$json="https://api.imjad.cn/pixiv/v2/?type=member_illust&id=".$painter."&page=".$page;
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
