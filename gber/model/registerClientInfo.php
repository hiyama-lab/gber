<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';

// 仕事ID
$input = json_decode(file_get_contents('php://input'), true);

$clientid = $input['clientid'];
$workid = $input['workid'];
$comment = $input['comment'];


// コメントが存在すればUpdate、なければInsert
$result = mysql_query("SELECT infoid FROM clientinfo WHERE workid='" . $workid . "'", $con) or die ("Query error: "
    . mysql_error());
if (mysql_num_rows($result) == 0) {
    mysql_query("INSERT INTO clientinfo (clientid, workid, comment) VALUES ('$clientid','$workid','$comment')",
        $con) or die ("Query error: " . mysql_error());
} else {
    mysql_query("UPDATE clientinfo SET comment='" . $comment
        . "' WHERE workid='" . $workid . "'", $con) or die ("Query error: "
        . mysql_error());
}

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

mysql_close($con);

?>