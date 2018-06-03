<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

$workdata = json_decode(file_get_contents('php://input'), true);

// まずはヘルプマッチングで選択済みにする
$sql = "UPDATE helpmatching SET status = 1 WHERE workid = '" . $workdata['workid']
    . "' and applyuserno = '" . $workdata['workerno'] . "'";
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

// これまでに選択した人数を調べる
$sql2 = "SELECT * FROM helpmatching WHERE workid='" . $workdata['workid']
    . "' and status = '1'";
$result2 = mysql_query($sql2);
$records2 = array();
while ($row2 = mysql_fetch_assoc($result2)) {
    $records2[] = $row2;
}
$selectednum = count($records2);

// 募集人数を調べる
$sql3 = "SELECT * FROM helplist WHERE id='" . $workdata['workid'] . "'";
$result3 = mysql_query($sql3);
$records3 = array();
while ($row3 = mysql_fetch_assoc($result3)) {
    $records3[] = $row3;
}
$totalworkernum = $records3[0]['workernum'];

// 募集人数に達していたら，他の候補者を削除し募集終了する
if ($selectednum == $totalworkernum) {
    $sql4 = "UPDATE helplist SET status = 2 WHERE id = '" . $workdata['workid']
        . "'";
    mysql_query($sql4, $con);
    $sql5 = "DELETE FROM helpmatching where workid = '" . $workdata['workid']
        . "' and status = '0'";
    mysql_query($sql5, $con);
}

//選択された応募者にメールで通知
$result = mysql_query("SELECT mail, nickname FROM db_user WHERE userno ='"
    . $workdata['workerno'] . "'", $con) or die ('Error: ' . mysql_error());
$mailaddress = mysql_fetch_assoc($result);
$mailto = $mailaddress['mail'];
$nickname = $mailaddress['nickname'];
$subject = "応募承認のお知らせ";
$messageText = $nickname
    . "様\r\nGBERで応募していた案件が依頼者に承認されました。\r\n詳細はログインしてご確認ください。\r\n";
$messageHtml = $nickname
    . "様<br />GBERで応募していた案件が依頼者に承認されました。<br />詳細はログインしてご確認ください。<br />";

sendEmail($baseurl, $subject, $messageText, $messageHtml, $mailto);


mysql_close($con);

?>