<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';

date_default_timezone_set('Asia/Tokyo');

//parse_str($_POST['postdata'],$_POST);
//array_walk_recursive($_POST, create_function('&$v', '$v = stripslashes($v);'));

$worktitle = mysql_real_escape_string($_POST["worktitle"]);
$content = mysql_real_escape_string($_POST["content"]);
$workgenre = mysql_real_escape_string($_POST["workgenre"]);
$groupgenre = mysql_real_escape_string($_POST["groupgenre"]);
$address = mysql_real_escape_string($_POST["address"]);
$lat = mysql_real_escape_string($_POST["lat"]);
$lng = mysql_real_escape_string($_POST["lng"]);
$userno = mysql_real_escape_string($_POST["userno"]);
$workernum = mysql_real_escape_string($_POST["workernum"]);
$price = mysql_real_escape_string($_POST["price"]);
$contact = mysql_real_escape_string($_POST["contact"]);

$workdatelist = $_POST["workdatelist"];

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['USER'], ['userno' => $userno])){
    http_response_code(403);
    exit;
}

//ヘルプリストに挿入
mysql_query("INSERT INTO helplist (userno, lat, lng, address, worktitle, content, price, workernum,  contact, workgenre, groupgenre) VALUES ('$userno', '$lat', '$lng', '$address', '$worktitle', '$content', '$price', '$workernum', '$contact', '$workgenre', '$groupgenre')",
    $con) or die('Error: ' . mysql_error());

$workidresource = mysql_query("SELECT LAST_INSERT_ID()", $con)
or die ("Query error: " . mysql_error());
$workid = mysql_fetch_row($workidresource)[0];

//仕事日を挿入
foreach ($workdatelist as $eachworkdate) {
    mysql_query("INSERT INTO helpdate (workid, workdate) VALUES ('$workid', '$eachworkdate')",
        $con) or die('Error: ' . mysql_error());
}

//掲示板に挿入
$datetime = date('Y-m-d G:i:s');
$message = "<a href=\"jobdetail.php?workid=" . $workid . "\" rel=\"external\">"
    . $worktitle . "</a>";
mysql_query("INSERT INTO bbs_group (groupno, senderid, message, datetime, jobpost) VALUES ('0', '$userno', '$message', '$datetime', '1')",
    $con) or die('Error: ' . mysql_error());

//メッセージボードを作成
$result3
    = mysql_query("INSERT INTO message (messagename, workid, nameedited, lastupdate) VALUES ('$worktitle','$workid','1','$datetime')",
    $con) or die ("Query error: " . mysql_error());

//興味ありに追加
$result4
    = mysql_query("INSERT INTO helpmatching (workid, applyuserno, interest) VALUES ('$workid','$userno','1')",
    $con) or die ("Query error: " . mysql_error());

//メッセージメンバーに追加
$result5 = mysql_query("SELECT messageid FROM message WHERE workid='" . $workid
    . "' and lastupdate='" . $datetime . "'", $con) or die ("Query error: "
    . mysql_error());
$messageid = mysql_fetch_assoc($result5)['messageid'];
$result6
    = mysql_query("INSERT INTO messagemember (messageid, memberid) VALUES ('$messageid','$userno')",
    $con) or die ("Query error: " . mysql_error());

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $userno . "', 'uploadJob.php', '" . date('Y-m-d G:i:s') . "')", $con)
or die('Error: ' . mysql_error());

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

mysql_close($con);

?>