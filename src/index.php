<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
session_start();
if (isset($_SESSION['uid'])) {  // 已登录用户，直接跳转
    header('HTTP/1.1 302 Found');
    header('Location: /chatboard.php');
    die();
} else require_once $_SERVER['DOCUMENT_ROOT'] .'/libneed_login.php';
