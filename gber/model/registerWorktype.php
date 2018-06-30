<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/db.php';

$worktype = json_decode(file_get_contents('php://input'), true);

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['MASTER_OR_ADMIN'], ['groupno' => $worktype['groupno']])){
    http_response_code(403);
    exit;
}

$db = DB::getInstance();
if($worktype['id'] == ""){
    unset($worktype['id']);
    $db->insertWorktype($worktype);
}else {
    $old = $db->getWorktypeById($worktype['id']);
    if(!$old){
        http_response_code(400);
        exit;
    }
    $db->updateWorktype($worktype);
}

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

$db->addToActivityLog($_SESSION['userno'], "registerWorktype.php");
?>

