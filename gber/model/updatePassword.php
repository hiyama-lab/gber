<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');
ini_set('display_errors', 0);

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['GLOBAL_MASTER'], [])){
    http_response_code(403);
    exit;
}

$mail = mysql_real_escape_string($_POST["mail"]);
$pass = mysql_real_escape_string($_POST["pass"]);

$options = array('cost' => 10);
$hash = password_hash($pass, PASSWORD_DEFAULT, $options);

mysql_query("UPDATE db_user SET pass='" . $hash . "' WHERE mail='" . $mail . "'", $con)
or die('Error: ' . mysql_error());

mysql_close($con);

?>