<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/db.php';

$post = json_decode(file_get_contents('php://input'), true);
$worktypeid = $post['id'];

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['MASTER_OR_ADMIN'], ['groupno' => $worktype['groupno']])){
    http_response_code(403);
    exit;
}

$db = DB::getInstance();
$db->deleteWorktype($worktypeid);

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

$db->addToActivityLog($_SESSION['userno'], "deleteWorktype.php");
?>

