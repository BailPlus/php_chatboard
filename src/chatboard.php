<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/libclass.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libconst.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libcsrftoken.php';
session_start();

$user = User::from_uid($_SESSION['uid']);
if ($user) {
    $username = $user->uid;
    $headphoto_path = $user->headphoto;
} else {
    $username = '游客';
    $headphoto_path = DEFAULT_HEADPHOTO;
}

$roomid = (string)$_GET['roomid'];
$chatroom = Chatroom::from_roomid($roomid);
if ($roomid !== '' && !in_array($roomid,$user->friends)) {
    header('HTTP/1.1 403 This Chatroom Isn\'t Yours');
    die('这不是你的聊天室');
};

$csrftoken = get_csrftoken();

function list_all_messages(string $msg_head_ptr):array {
    $msgid = $msg_head_ptr;
    $msgs = [];
    while ($msgid) {
        $msg = Message::from_msgid($msgid);
        if (!$msg) die('读取消息时出错');
        array_push($msgs, $msg);
        $msgid = $msg->last_msgid;
    }
    return $msgs;
}

function display_msg(Message $msg):void {
    global $user,$csrftoken;
    $poster = User::from_uid($msg->posterid); ?>
    <li class="comment-item" id="msg-<?= $msg->msgid ?>">
        <div class="comment-header">
            <div class="comment-avatar">
                <a href="/profile.php?uid=<?= urlencode($poster->uid) ?>">
                    <img src="<?= $poster->headphoto ?>">
                </a>
            </div>
            <span><?= htmlspecialchars($poster->uid, ENT_QUOTES) ?></span>
        </div>
        <div class="comment-content">
            <p class="comment-text"><?= str_replace("\n",'<br>',htmlspecialchars($msg->content, ENT_QUOTES)) ?></p>
            <p class="comment-time"><?= date('Y-m-d H:i:s',$msg->posttime); ?></p>
            <?php if($user): ?>
                <div class="comment-buttons">
                    <form action="/like.php?msgid=<?= $msg->msgid ?>" method="post">
                        <input type="hidden" name="csrftoken" value="<?= $csrftoken ?>">
                        <button>
                            <img src="<?= (in_array($user->uid,$msg->likes))?'/img/liked.png':'/img/like.png' ?>" width="25" height="25">
                            <?= count($msg->likes) ?>
                        </button>
                    </form>
                    <button onclick="comment('<?= $msg->msgid ?>');">
                        <img src="/img/comment.jpeg" width="25" height="25">
                    </button>
                    <?php if ($msg->posterid === $user->uid): ?>
                        <button>
                            <img src="/img/delete.png" width="25" height="25">
                        </button>
                    <?php  endif ?>
                </div>
            <?php endif; ?>
            <ul class="comment-list">
                <?php foreach (list_all_messages($msg->last_comment_msgid) as $submsg) display_msg(Message::from_msgid($submsg->msgid)); ?>
            </ul>
        </div>
    </li>
<?php
} 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
        <title>留言板</title>
        <style>
            /* 重置默认样式 */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body, html {
                height: 100%;
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0;
            }

            /* 定义顶部栏样式 */
            .top-bar {
                width: 100%;
                height: 60px;
                background-color: #030303;
                color: white;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0 20px;
                position: fixed;
                top: 0;
            }

            .top-bar h1 {
                margin: 0;
                font-size: 24px;
                text-align: middle;
            }

            .user-info {
                display: flex;
                align-items: center;
            }

            .user-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                overflow: hidden;
                margin-left: 10px;
            }

            .user-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* 定义主体部分样式 */
            .main-content {
                width: 80%;
                max-width: 150vh;
                margin-top: 70px;
                padding: 20px;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                max-height: calc(100vh - 70px); /* 减去顶部栏的高度 */
                overflow-y: auto;
            }
            @media (max-width: 600px) {
                .main-content {
                    max-width: 55vh;
                }
            }

            /* 输入框样式 */
            .input-container {
                width: 100%;
                margin-bottom: 20px;
            }
            .input-container input[type="text"],
            .input-container textarea {
                width: calc(100% - 22px);
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                outline: none;
            }

            /* 留言列表样式 */
            .comment-list {
                list-style-type: none;
                padding: 0;
            }
            .comment-item {
                width: 100%;
                margin-bottom: 20px;
                border-bottom: 1px solid #ccc;
                padding-bottom: 15px;
            }

            .comment-header {
                display: flex;
                /* justify-content: space-between; */
                align-items: center;
            }
            .comment-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                overflow: hidden;
            }
            .comment-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .comment-content {
                margin-left: 50px;
            }

            .comment-text {
                margin-bottom: 10px;
                word-wrap: break-word;
                white-space: normal;
            }

            .comment-time {
                font-size: 12px;
                color: #888;
                margin-top: 5px;
            }

            .comment-buttons {
                display: flex;
                /* justify-content: space-between; */
            }
        </style>
        <script>
            function comment(msgid) {
                document.getElementById('postCommentForm').action += '&msgid='+msgid;
                document.getElementById('commentTextarea').placeholder = '正在回复到'+msgid+'，刷新页面以取消';
                document.getElementById('commentTextarea').focus();
            }
        </script>
    </head>
    <body>
        <div class="top-bar">
            <h1>留言板</h1>
            <div class="user-info">
                <a href="/profile.php"><img src="<?= $headphoto_path; ?>" class="user-avatar"></a>
                <span><?= htmlspecialchars($username, ENT_QUOTES) ?></span>
            </div>
        </div>
        <div class="main-content" id="mainContentDiv">
            <?php if ($user): ?>
            <div class="input-container">
                <form action="/post_comment.php?roomid=<?= $roomid; ?>" method="post" id="postCommentForm">
                    <textarea name='comment' rows="4" cols="50" id="commentTextarea" placeholder="请输入您的留言"></textarea><br>
                    <input type="hidden" name="csrftoken" value="<?= $csrftoken ?>">
                    <input type="submit">
                </form>
            </div>
            <?php endif; ?>

            <ul class="comment-list">
                <?php foreach (list_all_messages($chatroom->msg_head_ptr) as $msg) display_msg($msg);?>
            </ul>
        </div>
    </body>
</html>
