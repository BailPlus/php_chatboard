<?php
ini_set('display_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT'].'/libsql.php';
session_start();

$user = get_user($_SESSION['uid']);

if (!$user) {
    header('HTTP/1.1 302 Login First');
    header('Location: /');
    die();
}

?>
<img src="<?php echo $user->headphoto; ?>" width="20" height="20">
<?php echo $user->uid; ?>
<a href="/logout.php">退出登录</a>