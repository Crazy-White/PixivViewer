<?php include "static/header.html"; ?>
        <h1>PixivViewer</h1>
        <p>重构版本,目前仍在开发状态</p>
        <div class="mui--text-center">
        	<img src="static/logo.png" alt="logo">
</div>
        <form class="mui-form" method="get" action="illust.php">
  <div class="mui-textfield mui-textfield--float-label">
    <input type="number" name="id">
    <label>图片</label>
  </div>
  <button type="submit" class="mui-btn mui-btn--raised mui--pull-right">查看</button>
    <div class="mui--clearfix"></div>
</form>
<!--第一个form-->
	     <form class="mui-form" method="get" action="list.php">
  <div class="mui-textfield mui-textfield--float-label">
    <input type="number" name="id">
    <label>画师</label>
    <input type="hidden" name="type" value="illust_member">
  </div>
  <button type="submit" class="mui-btn mui-btn--raised mui--pull-right">查看</button>
    <div class="mui--clearfix"></div>
</form>
<!--第二个form-->
	    <form class="mui-form" method="get" action="following.php">
  <div class="mui-textfield mui-textfield--float-label">
    <input type="number" name="id">
    <label>关注</label>
  </div>
  <button type="submit" class="mui-btn mui-btn--raised mui--pull-right">查看</button>
    <div class="mui--clearfix"></div>
</form>
<!--第三个form-->
	<div class="mui-divider"></div>
	<button class="mui-btn mui-btn--raised" onclick="window.open('list.php?type=rank')">周榜</button>
<?php include "static/footer.html"; ?>