<?php
header("HTTP/1.1 403 Forbidden");
$LIBSQL=true;
if (!isset($LIBCLASS)) require 'libclass.php';

// if (extension_loaded('mysqli')) {
//     echo 'mysqli extension is loaded.';
// } else {
//     die('mysqli extension is not loaded.');
// }

// 数据库配置信息
const servername = "localhost";
const username = "php_chatboard";
const password = "password";
const dbname = "php_chatboard";
// 创建连接
$conn = new mysqli(servername, username, password, dbname);
// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}


function get_user(string $uid) {
    global $conn;
    // 查询数据库
    $sql = "SELECT * FROM users WHERE uid = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $serialized_user = $stmt->get_result()->fetch_assoc()['obj'];
    if (!$serialized_user) return false;
    $result = User::from_serialized($serialized_user);
    return $result;
}
// 关闭连接
// $stmt->close();
// $conn->close();
