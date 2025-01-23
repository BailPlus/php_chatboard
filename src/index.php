<?php
// error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] != 'POST'):?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
        <title>留言板</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
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
            }

            /* 定义登录表单的样式 */
            .login-form {
                width: 360px;
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            }

            /* 标题样式 */
            .login-form h3{
                text-align: center;
                color: #333;
                margin-bottom: 20px;
            }

            /* 输入框通用样式 */
            input[type="text"],
            input[type="password"] {
                width: calc(100% - 22px);
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ccc;
                border-radius: 4px;
                outline: none;
            }

            /* 按钮样式 */
            input[type="submit"] {
                width: 100%;
                padding: 10px;
                background-color: #5cb85c;
                border: none;
                border-radius: 4px;
                color: white;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            input[type="submit"]:hover {
                background-color: #4cae4c;
            }

            /* 链接样式 */
            .login-form a {
                display: block;
                text-align: center;
                margin-top: 10px;
                color: #5cb85c;
                text-decoration: none;
            }

            .login-form a:hover {
                text-decoration: underline;
            }

            /* 错误信息样式 */
            .error {
                color: red;
                margin-bottom: 10px;
                text-align: center;
            }

            /* 成功信息样式 */
            .success {
                color: green;
                margin-bottom: 10px;
                text-align: center;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loginButton = document.getElementById('login');
                const passwordInput = document.getElementById('psw');
                const uidInput =document.getElementById('uid')
                

                loginButton.addEventListener('click', function(event) {
                    event.preventDefault(); // 阻止表单默认提交行为

                    if (uidInput.value.trim() === '') {
                        alert('用户名不能为空');
                        return;
                    }

                    const plainTextPassword = passwordInput.value;
                    if (plainTextPassword.trim() === '') {
                        alert('密码不能为空');
                        return;
                    }

                    const sha256Hash = CryptoJS.SHA256(plainTextPassword).toString(CryptoJS.enc.Hex);
                    passwordInput.value = sha256Hash;

                    // 提交表单
                    this.form.submit();
                });
            });
        </script>
    </head>
    <body>
        <form method="post" class="login-form">
            <h3>留言板</h3>
            <input type="text" name="uid" placeholder="用户名" id="uid" required>
            <input type="password" name="psw" placeholder="密码" id="psw" required>
            <input type="submit" value="登录" id="login">
            <a href="/register">注册</a>
            <a href="#">进入旁观模式 >>></a>
        </form>
    </body>
</html>
<?php die();endif;
session_start();
if (!isset($LIBCLASS)) require 'libclass.php';
if (!isset($LIBVERIFY_ARGS)) require 'libverify_args.php';
if (!isset($LIBSQL)) require 'libsql.php';

// 获取信息
$uid = require_args($_POST['uid']);
$psw = require_args($_POST['psw']);
$user = get_user($uid);
if (!$user) die('<script>alert("无此用户");location.href=".";</script>');
if ($psw !== $user->psw) die('<script>alert("密码错误");location.href=".";</script>');
else {
    header('HTTP/1.1 302 Found');
    $_SESSION['uid'] = $uid;
    header('Location: /chatboard.php');
    echo '欢迎你，'.$user->uid.'！';
}
?>
