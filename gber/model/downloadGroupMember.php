<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

//マスターページで管理者を任命する際に，選択されたグループのメンバーリストから管理者でない人のデータを取得して返す

$jqinput = json_decode(file_get_contents('php://input'), true);

$result
    = mysql_query("SELECT userno, nickname FROM db_user WHERE userno IN (SELECT userno FROM grouplist WHERE groupno='"
    . $jqinput['groupno'] . "' and admin='0')") or die ("Query error: "
    . mysql_error());
# 誰もグループに所属していない場合の処理
if (mysql_num_rows($result) == 0) {
    $result = mysql_query("SELECT userno, nickname FROM db_user")
    or die ("Query error: " . mysql_error());
}
$records = array();
while ($row = mysql_fetch_assoc($result)) {
    $records[] = $row;
}

mysql_close($con);

echo $_GET['jsoncallback'] . '(' . json_encode($records) . ');';
?>