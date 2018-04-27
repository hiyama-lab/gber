<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';

$inputdata = json_decode(file_get_contents('php://input'), true);
$userno = $inputdata['userno'];
$groupno = $inputdata['groupno'];

$sql2 = "DELETE FROM grouplist WHERE userno = '" . $userno . "' and groupno = '"
    . $groupno . "'";
if (!mysql_query($sql2, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>