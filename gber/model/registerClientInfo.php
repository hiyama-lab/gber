<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
require_once __DIR__ . '/../lib/auth.php';

// 仕事ID
$post = json_decode(file_get_contents('php://input'), true);

$clientid = $post['clientid'];
$workid = $post['workid'];
$comment = $post['comment'];
$groupno = $post['groupno'];

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['GROUP_ADMIN'], ['groupno' => $groupno])){
    http_response_code(403);
    exit;
}

// コメントが存在すればUpdate、なければInsert
$result = mysql_query("SELECT infoid FROM clientinfo WHERE workid='" . $workid . "'", $con) or die ("Query error: "
    . mysql_error());
if (mysql_num_rows($result) == 0) {
    mysql_query("INSERT INTO clientinfo (clientid, workid, comment) VALUES ('$clientid','$workid','$comment')",
        $con) or die ("Query error: " . mysql_error());
} else {
    mysql_query("UPDATE clientinfo SET comment='" . $comment
        . "' WHERE workid='" . $workid . "'", $con) or die ("Query error: "
        . mysql_error());
}

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

mysql_close($con);

?>