<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';

$post = json_decode(file_get_contents('php://input'), true);

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['GROUP_ADMIN'], ['groupno' => $post['groupno']])){
    http_response_code(403);
    exit;
}

// 仕事IDを基に仕事ステータスを3に設定する
$sql2 = "UPDATE worklist SET status = 3 WHERE id = '"
    . $post['workid'] . "'";
if (!mysql_query($sql2, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>