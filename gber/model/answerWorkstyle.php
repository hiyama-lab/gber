<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

$post = json_decode(file_get_contents('php://input'), true);
$userno = $post['userno'];
$workdayperweek = $post['workdayperweek'];
$worktimeperday = $post['worktimeperday'];
$commutetime = $post['commutetime'];
$transit_car = $post['transit_car'];
$transit_train = $post['transit_train'];
$transit_bus = $post['transit_bus'];
$transit_bicycle = $post['transit_bicycle'];
$transit_onfoot = $post['transit_onfoot'];
$transit_other = $post['transit_other'];
$workobject_money_1 = $post['workobject_money_1'];
$workobject_money_2 = $post['workobject_money_2'];
$workobject_purposeoflife = $post['workobject_purposeoflife'];
$workobject_health = $post['workobject_health'];
$workobject_contribution = $post['workobject_contribution'];
$workobject_asked = $post['workobject_asked'];
$workobject_sparetime = $post['workobject_sparetime'];
$workobject_skill = $post['workobject_skill'];
$workobject_other = $post['workobject_other'];

// 登録済ならアップデート
$result = mysql_query("SELECT * FROM questionnaire_workstyle WHERE userno='"
    . $userno . "'", $con) or die ("Query error: " . mysql_error());

if (mysql_num_rows($result) > 0) {
    //挿入用
    $sql = "UPDATE questionnaire_workstyle SET workdayperweek = '"
        . $workdayperweek . "', worktimeperday = '" . $worktimeperday
        . "', commutetime = '" . $commutetime . "', transit_car = '" . $transit_car
        . "', transit_train = '" . $transit_train . "', transit_bus = '" . $transit_bus
        . "', transit_bicycle = '" . $transit_bicycle . "', transit_onfoot = '"
        . $transit_onfoot . "', transit_other = '" . $transit_other
        . "', workobject_money_1 = '" . $workobject_money_1
        . "', workobject_money_2 = '" . $workobject_money_2
        . "', workobject_purposeoflife = '" . $workobject_purposeoflife
        . "', workobject_health = '" . $workobject_health
        . "', workobject_contribution = '" . $workobject_contribution
        . "', workobject_asked = '" . $workobject_asked
        . "', workobject_sparetime = '" . $workobject_sparetime
        . "', workobject_skill = '" . $workobject_skill . "', workobject_other = '"
        . $workobject_other . "' WHERE userno = '" . $userno . "'";
} else {
    //挿入用
    $sql
        = "INSERT INTO questionnaire_workstyle (userno, workdayperweek, worktimeperday, commutetime, transit_car, transit_train, transit_bus, transit_bicycle, transit_onfoot, transit_other, workobject_money_1, workobject_money_2, workobject_purposeoflife, workobject_health, workobject_contribution, workobject_asked, workobject_sparetime, workobject_skill, workobject_other) VALUES ('$userno', '$workdayperweek', '$worktimeperday', '$commutetime', '$transit_car', '$transit_train', '$transit_bus', '$transit_bicycle', '$transit_onfoot', '$transit_other', '$workobject_money_1', '$workobject_money_2', '$workobject_purposeoflife', '$workobject_health', '$workobject_contribution', '$workobject_asked', '$workobject_sparetime', '$workobject_skill', '$workobject_other')";
}

if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>