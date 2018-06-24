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
    'GROUP_MEMBER' => 3,
    'USER' => 4,
    'GROUP_ADMIN_OR_USER' => 5,
    'MASTER_OR_ADMIN' => 6,
    'MASTER_OR_SOMEADMIN' => 7
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

function authorize($userno_session, $role, array $args){
    $defaults = ['workid' => 0, 'groupno' => 0, 'userno' => 0, 'isapi' => true];
    $args = array_merge($defaults, $args);

    // APIの場合、X-Requested-Withヘッダがなければ拒否(CSRF対策)
    if($args['isapi'] && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        return false;
    }

    $db = DB::getInstance();
    switch($role){
        case ROLE['GLOBAL_MASTER']:
            return $db->isMaster($userno_session);
        case ROLE['GLOBAL_CLIENT']:
            return $db->isClientOfWork($userno_session, $args['workid']);
        case ROLE['GROUP_ADMIN']:
            return $db->isGroupAdmin($userno_session, $args['groupno']);
        case ROLE['GROUP_MEMBER']:
            return $db->isGroupMember($userno_session, $args['groupno']);
        case ROLE['USER']:
            return $userno_session == $args['userno'];
        case ROLE['GROUP_ADMIN_OR_USER']:
            return $db->isGroupAdmin($userno_session, $args['groupno']) || $userno_session == $args['userno'];
        case ROLE['MASTER_OR_ADMIN']:
            return $db->isMaster($userno_session) or $db->isGroupAdmin($userno_session, $args['groupno']);
        case ROLE['MASTER_OR_SOMEADMIN']:
            return $db->isMaster($userno_session) or $db->isSomeAdmin($userno_session);
    }
}

?>