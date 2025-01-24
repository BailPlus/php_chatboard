<?php
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) header("HTTP/1.1 403 Forbidden");

function require_args($arg) {
    if (!isset($arg)) {
        header("HTTP/1.1 400 Missing Parameters");
        die("你在搞什么？");
    }
    return $arg;
}
