<?php
header("HTTP/1.1 403 Forbidden");
$LIBCLASS=true;

class User {
    public string $uid;
    public string $psw;
    public function __construct($uid,$psw) {
        $this->uid = $uid;
        $this->psw = $psw;
    }
    public function save() {
        $string = serialize($this);
        // 保存到数据库
    }
    static function from_serialized(string $string): User {
        $obj = unserialize($string);
        return $obj;
    }
}