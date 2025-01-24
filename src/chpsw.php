<?php
// ini_set('display_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT'] .'/libsql.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libneed_login.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libmust_method.php';
must_method('POST');

$oldpsw = require_args($_POST['oldpsw']);
$newpsw = require_args($_POST['newpsw']);

if ($oldpsw != $user->psw) {
    echo '<script>alert("原密码错误");history.go(-1);</script>';
    die();
}
$user->psw = $newpsw;
$user->save();
echo '<script>alert("修改成功");location.href="/logout.php";</script>';
