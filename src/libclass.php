<?php
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) header("HTTP/1.1 403 Forbidden");
require_once $_SERVER['DOCUMENT_ROOT'].'/libsql.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libconst.php';

class User {
    public string $uid;
    public string $psw;
    public string $headphoto = DEFAULT_HEADPHOTO;
    public function __construct($uid,$psw) {
        $this->uid = $uid;
        $this->psw = $psw;
    }
    public function save(): string {
        if (get_user($this->uid)) { // 如果用户存在
            return update_user($this);
        } else {
            return sql_register($this);
        }
    }
    static function from_serialized(string $string): User {
        $obj = unserialize($string);
        return $obj;
    }
}