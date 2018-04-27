<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';

// 仕事ID
$input = json_decode(file_get_contents('php://input'), true);

mysql_query("UPDATE workeval SET evaluation='"
    . $input['evaluation'] . "', comment='" . $input['comment'] . "' WHERE workerno='"
    . $input['workerno'] . "' and workid='" . $input['workid'] . "'", $con)
or die ("Query error: " . mysql_error());

// 全てのワーカーに対して評価が行われていたら，worklistのstatusを4に変更し，今後一切の変更をシャットアウトする
$result = mysql_query("SELECT evalid FROM workeval WHERE workid='" . $input['workid'] . "' and evaluation='0'", $con)
or die ("Query error: " . mysql_error());
if (mysql_num_rows($result) == 0) {
    $result3 = mysql_query("UPDATE worklist SET status='4' WHERE id='" . $input['workid'] . "'", $con)
    or die ("Query error: " . mysql_error());
}

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

//$activitylog = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('".$input['workerno']."', 'registerEvalSpecialistNew.php', '".date('Y-m-d G:i:s')."')", $con) or die('Error: ' . mysql_error());

mysql_close($con);

?>