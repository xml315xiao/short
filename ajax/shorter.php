<?php
session_start();
include __DIR__. DIRECTORY_SEPARATOR. 'ShortUrl.php';

$base_url = 'http://oo.cc/';
$url = $_GET['url'];
$customer = isset($_GET['customer']) ? $_GET['customer'] : '';

// check url format
if ( strlen($url) === 0 ) {
    exit ( urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('url不能为空'), 'long_url'=>$url))) );
}
if ( FALSE === filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
    exit ( urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('url格式异常'), 'long_url'=>$url))) );
}
// filter own url. eg: http://oo.cc/5ac1O1
if ( FALSE !== strpos($url, rtrim($base_url, '/')) ) {
    exit ( urldecode(json_encode(array('success'=>TRUE, 'short_url'=>$url, 'long_url'=>$url))) );
}

// check user login
if ( ! isset($_SESSION['user']) ) {
    exit ( urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('用户未登陆'), 'long_url'=>$url))) );
}

$short = new ShortUrl();
if (strlen($customer) > 0) {
    if (strlen($customer) < 5 && $_SESSION['role'] < 2) {
        exit ( urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('当前用户权限不够'), 'long_url'=>$url))) );
    }
    $filter = array('create', 'jump', 'check', 'login', 'checkuser', 'shorter', 'database', 'bijective', 'ajax');
    if (in_array($customer, $filter)) {
        exit ( urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('自定义短链接不合法'), 'long_url'=>$url))) );
    } elseif ($short->checkEnable($customer) === FALSE) {
        exit ( urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('自定义短链接已被使用'), 'long_url'=>$url))) );
    }
}

$short_url = rtrim($base_url, '/'). '/'. $short->conventUrl($url, $_SESSION['user'], $customer);
exit ( urldecode(json_encode(array('success'=>TRUE, 'short_url'=>urlencode($short_url), 'long_url'=>$url))) );
