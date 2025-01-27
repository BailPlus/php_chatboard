<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libmust_method.php';
must_method('POST');
require_once $_SERVER['DOCUMENT_ROOT'].'/libclass.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libverify_args.php';
session_start();

// 获取信息
$user = User::from_id(require_args($_POST['uid']));
$psw = require_args($_POST['psw']);
$remember = $_POST['remember'];
if (!$user) {
    header('HTTP/1.1 401 No Such User');
    die('<script>alert("无此用户");location.href="/login.html";</script>');
}
if (!hash_equals($user->psw,$psw)) {
    header('HTTP/1.1 401 Wrong Password');
    die('<script>alert("密码错误");location.href="/login.html";</script>');
}
else {
    $_SESSION['uid'] = $user->uid;
    if ($remember) {
        $refresh_token = new RefreshToken($user);
        setcookie('refresh_token',$refresh_token->tokenid,time()+86400*3,'/refresh.php','',false,true);
    }
    header('Location: /chatboard.php');
}
