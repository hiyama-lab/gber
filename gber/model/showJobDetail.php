<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

// 仕事ID
$post = json_decode(file_get_contents('php://input'), true);
$workid = $post['workid'];

// 仕事IDを基にステータスを4(公開)に設定する
$sql2 = "UPDATE helplist SET status = 0 WHERE id = '" . $workid . "'";
if (!mysql_query($sql2, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>