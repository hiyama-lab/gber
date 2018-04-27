<?php
header('Content-type: text/plain; charset=UTF-8');

include __DIR__ . '/../lib/mysql_credentials.php';

//グループ管理者画面のメンバー追加機能で，入力されたニックネームに部分一致する近いユーザを取得し返す

$candidate = mysql_real_escape_string($_POST["search"]);
$groupno = mysql_real_escape_string($_POST["groupno"]);

$result = mysql_query("SELECT id, worktitle, content FROM worklist WHERE groupno=$groupno and status=4 and ( content like '%$candidate%' or worktitle like '%$candidate%' ) ORDER BY id DESC LIMIT 100")
or die ("Query error: " . mysql_error());

$string
    = "<li data-role=\"list-divider\" role=\"heading\" class=\"ui-li-divider ui-bar-inherit ui-first-child\">終了案件(検索)</li>\n";
if (mysql_num_rows($result) > 0) {
    $i = 1;
    while ($row = mysql_fetch_assoc($result)) {
        if ($i == mysql_num_rows($result)) {
            $string .= "<li data-theme=\"c\" class=\"ui-last-child\"><a href=\"quotation.php?workid="
                . $row['id'] . "&groupno=" . $groupno
                . "\" rel=\"external\" class=\"ui-btn ui-btn-c ui-btn-icon-right ui-icon-carat-r\"><h2>"
                . $row['worktitle'] . "</h2><p>" . $row['content'] . "</p></a></li>\n";
        } else {
            $string .= "<li data-theme=\"c\"><a href=\"quotation.php?workid="
                . $row['id'] . "&groupno=" . $groupno
                . "\" rel=\"external\" class=\"ui-btn ui-btn-c ui-btn-icon-right ui-icon-carat-r\"><h2>"
                . $row['worktitle'] . "</h2><p>" . $row['content'] . "</p></a></li>\n";
            $i++;
        }
    }
} else {
    $string = "<div>該当する終了案件はありません</div>";
}

echo $string;

mysql_close($con);

?>