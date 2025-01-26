<?php
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) header("HTTP/1.1 403 Forbidden");
require_once $_SERVER['DOCUMENT_ROOT'] .'/libconst.php';
session_start();

function get_csrftoken():string {
    $csrftoken = bin2hex(random_bytes(32));
    $_SESSION['csrftoken'] = [
        'value' => $csrftoken,
        'time'=> time()
    ];
    return $csrftoken;
}

function verify_csrftoken():void {
    $csrftoken = $_POST['csrftoken'];
    if (time() - $_SESSION['csrftoken']['time'] > CSRFTOKEN_EXPIRE || !hash_equals($csrftoken,$_SESSION['csrftoken']['value'])) {
        header('HTTP/1.1 401 CSRF Verification Failed');
        session_destroy();
        echo "很抱歉，你的操作超时或你正在遭受CSRF攻击，所以系统阻断了此次操作。请重试。";  
        die();  
    }
    return ;
}
