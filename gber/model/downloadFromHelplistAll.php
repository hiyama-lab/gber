<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

//statusは，0応募なし，1応募あり，2確定，3評価済み，4削除済み
$demosql = $_ENV["IS_DEMO"] === 'true' ? "" : "and helpdate.workdate > DATE_SUB(CURRENT_DATE(),interval 1 day)";
$result = mysql_query("SELECT DISTINCT helplist.id,helplist.worktitle,helplist.lat,helplist.lng FROM helplist INNER JOIN helpdate ON helplist.id = helpdate.workid WHERE helplist.status < 2 $demosql ORDER BY helpdate.workdate LIMIT 100") or die ("Query error: " . mysql_error());
$records = array();
while ($row = mysql_fetch_assoc($result)) {
    $records[] = $row;
}

mysql_close($con);

//仕事データを返す
echo $_GET['jsoncallback'] . '(' . json_encode($records) . ');';
?>