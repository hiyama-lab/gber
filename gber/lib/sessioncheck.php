<?php
session_start();
ini_set("display_errors", "On");

//緊急停止時は、このコメントアウトを外す
if ($_ENV["IS_MAINTAINANCE"] === 'true') {
    header('Content-type: text/plain; charset=UTF-8');
    echo "メンテナンス中です。ご迷惑をおかけしまして申し訳ありません。しばらくしてから再度アクセスしてください。";
    exit;
}

//-----------------------------
//ログイン確認
//-----------------------------
date_default_timezone_set('Asia/Tokyo');

//SESSIONに値が入っていればログイン中
if (isset($_SESSION['userno']) && $_SESSION['time'] + 604800 > time()) {
    $_SESSION['time'] = time();
    setcookie('userno', $_SESSION['userno'], time() + 604800, '/');
} else {
    if (isset($_COOKIE['userno'])) {
        $_SESSION['time'] = time();
        $_SESSION['userno'] = $_COOKIE['userno'];
        setcookie('userno', $_COOKIE['userno'], time() + 604800, '/');
    } else {
        header('Location: login.php');
        exit;
    }
}

?>
