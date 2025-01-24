<?php
session_start();
if (isset($_SESSION["uid"])) {
    echo $_SESSION['uid'];
    echo '<a href="/logout.php">退出登录</a>';
} else {
    echo '游客';
}