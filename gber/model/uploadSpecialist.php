<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/db.php';

$userno = mysql_real_escape_string($_POST["userno"]);
$lat = mysql_real_escape_string($_POST["lat"]);
$lng = mysql_real_escape_string($_POST["lng"]);
$worktitle = mysql_real_escape_string($_POST["worktitle"]);
$content = mysql_real_escape_string($_POST["content"]);
$workdatetime = mysql_real_escape_string($_POST["workdatetime"]);
$contact = mysql_real_escape_string($_POST["contact"]);
$genre = mysql_real_escape_string($_POST["genre"]);
$worktype = mysql_real_escape_string($_POST["worktype"]);

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['GROUP_ADMIN'], ['groupno' => $genre])){
    http_response_code(403);
    exit;
}

//仕事を追加
$sql = "INSERT INTO worklist (groupno, userno, lat, lng, worktitle, content, workdatetime, contact) VALUES ('$genre', '$userno', '$lat', '$lng', '$worktitle', '$content', '$workdatetime', '$contact')";
mysql_query($sql, $con) or die('Error: ' . mysql_error());
$workid = mysql_insert_id();

//仕事のタグ情報をmatchingparam_workに追加する。predefined_workで定義されている場合は定義済みのタグベクトルを使う
//定義されていない場合(worktype==0)は、タグベクトルを挿入しない（worktaglist.phpからあとでタグをつける）
$db = DB::getInstance();
if($worktype != 0) {
    $worktype = $db->getWorktypeById($worktype);
    unset($worktype['id']);
    unset($worktype['name']);
    $worktype['workid'] = $workid;
    $db->insertMatchingParamWork($worktype);
}

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

//グループ管理者(たち)にメール送信
$mailresults
    = mysql_query("SELECT mail, nickname FROM db_user WHERE userno IN (SELECT userno FROM grouplist WHERE groupno='"
    . $genre . "' and admin='1')", $con) or die ('Error: ' . mysql_error());
$mailaddresses = array();
while ($mailrows = mysql_fetch_assoc($mailresults)) {
    $mailaddresses[] = $mailrows;
}
foreach ($mailaddresses as $eachaddress) {
    $mailto = $eachaddress['mail'];
    $nickname = $eachaddress['nickname'];
    $subject = $groupnamelist[$genre] . "グループに新規仕事登録のお知らせ";
    $messageText = $nickname . "様\r\n" . $groupnamelist[$genre]
        . "グループに新規で仕事が登録されましたので、管理者の方々にお知らせします。\r\n仕事タイトルは「" . $worktitle
        . "」です。\r\nその仕事の責任者の方は、ログインして「②見積もり」から作業を行ってください。\r\n";
    $messageHtml = $nickname . "様<br />" . $groupnamelist[$genre]
        . "グループに新規で仕事が登録されましたので、管理者の方々にお知らせします。<br />仕事タイトルは「" . $worktitle
        . "」です。<br />その仕事の責任者の方は、ログインして「②見積もり」から作業を行ってください。<br />";
    sendEmail($baseurl, $subject, $messageText, $messageHtml, $mailto);
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $userno . "', 'uploadSpecialist.php', '" . date('Y-m-d G:i:s') . "')", $con)
or die('Error: ' . mysql_error());

mysql_close($con);

?>