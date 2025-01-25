<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libmust_method.php';
must_method('POST');
require_once $_SERVER['DOCUMENT_ROOT'] .'/libneed_login.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libclass.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libsql.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libconst.php';
if (DEBUG) ini_set('display_errors', 1);

$comment = str_replace("\n",'<br>',htmlspecialchars(require_args($_POST['comment'])));
$roomid = require_args($_GET['roomid']);
if ($roomid !== '' && !in_array($roomid,$user->friends)) {
    header('HTTP/1.1 403 This Chatroom Isn\'t Yours');
    die('这不是你的聊天室');
};
$chatroom = get_chatroom($roomid);
$msg = new Message($user->uid,$comment,$chatroom->msg_head_ptr) ;
$msg->save();
$chatroom->msg_head_ptr = $msg->msgid;
$chatroom->save();
?>
<script>alert("发表成功");location.href = '<?= $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:'/chatboard.php' ?>';</script>
