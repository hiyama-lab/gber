<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';

$post = json_decode(file_get_contents('php://input'), true);
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

$sql = "UPDATE helplist SET " . $post['field'] . "='" . $post['newcontent']
    . "' WHERE id=" . $post['workid'] . "";

if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>