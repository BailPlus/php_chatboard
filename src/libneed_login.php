<?php
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) header("HTTP/1.1 403 Forbidden");
require_once $_SERVER['DOCUMENT_ROOT'].'/libsql.php';
session_start();
$user = get_user($_SESSION['uid']);
if (!$user) {
    header('HTTP/1.1 403 Login First');
    die();
}
