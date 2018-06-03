<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/updateMatchingParam_human.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['GLOBAL_MASTER'], [])){
    http_response_code(403);
    exit;
}

$post = json_decode(file_get_contents('php://input'), true);
extract($post);

//既に登録されているものを取得する。
$alreadyregisteredresult
    = mysql_query("SELECT * FROM matchingparam_worktemp WHERE groupno='"
    . $groupno . "' and workid='" . $workid . "'") or die ("Query error: "
    . mysql_error());
$alreadyregistered = array();
while ($row = mysql_fetch_assoc($alreadyregisteredresult)) {
    $alreadyregistered[] = $row;
}

foreach ($alreadyregistered as $eachresult) {
    if ($userno == $eachresult['userno']) {
        die("1人が2つ以上評価を登録しています");
    }
}

//データを登録
mysql_query("INSERT INTO matchingparam_worktemp (groupno, workid, userno, timestamp, worktype_prune, worktype_agriculture, worktype_cleaning, worktype_housework, worktype_shopping, worktype_repair, worktype_caretaking, worktype_teaching, worktype_consulting, study_english, study_foreignlanguage, study_it, study_business, study_caretaking, study_housework, study_liberalarts, study_art, volunteer_health, volunteer_elderly, volunteer_disable, volunteer_children, volunteer_sport, volunteer_town, volunteer_safety, volunteer_nature, volunteer_disaster, volunteer_international, hobby_musicalinstrument, hobby_chorus, hobby_dance, hobby_shodo, hobby_kado, hobby_sado, hobby_wasai, hobby_knit, hobby_cooking, hobby_gardening, hobby_diy, hobby_painting, hobby_pottery, hobby_photo, hobby_writing, hobby_go, hobby_camp, hobby_watchsport, hobby_watchperformance, hobby_watchmovie, hobby_listenmusic, hobby_reading, hobby_pachinko, hobby_karaoke, hobby_game, hobby_attraction, hobby_train, hobby_car, trip_daytrip, trip_domestic, trip_international, sport_baseball, sport_tabletennis, sport_tennis, sport_badminton, sport_golf, sport_gateball, sport_bowling, sport_fishing, sport_swimming, sport_skiing, sport_climbing, sport_cycling, sport_jogging, sport_walking) VALUES ('$groupno', '$workid', '$userno', '"
    . date('Y-m-d G:i:s')
    . "', '$worktype_prune', '$worktype_agriculture', '$worktype_cleaning', '$worktype_housework', '$worktype_shopping', '$worktype_repair', '$worktype_caretaking', '$worktype_teaching', '$worktype_consulting', '$study_english', '$study_foreignlanguage', '$study_it', '$study_business', '$study_caretaking', '$study_housework', '$study_liberalarts', '$study_art', '$volunteer_health', '$volunteer_elderly', '$volunteer_disable', '$volunteer_children', '$volunteer_sport', '$volunteer_town', '$volunteer_safety', '$volunteer_nature', '$volunteer_disaster', '$volunteer_international', '$hobby_musicalinstrument', '$hobby_chorus', '$hobby_dance', '$hobby_shodo', '$hobby_kado', '$hobby_sado', '$hobby_wasai', '$hobby_knit', '$hobby_cooking', '$hobby_gardening', '$hobby_diy', '$hobby_painting', '$hobby_pottery', '$hobby_photo', '$hobby_writing', '$hobby_go', '$hobby_camp', '$hobby_watchsport', '$hobby_watchperformance', '$hobby_watchmovie', '$hobby_listenmusic', '$hobby_reading', '$hobby_pachinko', '$hobby_karaoke', '$hobby_game', '$hobby_attraction', '$hobby_train', '$hobby_car', '$trip_daytrip', '$trip_domestic', '$trip_international', '$sport_baseball', '$sport_tabletennis', '$sport_tennis', '$sport_badminton', '$sport_golf', '$sport_gateball', '$sport_bowling', '$sport_fishing', '$sport_swimming', '$sport_skiing', '$sport_climbing', '$sport_cycling', '$sport_jogging', '$sport_walking')")
or die ("Query error: " . mysql_error());


$numregistered = mysql_num_rows($alreadyregisteredresult);

//登録が2件既にあれば、workの方に挿入する。
if ($numregistered == 2) { //startof num2

    //上のからusernoを除いたもの
    mysql_query("INSERT INTO matchingparam_work (groupno, workid, worktype_prune, worktype_agriculture, worktype_cleaning, worktype_housework, worktype_shopping, worktype_repair, worktype_caretaking, worktype_teaching, worktype_consulting, study_english, study_foreignlanguage, study_it, study_business, study_caretaking, study_housework, study_liberalarts, study_art, volunteer_health, volunteer_elderly, volunteer_disable, volunteer_children, volunteer_sport, volunteer_town, volunteer_safety, volunteer_nature, volunteer_disaster, volunteer_international, hobby_musicalinstrument, hobby_chorus, hobby_dance, hobby_shodo, hobby_kado, hobby_sado, hobby_wasai, hobby_knit, hobby_cooking, hobby_gardening, hobby_diy, hobby_painting, hobby_pottery, hobby_photo, hobby_writing, hobby_go, hobby_camp, hobby_watchsport, hobby_watchperformance, hobby_watchmovie, hobby_listenmusic, hobby_reading, hobby_pachinko, hobby_karaoke, hobby_game, hobby_attraction, hobby_train, hobby_car, trip_daytrip, trip_domestic, trip_international, sport_baseball, sport_tabletennis, sport_tennis, sport_badminton, sport_golf, sport_gateball, sport_bowling, sport_fishing, sport_swimming, sport_skiing, sport_climbing, sport_cycling, sport_jogging, sport_walking) VALUES ('$groupno', '$workid', '$worktype_prune', '$worktype_agriculture', '$worktype_cleaning', '$worktype_housework', '$worktype_shopping', '$worktype_repair', '$worktype_caretaking', '$worktype_teaching', '$worktype_consulting', '$study_english', '$study_foreignlanguage', '$study_it', '$study_business', '$study_caretaking', '$study_housework', '$study_liberalarts', '$study_art', '$volunteer_health', '$volunteer_elderly', '$volunteer_disable', '$volunteer_children', '$volunteer_sport', '$volunteer_town', '$volunteer_safety', '$volunteer_nature', '$volunteer_disaster', '$volunteer_international', '$hobby_musicalinstrument', '$hobby_chorus', '$hobby_dance', '$hobby_shodo', '$hobby_kado', '$hobby_sado', '$hobby_wasai', '$hobby_knit', '$hobby_cooking', '$hobby_gardening', '$hobby_diy', '$hobby_painting', '$hobby_pottery', '$hobby_photo', '$hobby_writing', '$hobby_go', '$hobby_camp', '$hobby_watchsport', '$hobby_watchperformance', '$hobby_watchmovie', '$hobby_listenmusic', '$hobby_reading', '$hobby_pachinko', '$hobby_karaoke', '$hobby_game', '$hobby_attraction', '$hobby_train', '$hobby_car', '$trip_daytrip', '$trip_domestic', '$trip_international', '$sport_baseball', '$sport_tabletennis', '$sport_tennis', '$sport_badminton', '$sport_golf', '$sport_gateball', '$sport_bowling', '$sport_fishing', '$sport_swimming', '$sport_skiing', '$sport_climbing', '$sport_cycling', '$sport_jogging', '$sport_walking')")
    or die ("Query error: " . mysql_error());

    //先に取得した2件を足す
    foreach ($alreadyregistered as $eachresult) {
        updateMatchingParam_work($eachresult, $groupno, $workid, 1);
    }

    //すでに興味ありor承諾済みの人がいた場合、マッチングパラメータの今回の分を足す
    $interestedpeople = array();
    $notinterestedpeople = array();

    if ($groupno == 0) {
        $interestedpeopleresult
            = mysql_query("SELECT DISTINCT applyuserno FROM helpmatching WHERE workid = '"
            . $workid . "' and interest = 1", $con) or die ("Query error: "
            . mysql_error());
        while ($row = mysql_fetch_assoc($interestedpeopleresult)) {
            $interestedpeople[] = $row['applyuserno'];
        }
        $notinterestedpeopleresult
            = mysql_query("SELECT DISTINCT applyuserno FROM helpmatching WHERE workid = '"
            . $workid . "' and interest = 0", $con) or die ("Query error: "
            . mysql_error());
        while ($row = mysql_fetch_assoc($notinterestedpeopleresult)) {
            $notinterestedpeople[] = $row['applyuserno'];
        }
    } else {
        //興味有無
        $interestedpeopleresult
            = mysql_query("SELECT DISTINCT userno FROM workinterest WHERE workid = '" . $workid . "' and interest = 1", $con)
        or die ("Query error: " . mysql_error());
        while ($row = mysql_fetch_assoc($interestedpeopleresult)) {
            $interestedpeople[] = $row['userno'];
        }
        $notinterestedpeopleresult
            = mysql_query("SELECT DISTINCT userno FROM workinterest WHERE workid = '" . $workid . "' and interest = 0", $con)
        or die ("Query error: " . mysql_error());
        while ($row = mysql_fetch_assoc($notinterestedpeopleresult)) {
            $notinterestedpeople[] = $row['userno'];
        }
        //承諾orキャンセル。複数回可能なのでdistinctなし
        $interestedpeopleresult = mysql_query("SELECT workerno FROM workdate WHERE workid = '" . $workid . "' and status = 1", $con)
        or die ("Query error: " . mysql_error());
        while ($row = mysql_fetch_assoc($interestedpeopleresult)) {
            $interestedpeople[] = $row['workerno'];
        }
        $notinterestedpeopleresult
            = mysql_query("SELECT workerno FROM workcancel WHERE workid = '" . $workid . "'", $con) or die ("Query error: "
            . mysql_error());
        while ($row = mysql_fetch_assoc($notinterestedpeopleresult)) {
            $notinterestedpeople[] = $row['workerno'];
        }
    }

    $workparamresult
        = mysql_query("SELECT * FROM matchingparam_work WHERE groupno='"
        . $groupno . "' and workid='" . $workid . "'", $con) or die ('Error: '
        . mysql_error());
    $workparam = mysql_fetch_assoc($workparamresult);

    foreach ($interestedpeople as $eachuser) {
        updateMatchingParam_human($workparam, $eachuser, 0.1, true);
    }
    foreach ($notinterestedpeople as $eachuser) {
        updateMatchingParam_human($workparam, $eachuser, 0.1, false);
    }
}
// endof num2

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $post['userno'] . "', 'answerWorktag.php?groupno=" . $groupno . "&workid="
    . $workid . "', '" . date('Y-m-d G:i:s') . "')", $con) or die('Error: '
    . mysql_error());

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

mysql_close($con);

?>