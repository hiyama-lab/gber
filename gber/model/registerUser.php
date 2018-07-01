<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');
ini_set('display_errors', 0);

//接続
//include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/db.php';
include __DIR__.'/../lib/sendEmail.php';
include __DIR__ . '/../lib/auth.php';

//登録
$mail = h($_POST["mail"]);
$pass = h($_POST["pass"]);
$nickname = h($_POST["nickname"]);

$token = h($_POST["token"]);
if (validate_token($token)) exit;

$options = array('cost' => 10);
$hash = password_hash($pass, PASSWORD_DEFAULT, $options);

$db = DB::getInstance();
//メールアドレスとパスワードの重複確認をする
if ($db->isRegisteredUser($mail) > 0) {
    die ("This user is already registered.");
} else {
    //DBに登録
    $result = $db->registerUser($mail, $nickname, $hash, date('Y-m-d')) or die ("Query error");

    //登録データのメールアドレスとパスワードからユーザIDを取得する
    $row = $db->findUserByMail($mail);
    $userno = $row['userno'];

    //写真DBに追加
    $db->addToPhotodata($userno) or die ("Query error");
    //スキルマッチングに登録
    $db->addToMatchingParam($userno) or die ("Query error");
    $db->addToQuestionnaire($userno) or die ("Query error");
    $db->addToGrouplist(0, $userno) or die ("Query error");

    echo $_GET['jsoncallback'] . '({"status":"succeed"});';

    //登録完了メールを送信
    $subject = "GBER登録完了";
    $messageText = $nickname."様\r\nGBERに登録完了ました。\r\nログインしてご利用ください。\r\n使い方の説明はホームページにありますのでお読みください。\r\n";
    $messageHtml = $nickname."様<br />GBERの募集に応募がありました。<br />ログインしてご利用ください。<br />使い方の説明はホームページにありますのでお読みください。<br />";
    if($_ENV["IS_DEMO"] === 'false'){
        sendEmail($baseurl, $subject, $messageText, $messageHtml, $mail);
    }
}
?>