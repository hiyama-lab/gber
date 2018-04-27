<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';

date_default_timezone_set('Asia/Tokyo');
$datetime = date('Y-m-d G:i:s');

$userno = mysql_real_escape_string($_POST["userno"]);
$groupno = mysql_real_escape_string($_POST["groupno"]);
$postcontent = mysql_real_escape_string($_POST["postcontent"]);

$sql = "INSERT INTO bbs_group (groupno, senderid, message, datetime) VALUES ('$groupno', '$userno', '$postcontent', '$datetime')";

if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

//$activitylog = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('".$userno."', 'post2bbs.php?groupno=".$groupno."', '".date('Y-m-d G:i:s')."')", $con) or die('Error: ' . mysql_error());

mysql_close($con);

?>