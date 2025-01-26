<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libmust_method.php';
must_method('POST');
require_once $_SERVER['DOCUMENT_ROOT'] .'/libneed_login.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libclass.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libconst.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libcsrftoken.php';
verify_csrftoken();

$comment = str_replace("\n",'<br>',htmlspecialchars(require_args($_POST['comment'])));
$roomid = require_args($_GET['roomid']);
$comment_father = Message::from_msgid($_GET['msgid']);

if ($roomid !== '' && !in_array($roomid,$user->friends)) {
    header('HTTP/1.1 403 This Chatroom Isn\'t Yours');
    die('这不是你的聊天室');
};

$chatroom = Chatroom::from_roomid($roomid);
$msg = new Message($roomid,$user->uid,$comment,$chatroom->msg_head_ptr) ;
$msg->save();

if ($comment_father) {  // 评论
    if ($comment_father->roomid === $roomid) $comment_father->comment($msg->msgid);
    else {
        header('HTTP/1.1 403 This Chatroom Isn\'t Yours');
        die('这不是你的聊天室');
    }
} else {    // 发表消息
    $chatroom->msg_head_ptr = $msg->msgid;
    $chatroom->save();
}
?>
<script>alert("发表成功");location.href = '<?= $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:'/chatboard.php' ?>';</script>
