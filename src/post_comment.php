<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libmust_method.php';
must_method('POST');
require_once $_SERVER['DOCUMENT_ROOT'] .'/libneed_login.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libclass.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libconst.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libcsrftoken.php';
verify_csrftoken();

$comment = require_args($_POST['comment']);
$chatroom = Chatroom::from_id(require_args($_GET['roomid']));
$comment_father = Message::from_id($_GET['msgid']);

if (!$chatroom) { header('HTTP/1.1 404 No Such Chatroom'); die(); }
if ($chatroom->roomid !== '' && !in_array($chatroom->roomid,$user->friends)) { header('HTTP/1.1 403 This Chatroom Isn\'t Yours'); die(); }


if ($comment_father) {  // 评论
    if ($comment_father->roomid === $chatroom->roomid) $comment_father->comment($user->uid,$comment);
    else { header('HTTP/1.1 400 Can\'t Comment Across Chatrooms'); die(); }
} else {    // 发表消息
    $msg = new Message($chatroom->roomid,$user->uid,$comment,$chatroom->hang_msg_ptr) ;
    $msg->save();    
    $chatroom->hang_msg_ptr = $msg->msgid;
    $chatroom->save();
}
?>
<script>alert("发表成功");location.href = '<?= $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:'/chatboard.php' ?>';</script>
