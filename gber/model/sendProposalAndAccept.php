<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';

// 仕事ID
$post = json_decode(file_get_contents('php://input'), true);

// 仕事IDを基に仕事ステータスを変更する
$sql2 = "UPDATE worklist SET status = '2', price = '"
    . $post['price'] . "', content = '" . $post['content'] . "', workdatetime = '"
    . $post['workdatetime'] . "' WHERE id = '" . $post['workid'] . "'";
mysql_query($sql2, $con) or die('Error: ' . mysql_error());

$datetime = date('Y-m-d G:i:s');
$message = "<a href=\"quotation.php?workid=" . $post['workid'] . "&groupno="
    . $post['groupno'] . "\" rel=\"external\">" . $post['worktitle'] . "</a>";
$userno = $post['userno'];
$groupno = $post['groupno'];

//掲示板に投稿する
$result = mysql_query("INSERT INTO bbs_group (groupno, senderid, message, datetime, jobpost) VALUES ('$groupno', '$userno', '$message', '$datetime', '1')",
    $con) or die ("Query error: " . mysql_error());

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

mysql_close($con);

?>