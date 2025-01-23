<?php
header('HTTP/1.1 403 Forbidden');
$LIBVERIFY_ARGS=true;

function require_args($arg) {
    if (!isset($arg)) {
        die("<script>alert('你在搞什么？');</script>");
    }
    return $arg;
}
