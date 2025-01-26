<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libmust_method.php';
must_method('POST');
require_once $_SERVER['DOCUMENT_ROOT'] .'/libneed_login.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libclass.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libcsrftoken.php';
verify_csrftoken();

$friend = User::from_id(require_args($_GET['uid']));
if (!$friend) {
    header('HTTP/1.1 404 No Such User');
    die();
}
$user->add_friend($friend);
?>
<script>alert('添加好友成功');history.go(-1);</script>
