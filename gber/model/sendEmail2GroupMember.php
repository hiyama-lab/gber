<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';

$post = json_decode(file_get_contents('php://input'), true);

//グループメンバーのメアドを一覧で取得
$result
    = mysql_query("SELECT nickname, mail FROM db_user WHERE userno IN (SELECT DISTINCT userno FROM grouplist WHERE groupno='"
    . $post['groupno'] . "')", $con) or die ("Query error: " . mysql_error());
$records = array();
while ($row = mysql_fetch_assoc($result)) {
    $records[] = $row;
}

foreach ($records as $eachrecord) {
    $subject = $post['mailsubject'];
    $messageText = $eachrecord['nickname']
        . "様\r\nGBERのグループ管理者より連絡がありました。\r\n−−−−−−−−−−−−\r\n" . $post['mailcontent']
        . "\r\n";
    $messageHtml = $eachrecord['nickname']
        . "様<br />GBERのグループ管理者より連絡がありました。<br />−−−−−−−−−−−−<br />"
        . $post['mailcontent'] . "<br />";
    sendEmail($baseurl, $subject, $messageText, $messageHtml,
        $eachrecord['mail']);
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('0','sendEmail2GroupMember.php','"
    . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());


echo $_GET['jsoncallback'] . '({"status":"succeed"});';

?>