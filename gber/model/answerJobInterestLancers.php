<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

$post = json_decode(file_get_contents('php://input'), true);

$sql = "UPDATE dummyrecommendation SET top" . $post['recommendrank'] . "ans = "
    . $post['interest'] . " WHERE recommendationid = " . $post['recommendid'];
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>