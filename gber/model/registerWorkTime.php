<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';

$post = json_decode(file_get_contents('php://input'), true);

$sql = "UPDATE workdate SET worktime = '" . $post['worktime']
    . "' WHERE workid='" . $post['workid'] . "' and workday='" . $post['workday']
    . "' and workerno='" . $post['workerno'] . "' and am='" . $post['am'] . "' and pm='"
    . $post['pm'] . "'";
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>