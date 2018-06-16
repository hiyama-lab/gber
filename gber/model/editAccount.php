<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/auth.php';

$userno = h($_POST["userno"]);
$mail = h($_POST["mail"]);
$nickname = h($_POST["nickname"]);
$pass = h($_POST["pass"]);
$token = h($_POST["token"]);
if (validate_token($token)) exit;

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['USER'], ['userno' => $userno])){
    http_response_code(403);
    exit;
}

$db = DB::getInstance();
if ($pass == "") {
    $db->updateAccountWithoutPass($userno, $mail, $nickname);
} else {
    $options = array('cost' => 10);
    $hash = password_hash($pass, PASSWORD_DEFAULT, $options);
    $db->updateAccountWithPass($userno, $mail, $nickname, $hash);
}

echo $_GET['jsoncallback'] . '({"status":"succeed"});';
$db->addToActivityLog($userno, 'editAccount.php');

?>