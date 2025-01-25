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
    // if (!get_user($user->uid)) return '用户不存在';
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
    $result = Message::from_serialized($serialized_msg);
    if (!$result) return false;
    close($stmt);
    return $result;
}

function sql_newmsg(Message $message):void {
    global $conn;
    $serialized_msg = serialize($message);
    $sql = 'INSERT INTO '.SQL_MSG_TABLE.' ('.SQL_MSG_MSGID_COLUMN.', '.SQL_MSG_OBJ_COLUMN.') VALUES (?, ?);';
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param('ss', $message->msgid,$serialized_msg);
    $sql_exec_status = $stmt->execute();
    close($stmt);
    if (!$sql_exec_status) die('注册消息失败');
}

function updatemsg(Message $message):void {
    // if (!getmsg($message->msgid)) die('消息不存在');
    global $conn;
    $serialized_msg = serialize($message);
    $sql = 'UPDATE '.SQL_MSG_TABLE.' SET '.SQL_MSG_OBJ_COLUMN.' = ? WHERE msgid = "'.$message->msgid.'";';
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param('s',$serialized_msg);
    $sql_exec_status = $stmt->execute();
    close($stmt);
    if (!$sql_exec_status) die('更新失败');
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

function get_chatroom(string $roomid){
    global $conn;
    $sql = 'SELECT '.SQL_CHATROOM_OBJ_COLUMN.' FROM '.SQL_CHATROOM_TABLE.' WHERE roomid = ?';
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param('s', $roomid);
    $stmt->execute();
    $serialized_room = $stmt->get_result()->fetch_assoc()[SQL_CHATROOM_OBJ_COLUMN];
    if (!$serialized_room) return false;
    $result = Chatroom::from_serialized($serialized_room);
    if (!$result) return false;
    close($stmt);
    return $result;
}

function sql_new_chatroom(Chatroom $chatroom):void {
    global $conn;
    $serialized_chatroom = serialize($chatroom);
    $sql = 'INSERT INTO '.SQL_CHATROOM_TABLE.' ('.SQL_CHATROOM_ROOMID_COLUMN.', '.SQL_CHATROOM_OBJ_COLUMN.') VALUES (?, ?);';
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param('ss', $chatroom->roomid,$serialized_chatroom);
    $sql_exec_status = $stmt->execute();
    close($stmt);
    if (!$sql_exec_status) die('聊天室注册失败');
}

function update_chatroom(Chatroom $chatroom):void {
    // if (!get_chatroom($chatroom->roomid)) die('聊天室不存在');
    global $conn;
    $serialized_chatroom = serialize($chatroom);
    $sql = 'UPDATE '.SQL_CHATROOM_TABLE.' SET '.SQL_CHATROOM_OBJ_COLUMN.' = ? WHERE roomid = "'.$chatroom->roomid.'";';
    $stmt = $conn->prepare($sql);
    if (!$stmt) die('连接失败');
    $stmt->bind_param('s',$serialized_chatroom);
    $sql_exec_status = $stmt->execute();
    close($stmt);
    if (!$sql_exec_status) die('更新失败');
}
