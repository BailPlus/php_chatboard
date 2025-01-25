<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/libsql.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libconst.php';
if (DEBUG) ini_set('display_errors', 1);
session_start();

$user = get_user($_SESSION['uid']);
if ($user) {
    $username = $user->uid;
    $headphoto_path = $user->headphoto;
} else {
    $username = '游客';
    $headphoto_path = DEFAULT_HEADPHOTO;
}

$roomid = (string)$_GET['roomid'];
$chatroom = get_chatroom($roomid);
if ($roomid !== '' && !in_array($roomid,$user->friends)) {
    header('HTTP/1.1 403 This Chatroom Isn\'t Yours');
    die('这不是你的聊天室');
};

$msg_head_ptr = $chatroom->msg_head_ptr;
$msgid = $msg_head_ptr;
$msgs = [];
while ($msgid) {
    $msg = getmsg($msgid);
    if (!$msg) die('读取消息时出错');
    array_push($msgs, $msg);
    $msgid = $msg->last_msgid;
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
                justify-content: space-between;
            }
        </style>
    </head>
    <body>
        <div class="top-bar">
            <h1>留言板</h1>
            <div class="user-info">
                <a href="/profile.php"><img src="<?php echo $headphoto_path; ?>" class="user-avatar"></a>
                <span><?php echo $username; ?></span>
            </div>
        </div>
        <div class="main-content">
            <?php if ($user): ?>
            <div class="input-container">
                <form action="/post_comment.php?roomid=<?php echo $roomid; ?>" method="post">
                    <textarea name='comment' rows="4" cols="50" placeholder="请输入您的留言"></textarea><br>
                    <input type="submit">
                </form>
            </div>
            <?php endif; ?>

            <ul class="comment-list">
                <?php foreach ($msgs as $msg): ?>
                    <?php $poster = get_user($msg->posterid) ?>
                    <li class="comment-item">
                        <div class="comment-header">
                            <div class="comment-avatar">
                                <a href="/profile.php?uid=<?php echo $poster->uid ?>">
                                    <img src="<?php echo $poster->headphoto ?>">
                                </a>
                            </div>
                            <span><?php echo $poster->uid ?></span>
                        </div>
                        <div class="comment-content">
                            <p class="comment-text"><?php echo $msg->content; ?></p>
                            <p class="comment-time"><?php echo date('Y-m-d H:i:s',$msg->posttime); ?></p>
                            <?php if($user): ?>
                                <div class="comment-buttons">
                                    <button>回复</button>
                                    <button>删除</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </body>
</html>