<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';

$inputdata = json_decode(file_get_contents('php://input'), true);
$userno = $inputdata['userno'];
$groupno = $inputdata['groupno'];

$alreadyregistered = 0;

$sql = "SELECT groupno FROM grouplist WHERE userno ='" . $userno . "'";
$result = mysql_query($sql, $con);
while ($row = mysql_fetch_assoc($result)) {
    if ($row['groupno'] == $groupno) {
        $alreadyregistered = 1;
    }
}

if ($alreadyregistered == 0) {
    if (!mysql_query("INSERT INTO grouplist (groupno, userno) VALUES ('$groupno', '$userno')",
        $con)
    ) {
        die('Error: ' . mysql_error());
    } else {
        echo $_GET['jsoncallback'] . '({"status":"succeed"});';
    }
} else {
    die('既に登録されています');
}

mysql_close($con);

?>