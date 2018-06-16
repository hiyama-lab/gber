<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
require_once __DIR__ . '/../lib/auth.php';

// 仕事ID
$post = json_decode(file_get_contents('php://input'), true);

$userno = $post['userno'];
$groupno = $post['groupno'];
$workid = $post['workid'];
$worktitle = $post['worktitle'];

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['GROUP_ADMIN'], ['groupno' => $groupno])){
    http_response_code(403);
    exit;
}

// 仕事IDを基に仕事ステータスを変更する
$sql2 = "UPDATE worklist SET status = '2', price = '"
    . $post['price'] . "', content = '" . $post['content'] . "', workdatetime = '"
    . $post['workdatetime'] . "' WHERE id = '" . $post['workid'] . "'";
mysql_query($sql2, $con) or die('Error: ' . mysql_error());

$datetime = date('Y-m-d G:i:s');

//掲示板に投稿する
$result = mysql_query("INSERT INTO bbs_group (groupno, senderid, message, datetime, jobpost) VALUES ('$groupno', '$userno', '$worktitle', '$datetime', '$workid')",
    $con) or die ("Query error: " . mysql_error());

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

mysql_close($con);

?>