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
    if (!$result) return false;
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
    $sql = 'UPDATE '.SQL_USERS_TABLE.' SET '.SQL_USERS_OBJ_COLUMN.' = ? WHERE uid="'.$user->uid.'";';
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param('s', $serialized_user);
    $sql_exec_status = $stmt->execute();
    close($stmt);
    if (!$sql_exec_status) return '更新失败';
    else return '';
}

function getmsg(string $msgid){
    global $conn;
    $sql = 'SELECT '.SQL_MSG_OBJ_COLUMN.' FROM '.SQL_MSG_TABLE.' WHERE msgid = ?';
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param('s', $msgid);
    $stmt->execute();
    $serialized_msg = $stmt->get_result()->fetch_assoc()['obj'];
    if (!$serialized_msg) return false;
    $result = unserialize($serialized_msg);
    if (!$result) return false;
    close($stmt);
    return $result;
}

function sql_newmsg(Message $message):string {
    global $conn;
    $serialized_msg = serialize($message);
    $sql = 'INSERT INTO '.SQL_MSG_TABLE.' ('.SQL_MSG_MSGID_COLUMN.', '.SQL_MSG_OBJ_COLUMN.') VALUES (?, ?);';
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param('ss', $message->msgid,$serialized_msg);
    $sql_exec_status = $stmt->execute();
    close($stmt);
    if (!$sql_exec_status) return '注册失败';
    else return '';
}

function updatemsg(Message $message):string {
    if (!getmsg($message->msgid)) return '消息不存在';
    global $conn;
    $serialized_msg = serialize($message);
    $sql = 'UPDATE '.SQL_MSG_TABLE.' SET '.SQL_MSG_OBJ_COLUMN.' = ? WHERE msgid = "'.$message->msgid.'";';
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param('s',$serialized_msg);
    $sql_exec_status = $stmt->execute();
    close($stmt);
    if (!$sql_exec_status) return '更新失败';
    else return '';
}

function get_last_msgid():string {
    global $conn;
    return $conn->query(SQL_GET_LAST_MSGID_QUERY)->fetch_assoc()['msgid'];
}
function update_last_msgid(string $msgid):string {
    global $conn;
    $sql = SQL_UPDATE_LAST_MSGID_QUERY;
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param('s',$msgid);
    $sql_exec_status = $stmt->execute();
    close($stmt);
    if (!$sql_exec_status) return '更新最新msgid失败';
    else return '';
}