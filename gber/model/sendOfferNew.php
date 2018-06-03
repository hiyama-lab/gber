<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
require_once __DIR__ . '/../lib/auth.php';

$post = json_decode(file_get_contents('php://input'), true);
$workid = $post['workid'];
$workday = $post['workday'];
$am = $post['am'];
$pm = $post['pm'];
$workerno = $post['workerno'];
$groupno = $post['groupno'];

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['GROUP_ADMIN'], ['groupno' => $groupno])){
    http_response_code(403);
    exit;
}

//重複チェック
$result7 = mysql_query("SELECT dateid FROM workdate WHERE workid='"
    . $workid . "' and workday='" . $workday . "' and am='" . $am . "' and pm='" . $pm
    . "' and workerno='" . $workerno . "'", $con) or die ("Query error: "
    . mysql_error());
if (mysql_num_rows($result7) > 0) {
    exit;
}

//仕事タイトルを取得
$result700 = mysql_query("SELECT worktitle FROM worklist WHERE id='" . $workid . "'", $con) or die ("Query error: " . mysql_error());
$worktitle = mysql_fetch_assoc($result700)['worktitle'];

//仕事データをworkdateテーブルに挿入する
$result = mysql_query("INSERT INTO workdate (workid, workday, am, pm, workerno) VALUES ('$workid', '$workday', '$am', '$pm', '$workerno')",
    $con) or die ("Query error: " . mysql_error());

//スケジュールを勝手に書き換える
$workday_array = explode("-", $workday);
$year = $workday_array[0];
$month = ltrim($workday_array[1], "0");
$day = ltrim($workday_array[2], "0");
if ($am == 1) {
    $result2 = mysql_query("UPDATE schedule SET d" . $day
        . "_am = '0' WHERE userno=$workerno AND year=$year AND month=$month",
        $con) or die ("Query error: " . mysql_error());
} else {
    $result2 = mysql_query("UPDATE schedule SET d" . $day
        . "_pm = '0' WHERE userno=$workerno AND year=$year AND month=$month",
        $con) or die ("Query error: " . mysql_error());
}

if ($result) {
    //既にworkevalに登録されているか確認する
    $result3 = mysql_query("SELECT evalid FROM workeval WHERE workid = '" . $workid . "' and workerno = '" . $workerno . "'", $con)
    or die ('Error: ' . mysql_error());
    $userineval = mysql_num_rows($result3);
    if ($userineval == 0) {// まだworkevalに登録されてなかったら登録する
        $result4 = mysql_query("INSERT INTO workeval (workid,workerno) VALUES ('$workid','$workerno')", $con)
        or die ('Error: ' . mysql_error());
    } else {
        if ($userineval > 0) {// 既に登録されていたら，selfevalを0に戻す
            $result5 = mysql_query("UPDATE workeval SET selfeval='0' WHERE workid = '" . $workid
                . "' and workerno = '" . $workerno . "'", $con) or die ('Error: '
                . mysql_error());
        }
    }
}

$ampmstr = ["午後", "午前"];
//オファーを送信されたワーカーにメールで通知。ただし今日以降の日付だったら！
$today = date("Y-m-d");
if ($workday >= $today) {

    $result = mysql_query("SELECT mail, nickname FROM db_user WHERE userno ='"
        . $workerno . "'", $con) or die ('Error: ' . mysql_error());
    $mailaddress = mysql_fetch_assoc($result);
    $mailto = $mailaddress['mail'];
    $nickname = $mailaddress['nickname'];
    $subject = "オファーのお知らせ";
    $messageText = $nickname . "様\r\nGBERで" . $workday . "の" . $ampmstr[$am]
        . "の仕事のオファーが届きました。\r\n仕事タイトルは「" . $worktitle
        . "」です。\r\nログインして、「②オファーを受ける」からご確認の上ご回答ください。\r\n";
    $messageHtml = $nickname . "様<br />GBERで" . $workday . "の" . $ampmstr[$am]
        . "の仕事のオファーが届きました。<br />仕事タイトルは「" . $worktitle
        . "」です。<br />ログインして、「②オファーを受ける」からご確認の上ご回答ください。<br />";

    sendEmail($baseurl, $subject, $messageText, $messageHtml, $mailto);
}

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

mysql_close($con);

?>