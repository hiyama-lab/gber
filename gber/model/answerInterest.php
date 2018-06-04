<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
include __DIR__ . '/updateMatchingParam_human.php';
require_once __DIR__ . '/../lib/auth.php';

$workdata = json_decode(file_get_contents('php://input'), true);

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['USER'], ['userno' => $workdata['workerno']])){
    http_response_code(403);
    exit;
}

//興味の有無を登録
mysql_query("INSERT INTO helpmatching (workid, applyuserno, interest) VALUES ('"
    . $workdata['workid'] . "', '" . $workdata['workerno'] . "', '"
    . $workdata['interest'] . "')", $con) or die ('Error: ' . mysql_error());

//興味ありなら、メッセージメンバーに追加する
if ($workdata['interest'] == 1) {
    $result5 = mysql_query("SELECT messageid FROM message WHERE workid='"
        . $workdata['workid'] . "'", $con) or die ("Query error: " . mysql_error());
    $messageid = mysql_fetch_assoc($result5)['messageid'];
    $userno = $workdata['workerno'];
    $result6
        = mysql_query("INSERT INTO messagemember (messageid, memberid) VALUES ('$messageid','$userno')",
        $con) or die ("Query error: " . mysql_error());
}

//仕事がタグ付けされているか確認。されていたら個人パラメータを変更
$workparamresult
    = mysql_query("SELECT * FROM matchingparam_work WHERE groupno=0 and workid='"
    . $workdata['workid'] . "'", $con) or die ('Error: ' . mysql_error());
if (mysql_num_rows($workparamresult) > 0) {
    $workparam = mysql_fetch_assoc($workparamresult);
    if ($workdata['interest'] == 1) {
        updateMatchingParam_human($workparam, $workdata['workerno'], 0.1, true);
    } else {
        updateMatchingParam_human($workparam, $workdata['workerno'], 0.1,
            false);
    }
}


//募集者にメール送信
/*
$result = mysql_query("SELECT mail, nickname FROM db_user WHERE userno IN (SELECT userno FROM helplist WHERE id='".$workdata['workid']."')", $con) or die ('Error: ' . mysql_error());
$mailaddress = mysql_fetch_assoc($result);

$mailto = $mailaddress['mail'];
$nickname = $mailaddress['nickname'];
$subject = "募集に応募がありました";
$messageText = $nickname."様\r\nGBERの募集に応募がありました。\r\n詳細はログインしてご確認ください。\r\n";
$messageHtml = $nickname."様<br />GBERの募集に応募がありました。<br />詳細はログインしてご確認ください。<br />";

sendEmail($baseurl, $subject, $messageText, $messageHtml, $mailto);
*/

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $workdata['workerno'] . "', 'answerInterest.php', '" . date('Y-m-d G:i:s')
    . "')", $con) or die('Error: ' . mysql_error());

mysql_close($con);

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

?>