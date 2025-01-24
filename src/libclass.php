<?php
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) header("HTTP/1.1 403 Forbidden");
require_once 'libsql.php';

class User {
    public string $uid;
    public string $psw;
    public function __construct($uid,$psw) {
        $this->uid = $uid;
        $this->psw = $psw;
    }
    public function save(): string {
        return sql_register($this);
    }
    static function from_serialized(string $string): User {
        $obj = unserialize($string);
        return $obj;
    }
}