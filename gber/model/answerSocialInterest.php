<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/db.php';
include __DIR__ . '/../lib/sendEmail.php';
include __DIR__ . '/updateMatchingParam_human.php';
require_once __DIR__ . '/../lib/auth.php';

$post = json_decode(file_get_contents('php://input'), true);
$userno = $post['userno'];

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['USER'], ['userno' => $userno])){
    http_response_code(403);
    exit;
}

//個人パラメータを変更
$workparamresult = mysql_query("UPDATE questionnaire_socialactivity SET "
    . $post['answeredrow'] . "=" . $post['interest']
    . ", answered=answered+1 WHERE userno='" . $userno . "'", $con)
or die ('Error: ' . mysql_error());

$db = DB::getInstance();
if($db->getSocialQuestionnaireCount($userno) == 72){
    $userp = $db->getQuestionnaireSocial($userno);
    unset($userp['socialactivityid']);
    unset($userp['userno']);
    unset($userp['answered']);
    $db->updateMatchingParamHuman($userno, $userp);
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $post['userno'] . "', 'answerSocialInterest.php', '" . date('Y-m-d G:i:s')
    . "')", $con) or die('Error: ' . mysql_error());

mysql_close($con);

echo $_GET['jsoncallback'] . '({"status":"succeed"});';
?>