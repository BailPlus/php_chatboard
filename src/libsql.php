<?php
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) header("HTTP/1.1 403 Forbidden");
require_once $_SERVER['DOCUMENT_ROOT'].'/libclass.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libconst.php';

// if (extension_loaded('mysqli')) {
//     echo 'mysqli extension is loaded.';
// } else {
//     die('mysqli extension is not loaded.');
// }

// 创建连接
$conn = new mysqli(SQL_SERVERNAME, SQL_USERNAME, SQL_PASSWORD, SQL_DBNAME);
// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

function close($stmt): void {
    global $conn;
    $stmt->close();
    // $conn->close();
}

function get_user($uid) {
    if (!$uid) return false;
    global $conn;
    // 查询数据库
    $sql = "SELECT * FROM ".SQL_USERS_TABLE." WHERE uid = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $serialized_user = $stmt->get_result()->fetch_assoc()[SQL_USERS_OBJ_COLUMN];
    if (!$serialized_user) return false;
    $result = User::from_serialized($serialized_user);
    close($stmt);
    return $result;
}

function sql_register(User $user):string {
    global $conn;
    $serialized_user = serialize($user);
    if (get_user($user->uid)) return '该用户已存在';
    $sql = "INSERT INTO ".SQL_USERS_TABLE." (".SQL_USERS_UID_COLUMN.", ".SQL_USERS_OBJ_COLUMN.") VALUES (?, ?);";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("连接失败");
    $stmt->bind_param("ss", $user->uid, $serialized_user);
    $sql_exec_status = $stmt->execute();
    close($stmt);
    if (!$sql_exec_status) return '注册失败';
    else return '';
}

function update_user(User $user):string {
    global $conn;
    $serialized_user = serialize($user);
    if (!get_user($user->uid)) return '用户不存在';
    $sql = 'UPDATE '.SQL_USERS_TABLE.' SET obj=? WHERE uid="'.$user->uid.'";';
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param('s', $serialized_user);
    $sql_exec_status = $stmt->execute();
    close($stmt);
    if (!$sql_exec_status) return '更新失败';
    else return '';
}