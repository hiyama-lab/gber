<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/auth.php';

$post = json_decode(file_get_contents('php://input'), true);

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['USER'], ['userno' => $post['userno']])){
    http_response_code(403);
    exit;
}

$db = DB::getInstance();
if ($post['upload_num'] == 0) {
    $db->updateSchedule($post['userno'], $post['inputid'], 0, 0, $post['year'], $post['month'], $post['lastupdated']);
} else {
    if ($post['upload_num'] == 1) {
        $db->updateSchedule($post['userno'], $post['inputid'], 1, 1, $post['year'], $post['month'], $post['lastupdated']);
    } else {
        if ($post['upload_num'] == 2) {
            $db->updateSchedule($post['userno'], $post['inputid'], 1, 0, $post['year'], $post['month'], $post['lastupdated']);
        } else {
            if ($post['upload_num'] == 3) {
                $db->updateSchedule($post['userno'], $post['inputid'], 0, 1, $post['year'], $post['month'], $post['lastupdated']);            }
        }
    }
}

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

$db->addToActivityLog($userno, 'editSchedule.php');
?>