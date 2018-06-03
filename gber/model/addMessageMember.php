<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

$inputdata = json_decode(file_get_contents('php://input'), true);
$userno = $inputdata['userno'];
$messageid = $inputdata['messageid'];

$alreadyregistered = false;
$result
    = mysql_query("SELECT messagememberid FROM messagemember WHERE messageid='"
    . $messageid . "' and memberid='" . $userno . "'", $con) or die ("Query error: "
    . mysql_error());
if (mysql_num_rows($result) != 0) {
    $alreadyregistered = true;
}

if (!$alreadyregistered) {
    if (!mysql_query("INSERT INTO messagemember (messageid, memberid) VALUES ('$messageid', '$userno')",
        $con)
    ) {
        die('Error: ' . mysql_error());
    } else {
        echo $_GET['jsoncallback'] . '({"status":"succeed"});';
    }
    $result2
        = mysql_query("SELECT nameedited,messagename FROM message WHERE messageid='"
        . $messageid . "'", $con) or die ("Query error: " . mysql_error());
    $row2 = mysql_fetch_assoc($result2);
    $nameedited = $row2['nameedited'];
    $currentname = $row2['messagename'];
    if ($nameedited == 0) {
        $result3 = mysql_query("SELECT nickname FROM db_user WHERE userno='"
            . $userno . "'", $con) or die ("Query error: " . mysql_error());
        $nickname = mysql_fetch_assoc($result3)['nickname'];
        $newtitle = $currentname . "," . $nickname;
        $result4 = mysql_query("UPDATE message SET messagename='" . $newtitle
            . "' WHERE messageid='" . $messageid . "'", $con) or die ("Query error: "
            . mysql_error());
    }
} else {
    die('既に登録されています');
}

mysql_close($con);

?>