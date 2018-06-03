<?php

if ($_ENV["IS_MAINTAINANCE"] === 'true') {
    header('Content-type: text/plain; charset=UTF-8');
    echo "メンテナンス中です。ご迷惑をおかけしまして申し訳ありません。しばらくしてから再度アクセスしてください。";
    exit;
}

function require_unlogined_session () {
    // セッション開始
    @session_start();
    // ログインしていれば
    if (isset($_SESSION["userno"])) {
        header('Location: ./index.php');
        exit;
    }
}

function require_logined_session() {
    // セッション開始
    @session_start();
    // ログインしていなければlogin.phpに遷移
    if (!isset($_SESSION["userno"])) {
        header('Location: ./login.php');
        exit;
    }
}

// CSRFトークンの生成
function generate_token() {
    // セッションIDからハッシュを生成
    return hash ( 'sha256', session_id() );
}

// CSRFトークン
function validate_token ($token) {
    return $token === generate_token();
}

// htmlspecialchars
function h ($var) {
    if (is_array($var)){
        return array_map(h, $var);
    } else {
        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
}

?>