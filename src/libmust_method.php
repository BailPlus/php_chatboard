<?php
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) { header("HTTP/1.1 403 Library Can't Execute Directly"); die(); }

function must_method(string $method):void {
    if ($_SERVER['REQUEST_METHOD'] !== $method) {
        header('HTTP/1.1 405 Method Not Allowed');
        die();
    }    
}