<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libmust_method.php';
must_method('POST');
require_once $_SERVER['DOCUMENT_ROOT'] .'/libcsrftoken.php';
verify_csrftoken();
require_once $_SERVER['DOCUMENT_ROOT'] .'/libneed_login.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libverify_args.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/libclass.php';

$msg = Message::from_id(require_args($_GET['msgid']));
if (!$msg) { header('HTTP/1.1 404 No Such Message'); die(); }

if (in_array($user->uid, $msg->likes)) {
    $msg->likes = array_values(array_diff($msg->likes, [$user->uid]));
} else {
    array_push($msg->likes,$user->uid);
}
$msg->save();
header('Location: /chatboard.php?roomid='.$msg->roomid.'#msg-'. $msg->msgid);
