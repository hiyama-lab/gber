<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

$inputdata = json_decode(file_get_contents('php://input'), true);

$userno = $inputdata['userno'];

$result = mysql_query("SELECT nickname FROM db_user WHERE userno='" . $userno . "'",
    $con) or die ("Query error: " . mysql_error());
$nickname = mysql_fetch_assoc($result)['nickname'];
$datetime = date('Y-m-d G:i:s');

$result2
    = mysql_query("INSERT INTO message (messagename, lastupdate) VALUES ('$nickname', '$datetime')",
    $con) or die ("Query error: " . mysql_error());

$result3 = mysql_query("SELECT messageid FROM message WHERE messagename='"
    . $nickname . "' and lastupdate = '" . $datetime . "'", $con)
or die ("Query error: " . mysql_error());
$messageid = mysql_fetch_assoc($result3)['messageid'];

$result4
    = mysql_query("INSERT INTO messagemember (messageid, memberid) VALUES ('$messageid', '$userno')",
    $con) or die ("Query error: " . mysql_error());

echo $_GET['jsoncallback'] . '({"messageid":"' . $messageid . '"});';

mysql_close($con);

?>