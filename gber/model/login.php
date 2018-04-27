<?php
session_start();

header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

//接続
include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/db.php';
include __DIR__ . '/../lib/sendEmail.php';

//ログイン認証
$mail = $_POST["mail"];
$pass = $_POST["pass"];

$db = DB::getInstance();
$row = $db->findUserByMail($mail);

if (password_verify($pass, $row['pass'])) {//有効ログイン
    $_SESSION['time'] = time();
    $_SESSION['userno'] = $row['userno'];
    if (ini_get("session.use_cookies")) {
        setcookie(session_id(), '', time() + 604800, '/');
    }
    setcookie('userno', $_SESSION['userno'], time() + 604800, '/');
    header("Location: " . $baseurl);

} else {//無効ログイン(ログアウト処理と同じ)
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        setcookie(session_id(), '', time() - (7 * 24 * 60 * 60), '/');
    }
    session_destroy();
    setcookie('userno', '', time() - (7 * 24 * 60 * 60), '/');
    header("Location: " . $baseurl);
    exit;
}
?>
