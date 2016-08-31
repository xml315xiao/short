<?php
session_start();

$url = trim($_GET['short']);

if (strlen($url) < 1) {
    exit ( urldecode(json_encode(array('success'=>TRUE, 'short_url'=>$url, 'enable'=>FALSE, 'error'=>'输入的短连接为空'))) );
} elseif ($_SESSION['role'] < 2 && strlen($url) < 5) {
    exit ( urldecode(json_encode(array('success'=>TRUE, 'short_url'=>$url, 'enable'=>FALSE, 'error'=>'当前用户权限受限'))) );
} else {
    include __DIR__. DIRECTORY_SEPARATOR. 'ShortUrl.php';
    $short = new ShortUrl();
    $result = $short->checkEnable($url);
    if ($result === FALSE) {
        exit ( urldecode(json_encode(array('success'=>TRUE, 'short_url'=>$url, 'enable'=>FALSE, 'error'=>'当前链接地址已被使用' ))) );
    } else {
        exit ( urldecode(json_encode(array('success'=>TRUE, 'short_url'=>$url, 'enable'=>TRUE, 'error'=>'' ))) );
    }
}

