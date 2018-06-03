<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

$post = json_decode(file_get_contents('php://input'), true);
$workid = $post['workid'];
$workday = $post['workday'];
$workerno = $post['workerno'];
$worktime = $post['worktime'];
$groupno = $post['groupno'];

//データをworkdateテーブルに挿入する
$result = mysql_query("INSERT INTO workdate (workid, workday, am, pm, workerno, status, reportflag, workreport, worktime) VALUES ('$workid', '$workday', '0', '0', '$workerno', '1', '1', '見積もり', '$worktime')",
    $con) or die ("Query error: " . mysql_error());

//既にworkevalに登録されているか確認する
$result3 = mysql_query("SELECT evalid FROM workeval WHERE workid = '" . $workid . "' and workerno = '" . $workerno . "'", $con)
or die ('Error: ' . mysql_error());
$userineval = mysql_num_rows($result3);
if ($userineval == 0) {// まだworkevalに登録されてなかったら登録する
    $result4 = mysql_query("INSERT INTO workeval (workid,workerno,selfeval) VALUES ('$workid','$workerno','1')", $con)
    or die ('Error: ' . mysql_error());
}

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

mysql_close($con);

?>