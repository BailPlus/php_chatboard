<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libsql.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libclass.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libconst.php';
if (DEBUG) ini_set('display_errors', 1);
session_start();

$token = require_args($_GET['token']);
$uid = file_get_contents('http://localhost:5000/php-chatboard-login?token='.$token);
echo $uid;
if (!$uid) {
    header('HTTP/1.1 401 Unauthorited');
    die();
}
$user = get_user($uid);
if (!$user) {
    $psw = uniqid();
    $user = new User($uid,hash('sha256',$psw));
    $user->save();
    $_SESSION['uid'] = $uid;
    echo '<script>alert("已自动为你注册账号。你的密码为：'.$psw.'，请尽快到个人中心修改。");location.href="/profile.php";</script>';
} else {
    $_SESSION['uid'] = $uid;
    header('Location: /chatboard.php');
}
