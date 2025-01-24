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
$msg = new Message($user->uid,$comment) ;
$msg->save();
update_last_msgid($msg->msgid);
echo '<script>alert("发表成功");history.go(-1);</script>';
