<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['GROUP_ADMIN'], ['groupno' => $post['groupno']])){
    http_response_code(403);
    exit;
}

//グループメンバーの技能レベルを登録する

//植木剪定グループの例：
// 1: 初心者．基礎講習6回，ステップアップ講習6回の植木塾卒業生
// 2: 中級者．1ヶ月程度の勤続で身につけられる技能レベル
// 3: 上級者．1年程度の勤続で身につけられる技能レベル
// 超上級者(先生級)：見積もりに行って，何人何時間で作業できるか分かるプロフェッショナル．5年以上の勤続．今回は未実装

$inputdata = json_decode(file_get_contents('php://input'), true);
$userno = $inputdata['userno'];
$groupno = $inputdata['groupno'];
$neweval = $inputdata['neweval'];

$sql2 = "UPDATE grouplist SET eval = '" . $neweval . "' WHERE groupno = '" . $groupno
    . "' and userno = '" . $userno . "'";
if (!mysql_query($sql2, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $userno . "', 'changeEval.php?userno=" . $userno . "&groupno=" . $groupno . "', '"
    . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

mysql_close($con);

?>