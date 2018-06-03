<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

date_default_timezone_set('Asia/Tokyo');
$datetime = date('Y-m-d G:i:s');

$userno = mysql_real_escape_string($_POST["userno"]);
$messageid = mysql_real_escape_string($_POST["messageid"]);
$postcontent = mysql_real_escape_string($_POST["postcontent"]);

$result = mysql_query("UPDATE message SET lastupdate = '" . $datetime
    . "' WHERE messageid='" . $messageid . "'") or die ("Query error: "
    . mysql_error());

$sql
    = "INSERT INTO messageeach (messageid, senderid, message, messagedate) VALUES ('$messageid', '$userno', '$postcontent', '$datetime')";

if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $userno . "', 'post2message.php', '" . date('Y-m-d G:i:s') . "')", $con)
or die('Error: ' . mysql_error());

mysql_close($con);
?>