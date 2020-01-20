<!DOCTYPE html>
<html>
 <head> 
  <title>PixivViewer</title> 
<?php include "src/header.html"; ?>
<link rel="stylesheet" href="src/index.css" /> 
 </head> 
 <body> 
  <ul class="step"> 
   <li class="step-item active"> <a href="#" class="tooltip" data-tooltip="Step 1">index</a> </li> 
   <li class="step-item"> <a href="#" class="tooltip" data-tooltip="Step 2">following</a> </li> 
   <li class="step-item"> <a href="#" class="tooltip" data-tooltip="Step 3">painter</a> </li> 
   <li class="step-item"> <a href="#" class="tooltip" data-tooltip="Step 4">painting</a> </li> 
  </ul> 
  <div class="container"> 
   <div class="columns"> 
    <div class="card column col-sm-12 col-md-12 col-6"> 
     <div class="card-header"> 
      <div class="card-title h5">
        PixivViwer 
      </div> 
      <div class="card-subtitle text-gray">
        by.Crazy白茫茫 
      </div> 
     </div> 
     <div class="card-body"> 
      <form method="get" action="painting.php"> 
       <div class="input-group"> 
        <span class="input-group-addon">图片</span> 
        <input type="text" class="form-input" placeholder="请输入pixiv_id" name="id"/> 
        <input class="btn btn-primary input-group-btn" value="查看" type="submit"/> 
       </div> 
      </form> 
      <form method="get" action="painter.php"> 
       <div class="input-group"> 
        <span class="input-group-addon">画师</span> 
        <input type="text" class="form-input" placeholder="请输入pixiv_id" name="id"/> 
        <input class="btn btn-primary input-group-btn" value="查看" type="submit"/> 
       </div> 
      </form> 
      <form method="get" action="following.php"> 
       <div class="input-group"> 
        <span class="input-group-addon">关注</span> 
        <input type="text" class="form-input" placeholder="请输入pixiv_id" name="id"/> 
        <input class="btn btn-primary input-group-btn" value="查看" type="submit"/> 
       </div> 
      </form> 
     </div> 
     <div class="card-footer"> 
      <form method="post" action="https://support.qq.com/product/97039"> 
       <!--留言--> 
       <input type="submit" value="有问题?去留言。" class="btn" /> 
      </form> 
      <button onclick="window.open('rank.php')" class="btn btn-primary">排行榜</button>
     </div> 
    </div> 
    <!--搜索-->
    <div class="card column col-sm-12 col-md-12 col-6"> 
     <div class="card-header"> 
      <div class="card-title h5">
        按关键词搜索: 
      </div> 
      <div class="card-subtitle text-gray">
        (点击图片查看详情) 
      </div> 
     </div> 
     <div class="card-body"> 
<!--检索栏-->
      <div id="search" class="input-group"> 
       <input class="form-input main-search" type="text" maxlength="64" name="keyword" value="香風智乃 1000users入り" onkeypress="if(event.keyCode==13){document.getElementsByName('search')[0].click();return false;}" style="width:250px;" />&nbsp; 
       <input class="form-input input-group-addon main-input" type="text" maxlength="3" name="page" value="1" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" onkeypress="if(event.keyCode==13){document.getElementsByName('search')[0].click();return false;}" style="width:25px;" />&nbsp; 
       <input class="btn btn-primary input-group-btn main-btn" type="button" name="search" value="搜索" />
      </div>
      <br />
<!--输出列表--> 
      <ul id="search_result"></ul>
      <br /> 
      <span id="prev_page" class="btn">上一页</span> 
      <span id="next_page" class="btn">下一页</span> 
     </div> 
     <div class="card-footer">
<!--查看栏-->
       id||rank mode: 
   <div class="input-group"> 
      <input class="form-input" type="text" maxlength="10" name="id" value="" onkeypress="if(event.keyCode==13){get();return false;}" style="width:150px;" />&nbsp; 
      <input class="btn input-group-btn" type="button" name="view" value="预览" onclick="get()" /> 
</div>
      <p> <input class="form-input input-sm" type="text" name="code" style="width:96%;max-width:800px;" /> </p> 
      <div class="spinner" id="loading" style="display: none;"> 
       <div class="rect1"></div> 
       <div class="rect2"></div> 
       <div class="rect3"></div> 
       <div class="rect4"></div> 
       <div class="rect5"></div> 
      </div> 
      <a id="pixiv_url" target="_blank" href=""><img id="pixiv_img" class="s-rounded img-responsive" alt="可能未载入图片" src="" /></a>
      <br />
      <br /> 
<!--输出信息-->
<span class="meta"></span>
      <br /> 
     </div> 

    </div>
   </div> 
  </div> 
     <script src="src/index.js">
     <script src="https://api.imjad.cn/js/smoothscroll.js"></script> 
     <script>
(function(){
var forEach = function (array, callback, scope) {
for (var i = 0; i < array.length; i++) {
callback.call(scope, i, array[i]);
}
};
var anchorLinks = document.querySelectorAll("a[href^='#']");
if (window.scrollTo) {
forEach(anchorLinks, function(index, element) {
var target = document.getElementById(element.getAttribute("href").substring(1));
element.addEventListener("click", function(el) {
el.preventDefault();
scrollTo({"behavior": "smooth", "top": target.offsetTop}); 
})
});
}
})();
</script>
  <?php include "src/footer.html"; ?>
 </body>
</html>
