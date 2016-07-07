<?php
include './ShortUrl.php';
$short_url = $_SERVER['REQUEST_URI'];
$base_url  = 'http://mc.cc/';
$path = parse_url($short_url);
$short_code = trim($path['path'], '/');

if (stripos($short_code, 'jump') !== FALSE) {
    header('Location: http://www.mc.cc');
} elseif (stripos($short_code, 'create') !== FALSE) {
    header('Location: http://mc.cc/create.php');
} else {
    $short = new ShortUrl();
//    echo $short_code;exit;
    $long_url = $short->parseShortCode($short_code);

    if ( FALSE === $long_url ) {
        header('Location: http://www.mc.cc');
    } else {
        header('Location: '. $long_url);
    }
}

