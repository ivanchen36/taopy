<?php
$url = $_GET['u'];
$url = str_replace("*", "=", $url);
$url = strrev($url);
$url = base64_decode($url);
if (substr($url, 0, 4) != "http") $url = "/pin.html";
?><meta http-equiv="refresh" content="1;url='<?php echo $url ?>'" /><a href='<?php echo $url ?>' display='none' id='url' /><script type='text/javascript'>setTimeout("document.getElementById('url').click()","0")</script>
