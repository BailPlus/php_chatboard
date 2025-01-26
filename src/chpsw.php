<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libmust_method.php';
must_method('POST');
require_once $_SERVER['DOCUMENT_ROOT'] .'/libcsrftoken.php';
verify_csrftoken();
require_once $_SERVER['DOCUMENT_ROOT'] .'/libneed_login.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libsql.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';

$oldpsw = require_args($_POST['oldpsw']);
$newpsw = require_args($_POST['newpsw']);

if (!hash_equals($user->psw,$oldpsw)) {
    echo '<script>alert("原密码错误");history.go(-1);</script>';
    die();
}
$user->psw = $newpsw;
$return_msg = $user->save();
if ($return_msg) die($return_msg);
echo '<script>alert("修改成功");location.href="/logout.php";</script>';
