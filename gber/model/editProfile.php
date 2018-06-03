<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

$userno = mysql_real_escape_string($_POST["userno"]);
$mail = mysql_real_escape_string($_POST["mail"]);
$phone = mysql_real_escape_string($_POST["phone"]);
$nickname = mysql_real_escape_string($_POST["nickname"]);
$birthyear = mysql_real_escape_string($_POST["birthyear"]);
$gender = mysql_real_escape_string($_POST["gender"]);
$intro = mysql_real_escape_string($_POST["intro"]);
$address = mysql_real_escape_string($_POST["address"]);
$lat = mysql_real_escape_string($_POST["lat"]);
$lng = mysql_real_escape_string($_POST["lng"]);

$sql = "UPDATE db_user SET mail='" . $mail . "', phone='" . $phone . "', nickname='"
    . $nickname . "', birthyear='" . $birthyear . "', gender='" . $gender . "', intro='"
    . $intro . "', address_string='" . $address . "', mylat='" . $lat . "', mylng='" . $lng
    . "' WHERE userno = '" . $userno . "'";
if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $userno . "', 'editProfile.php', '" . date('Y-m-d G:i:s') . "')", $con)
or die('Error: ' . mysql_error());

mysql_close($con);

?>