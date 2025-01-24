<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libmust_method.php';
must_method('POST');
require_once $_SERVER['DOCUMENT_ROOT'].'/libclass.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libsql.php';
session_start();

// 获取信息
$uid = require_args($_POST['uid']);
$psw = require_args($_POST['psw']);
$user = get_user($uid);
if (!$user) {
    header('HTTP/1.1 401 No Such User');
    die('<script>alert("无此用户");location.href="/login.html";</script>');
}
if ($psw !== $user->psw) {
    header('HTTP/1.1 401 Wrong Password');
    die('<script>alert("密码错误");location.href="/login.html";</script>');
}
else {
    $_SESSION['uid'] = $uid;
    header('HTTP/1.1 302 Found');
    header('Location: /chatboard.php');
}
