<?php
include './ajax/ShortUrl.php';
$short_url = $_SERVER['REQUEST_URI'];
$base_url  = 'http://oo.cc/';
$path = parse_url($short_url);
$short_code = trim($path['path'], '/');

if (stripos($short_code, 'jump') !== FALSE) {
    header('Location: http://oo.cc');
} elseif (stripos($short_code, 'create') !== FALSE) {
    header('Location: http://oo.cc/create.php');
} else {
    $short = new ShortUrl();
//    echo $short_code;exit;
    $long_url = $short->parseShortCode($short_code);

    if ( FALSE === $long_url ) {
        header('Location: http://oo.cc');
    } else {
        header('Location: '. $long_url);
    }
}

