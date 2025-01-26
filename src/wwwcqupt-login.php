<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libsql.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libclass.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libconst.php';
session_start();

$server_nonce = require_args($_GET['nonce']);
$server_time = (int)substr($server_nonce,0,10);
$server_hash = substr($server_nonce,10);
if (time() - $server_time > WWWCQUPT_NONCE_EXPIRE || !hash_equals($server_hash, md5($server_time.WWWCQUPT_SALT))) {
    header('HTTP/1.1 403 Bad Nonce');
    die();
}
$token = require_args($_GET['token']);
$time = (string)time();
$nonce = $time.md5($time.WWWCQUPT_SALT);
$uid = file_get_contents('http://localhost:5000/php-chatboard-login?token='.$token.'&nonce='.$nonce);
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
