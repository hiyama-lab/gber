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
mysql_query("UPDATE helpmatching SET interest='" . $workdata['interest']
    . "' WHERE workid='" . $workdata['workid'] . "' and applyuserno='"
    . $workdata['workerno'] . "'", $con) or die ('Error: ' . mysql_error());

$result5 = mysql_query("SELECT messageid FROM message WHERE workid='"
    . $workdata['workid'] . "'", $con) or die ("Query error: " . mysql_error());
$messageid = mysql_fetch_assoc($result5)['messageid'];
$userno = $workdata['workerno'];

//仕事がタグ付けされているか確認。されていたら個人パラメータを変更
$workparamresult
    = mysql_query("SELECT * FROM matchingparam_work WHERE groupno='0' and workid='"
    . $workdata['workid'] . "'", $con) or die ('Error: ' . mysql_error());
if (mysql_num_rows($workparamresult) > 0) {
    $workparam = mysql_fetch_assoc($workparamresult);
    if ($workdata['interest'] == 1) {
        updateMatchingParam_human($workparam, $workdata['workerno'], 0.2, true);
    } else {
        updateMatchingParam_human($workparam, $workdata['workerno'], 0.2,
            false);
    }
}

//重複確認
$result7
    = mysql_query("SELECT messagememberid FROM messagemember WHERE messageid = '"
    . $messageid . "' and memberid = '" . $userno . "'", $con) or die ("Query error: "
    . mysql_error());
if (mysql_num_rows($result7) < 1) {
    $result6
        = mysql_query("INSERT INTO messagemember (messageid, memberid) VALUES ('$messageid','$userno')",
        $con) or die ("Query error: " . mysql_error());
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $workdata['workerno'] . "', 'changeIppanInterest.php', '" . date('Y-m-d G:i:s')
    . "')", $con) or die('Error: ' . mysql_error());

mysql_close($con);

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

?>