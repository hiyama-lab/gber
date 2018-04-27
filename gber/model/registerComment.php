<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';

$post = json_decode(file_get_contents('php://input'), true);

$sql = "UPDATE workinterest SET comment = '"
    . $post['comment'] . "' WHERE workid='" . $post['workid'] . "' and userno='"
    . $post['workerno'] . "'";

if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>