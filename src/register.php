<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT'] .'/libmust_method.php';
must_method('POST');
require_once $_SERVER['DOCUMENT_ROOT'] .'/libclass.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libsql.php';
session_start();

// 获取信息
$uid = require_args($_POST['uid']);
$psw = require_args($_POST['psw']);

$user = new User($uid, $psw);
$return_msg = $user->save();
if ($return_msg) echo '<script>alert("注册失败：'.$return_msg.'");location.href="/register.php";</script>';
else echo '<script>alert("注册成功");location.href="/";</script>';
