<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

$post = json_decode(file_get_contents('php://input'), true);
$giver = $post['giver'];
$taker = $post['taker'];

// 二重登録防止のため、すでに登録されているかを確認。1人に対して複数人の代理人がつくことは許可する。そして1人が複数の代理人をすることも許可する。
$result = mysql_query("SELECT caretakerid FROM caretakerlist WHERE giver='"
    . $giver . "' and taker='" . $taker . "'", $con) or die ("Query error: "
    . mysql_error());
if (mysql_num_rows($result) > 0) {
    exit;
}

// 挿入する
$sql = "INSERT INTO caretakerlist (giver, taker) VALUES ('$giver', '$taker')";
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>