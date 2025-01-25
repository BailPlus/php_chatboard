<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libneed_login.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libsql.php';

$friend_uid = require_args($_GET['uid']);
$friend = get_user($friend_uid);
if (!$friend) {
    header('HTTP/1.1 404 No Such User');
    die();
}
$user->add_friend($friend);
?>
<script>alert('添加好友成功');history.go(-1);</script>
