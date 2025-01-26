<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libmust_method.php';
must_method('POST');
require_once $_SERVER['DOCUMENT_ROOT'] .'/libsql.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libneed_login.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libcsrftoken.php';
verify_csrftoken();

$file = require_args($_FILES['file']);
if ($file['error'] > 0) {
    header('HTTP/1.1 500 Internal Error');
    die('文件上传错误：'.$_FILES['file']['error']);
}
$filename = '/img/headphoto/'.uniqid();
$filepath = $_SERVER['DOCUMENT_ROOT'].$filename;
move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
$user->headphoto = $filename;
$msg = $user->save();
if ($msg) die($msg);
echo '<script>alert("头像上传成功");history.go(-1);</script>';
