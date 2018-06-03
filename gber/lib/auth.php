<?php
require_once __DIR__  . "/db.php";

if ($_ENV["IS_MAINTAINANCE"] === 'true') {
    header('Content-type: text/plain; charset=UTF-8');
    echo "メンテナンス中です。ご迷惑をおかけしまして申し訳ありません。しばらくしてから再度アクセスしてください。";
    exit;
}

const ROLE = array(
    'GLOBAL_MASTER' => 0,
    'GLOBAL_CLIENT' => 1,
    'GROUP_ADMIN' => 2,
    'GROUP_MEMBER' => 3
);

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

function authorize($userno, $role, array $args){
    $defaults = ['workid' => 0, 'groupno' => 0];
    $args = array_merge($defaults, $args);

    $db = DB::getInstance();
    switch($role){
        case ROLE['GLOBAL_MASTER']:
            return $db->isMaster($userno);
        case ROLE['GLOBAL_CLIENT']:
            return $db->isClientOfWork($userno, $args['workid']);
        case ROLE['GROUP_ADMIN']:
            return $db->isGroupAdmin($userno, $args['groupno']);
        case ROLE['GROUP_MEMBER']:
            return $db->isGroupMember($userno, $args['groupno']);
    }
}

?>