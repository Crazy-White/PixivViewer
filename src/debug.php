<?php
//判断$_SERVER['HTTP_REFERER']防止报错
$_SERVER['HTTP_REFERER'] = (empty($_SERVER['HTTP_REFERER'])) ? 'NULL' : $_SERVER['HTTP_REFERER'];
echo "<div class=\"toast\">
<big>DEBUG MODE</big><br>
<strong>api:</strong> <a href=\"{$api}\">{$api}</a><br>
<strong>id:</strong> {$id}<br>
<strong>referer:</strong> {$_SERVER['HTTP_REFERER']}<br>
<strong>script:</strong> {$_SERVER['PHP_SELF']}<br>
<strong>server_ip:</strong> {$_SERVER['SERVER_ADDR']}<br>
<strong>user_ip:</strong> {$_SERVER['REMOTE_ADDR']}<br>
</div>";
?>