<?php
session_start();
if ($_SESSION['uid'] === '1') highlight_file(__FILE__);

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
        if (get_user($this->uid)) return update_user($this);  // 如果用户存在
        else return sql_register($this);
    }
    static function from_serialized(string $string): User {
        $obj = unserialize($string);
        return $obj;
    }
}

class Message {
    public string $msgid;
    public string $last_msgid;
    public string $content;
    public array $comments;
    public function __construct(string $content) {
        $this->msgid = uniqid();
        $this->last_msgid = get_last_msgid();
        $this->content = $content;
        $this->comments = [];
    }
    public function save(): string {
        if (getmsg($this->msgid)) return updatemsg($this);
        else return sql_newmsg($this);
    }
    public function comment(string $msgid):string {
        array_push($this->comments, $msgid);
        return $this->save();
    }
    static function from_serialized(string $string): Message {
        $obj = unserialize($string);
        return $obj;
    }
}