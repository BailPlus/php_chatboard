<?php
session_start();
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) header("HTTP/1.1 403 Forbidden");
require_once $_SERVER['DOCUMENT_ROOT'].'/libsql.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libconst.php';

class User {
    public string $uid;
    public string $psw;
    public string $headphoto = DEFAULT_HEADPHOTO;
    public array $friends = [];
    public function __construct($uid,$psw) {
        $this->uid = $uid;
        $this->psw = $psw;
    }
    public function save(): string {
        if (get_user($this->uid)) return update_user($this);  // 如果用户存在
        else return sql_register($this);
    }
    public function add_friend(User $friend): void {
        $chatroom = new Chatroom() ;
        $chatroom->save();
        $this->friends[$friend->uid] = $chatroom->roomid;
        $return_msg = $this->save();
        if ($return_msg) die($return_msg);
        $friend->friends[$this->uid] = $chatroom->roomid;
        $return_msg = $friend->save();
        if ($return_msg) die($return_msg);  // 不够原子性啊
    }
    static function from_serialized(string $string): User {
        $obj = unserialize($string);
        return $obj;
    }
    public static function from_uid(string $uid) {
        return get_user($uid);
    }
}

class Message {
    public string $msgid;
    public string $last_msgid;
    public string $roomid = '';  // 防止跨聊天室回复
    public string $content;
    public string $posterid;
    public int $posttime;
    public array $likes = [];
    public array $comments = [];
    public function __construct(string $roomid, string $posterid, string $content, string $last_msgid) {
        $this->msgid = uniqid();
        $this->last_msgid = $last_msgid;
        $this->roomid = $roomid;
        $this->content = $content;
        $this->posterid = $posterid;
        $this->posttime = time();
    }
    public function save():void {
        if (getmsg($this->msgid)) updatemsg($this);
        else sql_newmsg($this);
    }
    public function comment(string $msgid):void {
        array_push($this->comments, $msgid);
        $this->save();
    }
    static function from_serialized(string $string): Message {
        $obj = unserialize($string);
        return $obj;
    }
    static function from_msgid(string $msgid) {
        return getmsg($msgid);
    }
}

class Chatroom {
    public string $roomid;
    public string $msg_head_ptr;
    public function __construct() {
        $this->roomid = uniqid();
        $this->msg_head_ptr = '';
    }
    public function save():void {
        if (get_chatroom($this->roomid)) update_chatroom($this);
        else sql_new_chatroom($this);
    }
    public static function from_serialized(string $string): Chatroom {
        $obj = unserialize($string);
        return $obj;
    }
    public static function from_roomid(string $roomid) {
        return get_chatroom($roomid);
    }
}
