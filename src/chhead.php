<?php
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    die();
}
require_once $_SERVER['DOCUMENT_ROOT'] .'/libsql.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
session_start();

$user = get_user($_SESSION['uid']);
if (!$user) {
    header('HTTP/1.1 403 Login First');
    die();
}
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
