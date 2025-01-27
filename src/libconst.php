<?php
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) header("HTTP/1.1 403 Forbidden");

const DEBUG = false;
const DEBUG_PSW = '70f47a4c1f5e9424fbd08c126b719471';
if (DEBUG) ini_set('display_errors', 1);

const WWWCQUPT_SALT = '0523b3c3c2c54271e1612ec0e590d122';
const WWWCQUPT_NONCE_EXPIRE = 3;
const CSRFTOKEN_EXPIRE = 300;

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
const SQL_REFRESH_TOKEN_TABLE = "refresh_tokens";
const SQL_REFRESH_TOKENID_COLUMN = "tokenid";
const SQL_REFRESH_TOKEN_OBJ_COLUMN = "obj";
