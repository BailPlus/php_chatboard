<?php
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) header("HTTP/1.1 403 Forbidden");

const DEBUG = false;
const DEFAULT_HEADPHOTO = "/img/headphoto/default.svg";

// 数据库配置信息
const SQL_SERVERNAME = "localhost";
const SQL_USERNAME = "php_chatboard";
const SQL_PASSWORD = "password";
const SQL_DBNAME = "php_chatboard";
// 数据库相关常量
const SQL_USERS_TABLE = "users";
const SQL_USERS_UID_COLUMN = "uid";
const SQL_USERS_OBJ_COLUMN = "obj";
const SQL_MSG_TABLE = "msg";
const SQL_MSG_MSGID_COLUMN = "msgid";
const SQL_MSG_OBJ_COLUMN = "obj";
// const SQL_GET_LAST_MSGID_QUERY = "SELECT msgid FROM last_msgid;";
// const SQL_UPDATE_LAST_MSGID_QUERY = "UPDATE last_msgid SET msgid = ?";
const SQL_CHATROOM_TABLE = "chatrooms";
const SQL_CHATROOM_ROOMID_COLUMN = "roomid";
const SQL_CHATROOM_OBJ_COLUMN = "obj";
