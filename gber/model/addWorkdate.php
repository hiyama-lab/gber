<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';

$post = json_decode(file_get_contents('php://input'), true);
$workid = $post['workid'];
$workdate = $post['workdate'];

$sql2 = "INSERT INTO helpdate (workid,workdate) VALUES ('$workid','$workdate')";
if (!mysql_query($sql2, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>