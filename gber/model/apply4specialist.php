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

if ($workdata['status'] == 1) {//オファーを受けるとき
    mysql_query("UPDATE workdate SET status='1' WHERE workid='" . $workdata['workid'] . "' and workerno='"
        . $workdata['workerno'] . "' and workday='" . $workdata['workday']
        . "' and am='" . $workdata['am'] . "' and pm='" . $workdata['pm'] . "'", $con)
    or die('Error: ' . mysql_error());
} else {
    if ($workdata['status'] == 0) {//オファーを断るとき
        mysql_query("DELETE FROM workdate WHERE workid = '" . $workdata['workid'] . "' and workerno = '"
            . $workdata['workerno'] . "' and workday = '" . $workdata['workday']
            . "' and am='" . $workdata['am'] . "' and pm='" . $workdata['pm'] . "'",
            $con) or die('Error: ' . mysql_error());
        mysql_query("INSERT INTO workcancel (workerno,workid,workday,am,pm) VALUES ('" . $workdata['workerno']
            . "','" . $workdata['workid'] . "','" . $workdata['workday'] . "','"
            . $workdata['am'] . "','" . $workdata['pm'] . "')", $con) or die('Error: '
            . mysql_error());
    }
}

if ($workdata['status'] == 0) {
    $result = mysql_query("SELECT dateid FROM workdate WHERE workid = '" . $workdata['workid'] . "' and workerno = '"
        . $workdata['workerno'] . "'", $con) or die ('Error: ' . mysql_error());
    if (mysql_num_rows($result) == 0) { //もしオファーを断ってもう残っていないとき，workevalからも削除する
        mysql_query("DELETE FROM workeval WHERE workid = '" . $workdata['workid'] . "' and workerno = '"
            . $workdata['workerno'] . "'", $con) or die ('Error: ' . mysql_error());
    } else { //もしオファーを断って、残りの日報が全て記入済みの時、selfevalを1にする
        $result2 = mysql_query("SELECT dateid FROM workdate WHERE workid = '" . $workdata['workid']
            . "' and workerno = '" . $workdata['workerno'] . "' and reportflag='0'",
            $con) or die ('Error: ' . mysql_error());
        if (mysql_num_rows($result2) == 0) {
            mysql_query("UPDATE workeval SET selfeval='1' WHERE workid = '" . $workdata['workid']
                . "' and workerno = '" . $workdata['workerno'] . "'", $con)
            or die ('Error: ' . mysql_error());
        }
    }
}

//仕事がタグ付けされているか確認。されていたら個人パラメータを変更
$workparamresult
    = mysql_query("SELECT * FROM matchingparam_work WHERE groupno='"
    . $workdata['groupno'] . "' and workid='" . $workdata['workid'] . "'", $con)
or die ('Error: ' . mysql_error());
if (mysql_num_rows($workparamresult) > 0) {
    $workparam = mysql_fetch_assoc($workparamresult);
    if ($workdata['status'] == 1) {
        updateMatchingParam_human($workparam, $workdata['workerno'], 0.1, true);
    } else {
        updateMatchingParam_human($workparam, $workdata['workerno'], 0.1,
            false);
    }
}


/*
//グループ管理者(たち)にメール送信
$mailresults = mysql_query("SELECT mail, nickname FROM db_user WHERE userno IN (SELECT userno FROM grouplist WHERE groupno='".$workdata['groupno']."' and admin='1')", $con) or die ('Error: ' . mysql_error());
$mailaddresses = array();
while($mailrows = mysql_fetch_assoc($mailresults)){
    $mailaddresses[] = $mailrows;
}

foreach($mailaddresses as $eachaddress){
    $mailto = $eachaddress['mail'];
    $nickname = $eachaddress['nickname'];
    if($workdata['status']==1){
        $subject = "オファー承諾のお知らせ";
        $messageText = $nickname."様\r\nグループメンバーへのオファーが承諾されました。\r\n詳細はログインしてご確認ください。\r\n";
        $messageHtml = $nickname."様<br />グループメンバーへのオファーが承諾されました。<br />詳細はログインしてご確認ください。<br />";
    } else {
        $subject = "オファー拒否のお知らせ";
        $messageText = $nickname."様\r\nグループメンバーへのオファーが断られました。\r\n詳細はログインしてご確認ください。\r\n";
        $messageHtml = $nickname."様<br />グループメンバーへのオファーが断られました。<br />詳細はログインしてご確認ください。<br />";
    }
    sendEmail($baseurl, $subject, $messageText, $messageHtml, $mailto);
}
*/

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

//$activitylog = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('".$workdata['workerno']."', 'apply4specialist.php', '".date('Y-m-d G:i:s')."')", $con) or die('Error: ' . mysql_error());

mysql_close($con);

?>