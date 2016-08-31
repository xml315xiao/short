<?php
include __DIR__. DIRECTORY_SEPARATOR. 'ShortUrl.php';

$base_url = 'http://mc.cc/';
$url = isset($_GET['url']) ? $_GET['url'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

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

// verify
if ( strlen($token) === 0) {
    exit ( urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('校验码不能为空'), 'long_url'=>$url))) );
}

if (md5(md5($url. 'V0utjFzqmKmXAYGonmrM9')) != $token) {
    exit ( urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('校验码异常'), 'long_url'=>$url))) );
}

$short = new ShortUrl();
$short_url = rtrim($base_url, '/'). '/'. $short->conventUrl($url, 'app');
exit ( urldecode(json_encode(array('success'=>TRUE, 'short_url'=>urlencode($short_url), 'long_url'=>$url))) );
