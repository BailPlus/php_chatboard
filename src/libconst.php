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

