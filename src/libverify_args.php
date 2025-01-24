<?php
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) header("HTTP/1.1 403 Forbidden");

function require_args($arg) {
    if (!isset($arg)) {
        die("<script>alert('你在搞什么？');</script>");
    }
    return $arg;
}
