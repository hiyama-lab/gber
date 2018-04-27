<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';

date_default_timezone_set('Asia/Tokyo');

$groupno = mysql_real_escape_string($_POST["groupno"]);
$workid = mysql_real_escape_string($_POST["workid"]);
$lat = mysql_real_escape_string($_POST["changelat"]);
$lng = mysql_real_escape_string($_POST["changelng"]);

//ヘルプリストに挿入
mysql_query("UPDATE worklist SET lat='" . $lat . "', lng='" . $lng
    . "' WHERE id='" . $workid . "'", $con) or die('Error: ' . mysql_error());
echo $_GET['jsoncallback'] . '({"status":"succeed"});';

mysql_close($con);

?>