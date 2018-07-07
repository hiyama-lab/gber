<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
require_once __DIR__ . '/../lib/auth.php';

$post = json_decode(file_get_contents('php://input'), true);

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['GROUP_ADMIN'], ['groupno' => $post['groupno']])){
    http_response_code(403);
    exit;
}

//確定済みのワーカーのメールアドレスを一覧で取得
$result
    = mysql_query("SELECT nickname, mail FROM db_user WHERE userno IN (SELECT DISTINCT workerno FROM workdate WHERE workid='" . $post['workid'] . "')", $con)
or die ("Query error: " . mysql_error());
$records = array();
while ($row = mysql_fetch_assoc($result)) {
    $records[] = $row;
}

$subject = $post['mailsubject'];
$contentHtml = "GBERの" . $groupnamelist[$post['groupno']] . "グループ管理者より"
    . $post['worktitle'] . "の件で連絡がありました。\r\n−−−−−−−−−−−−\r\n" . $post['mailcontent']
    . "\r\n";
foreach ($records as $eachrecord) {
    $messageText = $eachrecord['nickname'] . "様\r\nGBERの"
        . $groupnamelist[$post['groupno']] . "グループ管理者より" . $post['worktitle']
        . "の件で連絡がありました。\r\n−−−−−−−−−−−−\r\n" . $post['mailcontent'] . "\r\n";
    $messageHtml = $eachrecord['nickname'] . "様<br />GBERの"
        . $groupnamelist[$post['groupno']] . "グループ管理者より" . $post['worktitle']
        . "の件で連絡がありました。<br />−−−−−−−−−−−−<br />" . $post['mailcontent'] . "<br />";
    sendEmail($baseurl, $subject, $messageText, $messageHtml,
        $eachrecord['mail']);
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('0', 'sendEmail2workers.php', '"
    . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

$result2
    = mysql_query("INSERT INTO emaillog (groupno,workid,subject,content) VALUES ('"
    . $post['groupno'] . "','" . $post['workid'] . "','$subject','$contentHtml')",
    $con) or die ("Query error: " . mysql_error());

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

?>