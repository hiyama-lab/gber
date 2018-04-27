<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
include __DIR__ . '/updateMatchingParam_human.php';

$workdata = json_decode(file_get_contents('php://input'), true);

//興味の有無を登録
mysql_query("UPDATE helpchousei SET attendance='" . $workdata['attendance']
    . "' WHERE helpdateid='" . $workdata['helpdateid'] . "' and workerno='"
    . $workdata['workerno'] . "'", $con) or die ('Error: ' . mysql_error());

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $workdata['workerno'] . "', 'changeAttendance.php', '" . date('Y-m-d G:i:s')
    . "')", $con) or die('Error: ' . mysql_error());

mysql_close($con);

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

?>