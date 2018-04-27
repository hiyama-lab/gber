<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';

// 仕事ID
$workid = mysql_real_escape_string($_POST["workid"]);

// 仕事IDを基に応募者リストを取得する
$sql2 = "SELECT * FROM helpmatching WHERE workid='" . $workid . "'";
$result2 = mysql_query($sql2, $con) or die ("Query error: " . mysql_error());
$records2 = array();
while ($row2 = mysql_fetch_assoc($result2)) {
    $records2[] = $row2;
}

// applyusernoを取得し，jsからの受け取りを行う
foreach ($records2 as $eachrecord) {
    $userno = $eachrecord['applyuserno'];
    if ($eachrecord['status'] == 1) {
        $evaluation = mysql_real_escape_string($_POST["backing" . $userno]);
        $comment = mysql_real_escape_string($_POST["comment" . $userno]);
        mysql_query("UPDATE helpmatching SET evaluation='" . $evaluation
            . "', comment='" . $comment . "' WHERE workid = '" . $workid
            . "' and applyuserno = '" . $userno . "'", $con) or die ("Query error: "
            . mysql_error());
    } else {
        mysql_query("DELETE FROM helpmatching WHERE workid='" . $workid
            . "' and applyuserno='" . $userno . "'", $con) or die ("Query error: "
            . mysql_error());
    }
}

// 最後にstatusを3に変更する
$sql = "UPDATE helplist SET status='3' WHERE id = '" . $workid . "'";
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

//$activitylog = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('".$userno."', 'registerEval.php', '".date('Y-m-d G:i:s')."')", $con) or die('Error: ' . mysql_error());

mysql_close($con);

?>