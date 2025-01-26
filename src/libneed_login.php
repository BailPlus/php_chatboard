<?php
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) header("HTTP/1.1 403 Forbidden");
require_once $_SERVER['DOCUMENT_ROOT'].'/libclass.php';
session_start();
$user = User::from_id($_SESSION['uid']);
if (!$user) {
    header('HTTP/1.1 302 Login First');
    header('Location: /login.html');
    die();
}
