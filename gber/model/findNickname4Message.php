<?php
header('Content-type: text/plain; charset=UTF-8');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';

require_logined_session();

//グループ管理者画面のメンバー追加機能で，入力されたニックネームに部分一致する近いユーザを取得し返す

$nicknamecandidate = mysql_real_escape_string($_POST["search"]);

if ($nicknamecandidate == "") {
    exit;
}

$result
    = mysql_query("SELECT userno, nickname FROM db_user WHERE nickname like '%$nicknamecandidate%'")
or die ("Query error: " . mysql_error());

$string = '';
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        $string .= "<span><a href=\"mypage.php?userno=" . $row['userno']
            . "\" rel=\"external\"><img src=\"./model/showuserimage.php?userno="
            . $row['userno']
            . " onerror=\"this.src='img/noimage.svg';\" width=\"25px\" height=\"25px\" /></a>　ID: "
            . $row['userno'] . "　ニックネーム: " . $row['nickname']
            . "</span>　<button type=\"button\" onclick=\"addMessageMember("
            . $row['userno'] . ")\">追加する</button></br>\n";
    }
} else {
    $string = "<div>該当するユーザはいません</div>";
}

echo $string;

mysql_close($con);

?>