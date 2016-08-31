<?php
session_start();
// role 1.普通用户 可以自定义长度为1-4的短连接 2. 只允许自定义长度至少为5的短连接地址
include __DIR__. DIRECTORY_SEPARATOR. 'database.php';
$pdo = new PDO('mysql:host='. HOSTNAME. ';dbname='. DATABASE, USERNAME, PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('SET NAMES UTF8');
$query = 'SELECT * FROM users ';
$statement = $pdo->prepare($query);
$statement->execute();
$result = $statement->fetchALL(2);
$users = array();
foreach($result as $user) {
    $username = $user["username"];
    $users[$username] = $user;
}

// check user
$username = trim($_REQUEST["username"]);
if(strlen($username) === 0) {
    exit(urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('请输入用户名')))));
} elseif ( !in_array($username, array_keys($users)) ) {
    exit(urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('当前用户不存在')))));
} elseif ( ! isset($_POST['password'])) {
    exit(urldecode(json_encode(array('success'=>FALSE, 'error'=>'password'))));
} elseif ( md5(trim($_POST['password'])) != $users[$username]["password"]) {
    exit(urldecode(json_encode(array('success'=>FALSE, 'error'=>urlencode('用户名与密码异常')))));
} else {
    $_SESSION["user"] = $username;
    $_SESSION["role"] = $users[$username]["role"];
    exit(urldecode(json_encode(array('success'=>TRUE, 'error'=>''))));
}