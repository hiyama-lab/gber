<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

//一般募集について，日付が選択されたらその日の仕事をDBから選んでJSON形式で返す

$workdate = mysql_real_escape_string($_POST["workdate"]);
$userno = mysql_real_escape_string($_POST["userno"]);

//statusは，0応募なし，1応募あり，2確定，3評価済み，4削除済み
$sql
    = "SELECT helplist.id,helplist.worktitle,helpdate.workdate,helplist.lat,helplist.lng FROM helplist INNER JOIN helpdate ON helplist.id = helpdate.workid WHERE helplist.status < 2 and helpdate.workdate = '"
    . $workdate . "' ORDER BY helpdate.workdate LIMIT 100";
$result = mysql_query($sql) or die ("Query error: " . mysql_error());
$records = array();
while ($row = mysql_fetch_assoc($result)) {
    $records[] = $row;
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $userno . "', 'downloadFromHelplistOnDate.php?workdate=" . $workdate . "','"
    . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

mysql_close($con);

//仕事データを返す
echo $_GET['jsoncallback'] . '(' . json_encode($records) . ');';
?>
