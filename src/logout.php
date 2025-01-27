<?php
session_start();
session_destroy();
setcookie('refresh_token','',0,'/refresh.php');
header("HTTP/1.1 302 Found");
header("Location: /");