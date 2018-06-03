<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['GLOBAL_MASTER'], [])){
    http_response_code(403);
    exit;
}

$inputdata = json_decode(file_get_contents('php://input'), true);

$groupno = $inputdata['groupno'];
$groupname = $inputdata['groupname'];

//グループネームリストに追加
$result4
    = mysql_query("INSERT INTO groupnamelist (groupno, groupname) VALUES ('$groupno', '$groupname')",
    $con) or die ("Query error: " . mysql_error());

//マスター権限を持つ者を管理者として追加
$result9 = mysql_query("SELECT userno FROM db_user WHERE master='1'", $con)
or die ("Query error: " . mysql_error());
$masters = array();
while ($row = mysql_fetch_assoc($result9)) {
    $masters[] = $row['userno'];
}
foreach ($masters as $eachmaster) {
    mysql_query("INSERT INTO grouplist (groupno, userno, admin) VALUES ('$groupno', '$eachmaster', '1')",
        $con) or die ("Query error: " . mysql_error());
}

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

mysql_close($con);

?>