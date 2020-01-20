<!DOCTYPE html>
<html>
<head> <title>Error</title>
<?php include "src/header.html"; ?>
<style>
body{padding:5px;margin:auto;max-width:960px}
 </style>
 </head>
<body>

<div class="empty">
  <div class="empty-icon">
    <i class="icon icon-cross"></i>
  </div>
  <p class="empty-title h4">出错了</p>
  <p class="empty-subtitle">如果你的操作没有问题，那么可能是是api服务器没有刷新，此时等待几分钟后即可。</p>
  <div class="empty-action">
    <a href="index.php"><button class="btn btn-primary">回首页</button></a>
    <a href="rank.php"><button class="btn btn-primary">排行榜</button></a>
  </div>
</div>
<?php include "src/footer.html"; ?>
</body>
</html>