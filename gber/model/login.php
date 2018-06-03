<?php
session_start();

header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

//接続
include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/db.php';
include __DIR__ . '/../lib/sendEmail.php';
include __DIR__ . '/../lib/sessionUtil.php';

//ログイン認証
$mail = h($_POST["mail"]);
$pass = h($_POST["pass"]);
$token = h($_POST["token"]);

$db = DB::getInstance();
$row = $db->findUserByMail($mail);

if (validate_token($token) && password_verify($pass, $row['pass'])) {//有効ログイン
    session_regenerate_id(true);
    $_SESSION['time'] = time();
    $_SESSION['userno'] = $row['userno'];
    header("Location: $baseurl");
    exit;

} else {//無効ログイン
    header("Location: $baseurl/login.php");
    exit;
}
?>
