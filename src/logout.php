<?php
session_start();
session_destroy();
header("HTTP/1.1 302 Found");
header("Location: /");