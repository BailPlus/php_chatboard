<?php
ini_set('display_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT'].'/libsql.php';
session_start();

$current_user = get_user($_SESSION['uid']);
$target_user = isset($_GET['uid'])?get_user($_GET['uid']):$current_user; // 要查看的用户id
if (!$target_user) {
    if (isset($_GET['uid'])) {
        header('HTTP/1.1 404 No Such User');
        die('uid不存在');
    } else {
        header('HTTP/1.1 302 Login First');
        header('Location: /');
        die();
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
        <title>个人信息</title>
        <style>
            /* 重置默认样式 */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body, html {
                height: 100%;
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0;
            }

            /* 定义顶部栏样式 */
            .top-bar {
                width: 100%;
                height: 60px;
                background-color: #030303;
                color: white;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0 20px;
                position: fixed;
                top: 0;
            }

            .top-bar h1 {
                margin: 0;
                font-size: 24px;
                text-align: middle;
            }
        </style>
    </head>
    <body>
        <div class="top-bar">
            <h1>个人信息</h1>
        </div>
        <center>
            <p>用户名：<?php echo $target_user->uid; ?></p>
            <hr>
            <p>头像：</p>
            <a href="<?php echo $target_user->headphoto; ?>"><img src="<?php echo $target_user->headphoto; ?>"></a>
            <?php if ($target_user === $current_user): ?>
                <form action="/chhead.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="file" required>
                    <input type="submit" value="上传">
                </form>
                <hr>
                <p>修改密码：</p>
                <form action="/chpsw.php" method="post">
                    <input type="password" name="oldpsw" id="oldpsw" placeholder="原密码" required><br>
                    <input type="password" name="psw1" id="psw1" placeholder="新密码" required><br>
                    <input type="password" name="psw2" id="psw2" placeholder="重复密码" required><br>
                    <input type="submit" value="修改">
                </form>
                <hr>
                <a href="/logout.php">退出登录</a>
            <?php endif; ?>
        </center>
    </body>
</html>
