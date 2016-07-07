<?php
include './ShortUrl.php';

$base_url = 'http://mc.cc/';
$url = $_POST['url'];

// check url format
if ( strlen($url) === 0 ) {
    exit ( urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('url不能为空'), 'long_url'=>$url))) );
}
if ( FALSE === filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
    exit ( urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('url格式异常'), 'long_url'=>$url))) );
}
// filter own url. eg: http://mc.cc/5ac1O1
if ( FALSE !== strpos($url, rtrim($base_url, '/')) ) {
    exit ( urldecode(json_encode(array('success'=>TRUE, 'short_url'=>$url, 'long_url'=>$url))) );
}

$short = new ShortUrl();
$short_url = rtrim($base_url, '/'). '/'. $short->conventUrl($url);
exit ( urldecode(json_encode(array('success'=>TRUE, 'short_url'=>urlencode($short_url), 'long_url'=>$url))) );
