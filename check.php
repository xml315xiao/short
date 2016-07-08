<?php
include './ShortUrl.php';

$url = $_GET['short'];
$short = new ShortUrl();
$result = $short->checkEnable($url);

exit ( urldecode(json_encode(array('success'=>TRUE, 'short_url'=>$url, 'enable'=>$result))) );