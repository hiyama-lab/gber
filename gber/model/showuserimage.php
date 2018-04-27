<?php
//header('Content-type: text/plain; charset=UTF-8');
//echo "test";
include __DIR__ . '/../lib/mysql_credentials.php';
$userno = $_GET['userno'];
$result
    = mysql_query("SELECT mime,photodata, length(photodata) as size FROM photodata WHERE userno = '"
    . $userno . "'") or die ('Error: ' . mysql_error());
$row = mysql_fetch_array($result);
header("Content-Type: " . $row['mime']);
header("Content-Length: " . $row['size']);
header("Content-Disposition: inline");
echo $row['photodata'];
?>
