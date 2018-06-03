<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
require_once __DIR__ . '/../lib/auth.php';

// 仕事ID
$post = json_decode(file_get_contents('php://input'), true);

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['GROUP_ADMIN'], ['groupno' => $post['groupno']])){
    http_response_code(403);
    exit;
}

mysql_query("UPDATE workeval SET evaluation='"
    . $post['evaluation'] . "', comment='" . $post['comment'] . "' WHERE workerno='"
    . $post['workerno'] . "' and workid='" . $post['workid'] . "'", $con)
or die ("Query error: " . mysql_error());

// 全てのワーカーに対して評価が行われていたら，worklistのstatusを4に変更し，今後一切の変更をシャットアウトする
$result = mysql_query("SELECT evalid FROM workeval WHERE workid='" . $post['workid'] . "' and evaluation='0'", $con)
or die ("Query error: " . mysql_error());
if (mysql_num_rows($result) == 0) {
    $result3 = mysql_query("UPDATE worklist SET status='4' WHERE id='" . $post['workid'] . "'", $con)
    or die ("Query error: " . mysql_error());
}

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

//$activitylog = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('".$post['workerno']."', 'registerEvalSpecialistNew.php', '".date('Y-m-d G:i:s')."')", $con) or die('Error: ' . mysql_error());

mysql_close($con);

?>