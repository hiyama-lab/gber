<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';

$post = json_decode(file_get_contents('php://input'), true);

$sql = "UPDATE workdate SET reportflag='1', workreport = '"
    . $post['workreport'] . "' WHERE workid='" . $post['workid'] . "' and workday='"
    . $post['workday'] . "' and workerno='" . $post['workerno'] . "' and am='"
    . $post['am'] . "' and pm='" . $post['pm'] . "'";
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

// もし全日程分の日報が提出されていたら，workevalのselfevalを1にする
$result = mysql_query("SELECT dateid FROM workdate WHERE reportflag='0' and workid='" . $post['workid'] . "' and workerno='"
    . $post['workerno'] . "'", $con) or die ("Query error: " . mysql_error());
if (mysql_num_rows($result) == 0) {
    $result2 = mysql_query("UPDATE workeval SET selfeval='1' WHERE workid='" . $post['workid'] . "' and workerno='"
        . $post['workerno'] . "'", $con) or die ("Query error: " . mysql_error());
}

mysql_close($con);

?>