<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libneed_login.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libcsrftoken.php';
verify_csrftoken();
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libclass.php';

$msg = Message::from_id(require_args($_GET['msgid']));
$hangable_id = require_args($_GET['hangable_id']);
$hangable = Chatroom::from_id($hangable_id);
if (!$hangable) $hangable = Message::from_id($hangable_id);
if (!$hangable) { header('HTTP/1.1 404 No Such Hangable'); die(); }
if (!$msg) { header('HTTP/1.1 404 No Such Message'); die(); }
if ($msg->posterid !== $user->uid && !$user->isadmin) { header('HTTP/1.1 403 This Message Isn\'t Yours'); die(); }

$hangable->delete_msg($msg);
?>
<script>
    alert('删除成功');
    history.go(-1);
</script>
