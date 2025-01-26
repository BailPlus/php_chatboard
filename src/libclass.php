<?php
session_start();
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) header("HTTP/1.1 403 Forbidden");
require_once $_SERVER['DOCUMENT_ROOT'].'/libsql.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libconst.php';

interface From_id_able {
    public static function from_id($id);
}

class User implements From_id_able{
    public string $uid;
    public string $psw;
    public string $headphoto = DEFAULT_HEADPHOTO;
    public array $friends = [];
    public bool $isadmin = false;
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
    public static function from_id($id) {
        return get_user($id);
    }
}

abstract class Hangable implements From_id_able{
    public string $hang_msg_ptr;
    abstract public function hang(Message $message):void ;
    public function delete_msg(Message $message_want_to_delete): void {
        $now_msg = Message::from_id($this->hang_msg_ptr);
        if ($now_msg->msgid === $message_want_to_delete->msgid) {
            $this->hang_msg_ptr = $now_msg->last_msgid;
            $this->save();
            return;
        }
        while ($now_msg->last_msgid !== $message_want_to_delete->msgid) {
            $now_msg = Message::from_id($now_msg->last_msgid);
            if (!$now_msg->last_msgid) die('在该Hangable找不到要删除的Message');
        }
        $now_msg->last_msgid = $message_want_to_delete->last_msgid;
        $now_msg->save();
    }
    abstract public static function from_id($id);
}

class Message extends Hangable{
    public string $msgid;
    public string $last_msgid;
    public string $roomid = '';  // 防止跨聊天室回复
    public string $hangable_id;
    public string $content;
    public string $posterid;
    public int $posttime;
    public array $likes = [];
    public string $hang_msg_ptr = '';
    public function __construct(string $roomid, string $posterid, string $content, string $last_msgid) {
        $this->msgid = uniqid();
        $this->last_msgid = $last_msgid;
        $this->roomid = $roomid;
        $this->hangable_id = $roomid;
        $this->content = $content;
        $this->posterid = $posterid;
        $this->posttime = time();
    }
    public function save():void {
        if (getmsg($this->msgid)) updatemsg($this);
        else sql_newmsg($this);
    }
    public function hang(Message $message):void {
        $message->last_msgid = $this->hang_msg_ptr;
        $message->hangable_id = $this->msgid;
        $message->save();
        $this->hang_msg_ptr = $message->msgid;
        $this->save();
    }
    public function comment(string $uid,string $content):void {
        $comment_msg = new Message($this->roomid,$uid,$content,$this->hang_msg_ptr);
        $comment_msg->save();
        $this->hang($comment_msg);
    }
    public function delete():void {
        // 只是解链，而不从数据库删除消息对象
    }
    public static function from_serialized(string $string): Message {
        $obj = unserialize($string);
        return $obj;
    }
    public static function from_id($msgid) {
        return getmsg($msgid);
    }
}

class Chatroom extends Hangable{
    public string $roomid;
    public string $hang_msg_ptr;
    public function __construct() {
        $this->roomid = uniqid();
        $this->hang_msg_ptr = '';
    }
    public function save():void {
        if (get_chatroom($this->roomid)) update_chatroom($this);
        else sql_new_chatroom($this);
    }
    public function hang(Message $message):void {
        $message->last_msgid = $this->hang_msg_ptr;
        $message->hangable_id = $this->roomid;
        $message->save();
        $this->hang_msg_ptr = $message->msgid;
        $this->save();
    }
    public static function from_serialized(string $string): Chatroom {
        $obj = unserialize($string);
        return $obj;
    }
    public static function from_id($roomid) {
        return get_chatroom($roomid);
    }
}
