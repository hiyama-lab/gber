<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';
include __DIR__ . '/updateMatchingParam_human.php';

$post = json_decode(file_get_contents('php://input'), true);

//個人パラメータを変更
$workparamresult = mysql_query("UPDATE questionnaire_socialactivity SET "
    . $post['answeredrow'] . "=" . $post['interest']
    . ", answered=answered+1 WHERE userno='" . $post['userno'] . "'", $con)
or die ('Error: ' . mysql_error());

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $post['userno'] . "', 'answerSocialInterest.php', '" . date('Y-m-d G:i:s')
    . "')", $con) or die('Error: ' . mysql_error());

mysql_close($con);

echo $_GET['jsoncallback'] . '({"status":"succeed"});';
?>