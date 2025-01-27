<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/libclass.php';
session_start();

$refresh_token = RefreshToken::from_id($_COOKIE['refresh_token']);
if (!$refresh_token) { header('Location: /login.html'); die(); }
$user = User::from_id($refresh_token->owner);
if (!$user) { header('HTTP/1.1 500 User Who Belongs To Given Token Not Found'); die(); }
$_SESSION['uid'] = $user->uid;
$new_refresh_token = new RefreshToken($user);
setcookie('refresh_token',$new_refresh_token->tokenid,time()+86400*3,'/refresh.php','',true);
header('Location: /chatboard.php');
