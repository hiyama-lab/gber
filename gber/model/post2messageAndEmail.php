<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

date_default_timezone_set('Asia/Tokyo');
$datetime = date('Y-m-d G:i:s');

$userno = mysql_real_escape_string($_POST["userno"]);
$messageid = mysql_real_escape_string($_POST["messageid"]);
$messagename = mysql_real_escape_string($_POST["messagename"]);
$postcontent = mysql_real_escape_string($_POST["postcontent"]);
$postcontenthtml = nl2br($_POST["postcontent"]);


$result = mysql_query("UPDATE message SET lastupdate = '" . $datetime
    . "' WHERE messageid='" . $messageid . "'") or die ("Query error: "
    . mysql_error());

//メッセージメンバーにメール送信

$mailresults
    = mysql_query("SELECT mail, nickname FROM db_user WHERE userno IN (SELECT memberid FROM messagemember WHERE messageid='"
    . $messageid . "')", $con) or die ('Error: ' . mysql_error());
$mailaddresses = array();
while ($mailrows = mysql_fetch_assoc($mailresults)) {
    $mailaddresses[] = $mailrows;
}
foreach ($mailaddresses as $eachaddress) {
    $mailto = $eachaddress['mail'];
    $nickname = $eachaddress['nickname'];
    $subject = "GBER新着メッセージのお知らせ";
    $messageText = $nickname . "様\r\nメッセージ「" . $messagename . "」の新着通知が届きました。\r\n\r\n"
        . $postcontent . "\r\nこのメールには返信できません。ログインして行ってください。\r\n";
    $messageHtml = $nickname . "様<br />メッセージ「" . $messagename
        . "」の新着通知が届きました。<br /><hr>" . $postcontenthtml
        . "<hr><br />このメールには返信できません。ログインして行ってください。<br />";
    sendEmail($baseurl, $subject, $messageText, $messageHtml, $mailto);
}

$sql
    = "INSERT INTO messageeach (messageid, senderid, message, messagedate) VALUES ('$messageid', '$userno', '$postcontent', '$datetime')";
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $userno . "', 'post2messageAndEmail.php', '" . date('Y-m-d G:i:s') . "')", $con)
or die('Error: ' . mysql_error());

mysql_close($con);
?>