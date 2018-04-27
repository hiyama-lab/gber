<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';

$post = json_decode(file_get_contents('php://input'), true);

// 入力をもとに，あるグループのあるワーカーの管理者ステータスを1に設定する
// レコードが存在しない場合は、レコードを作成する
$check_result = mysql_query("SELECT registeredid FROM grouplist WHERE userno='"
    . $post['userno'] . "' and groupno='" . $post['groupno'] . "'");
if (mysql_num_rows($check_result) == 0) {
    $sql2 = "INSERT INTO grouplist (userno, groupno, admin) VALUES ("
        . $post['userno'] . ", " . $post['groupno'] . ", 1)";
} else {
    $sql2 = "UPDATE grouplist SET admin=1 WHERE userno='" . $post['userno']
        . "' and groupno='" . $post['groupno'] . "'";
}
if (!mysql_query($sql2, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}
mysql_close($con);

?>