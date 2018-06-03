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
$groupno = mysql_real_escape_string($_POST["groupno"]);
$postcontent = mysql_real_escape_string($_POST["postcontent"]);
$postcontenthtml = nl2br($_POST["postcontent"]);

$sql = "INSERT INTO bbs_group (groupno, senderid, message, datetime) VALUES ('$groupno', '$userno', '$postcontent', '$datetime')";

if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

//グループメンバーにメール送信

$mailresults
    = mysql_query("SELECT mail, nickname FROM db_user WHERE userno IN (SELECT userno FROM grouplist WHERE groupno='"
    . $groupno . "')", $con) or die ('Error: ' . mysql_error());
$mailaddresses = array();
while ($mailrows = mysql_fetch_assoc($mailresults)) {
    $mailaddresses[] = $mailrows;
}
foreach ($mailaddresses as $eachaddress) {
    $mailto = $eachaddress['mail'];
    $nickname = $eachaddress['nickname'];
    $subject = $groupnamelist[$groupno] . "グループ掲示板新規投稿のお知らせ";
    $messageText = $nickname . "様\r\n" . $groupnamelist[$groupno]
        . "グループの掲示板に新規投稿通知がありました。\r\n\r\n" . $postcontent . "\r\n";
    $messageHtml = $nickname . "様<br />" . $groupnamelist[$groupno]
        . "グループの掲示板に新規投稿通知がありました。<br /><hr><br />" . $postcontenthtml
        . "<br /><br /><hr><br />";
    sendEmail($baseurl, $subject, $messageText, $messageHtml, $mailto);
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $userno . "', 'post2bbsAndEmail.php', '" . date('Y-m-d G:i:s') . "')", $con)
or die('Error: ' . mysql_error());

mysql_close($con);

?>