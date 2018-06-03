<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

$post = json_decode(file_get_contents('php://input'), true);

$sql = "DELETE FROM messagemember WHERE messageid=" . $post['messageid']
    . " and memberid=" . $post['memberno'];

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('0', 'removeMessageMember.php', '"
    . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>