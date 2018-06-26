<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/updateMatchingParam_human.php';
require_once __DIR__ . '/../lib/auth.php';

$post = json_decode(file_get_contents('php://input'), true);
extract($post);

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['MASTER_OR_ADMIN'], ['groupno' => $groupno])){
    http_response_code(403);
    exit;
}

$alreadyregisteredresult
    = mysql_query("SELECT * FROM matchingparam_work WHERE groupno='"
    . $groupno . "' and workid='" . $workid . "'") or die ("Query error: "
    . mysql_error());
$alreadyregistered = array();
while ($row = mysql_fetch_assoc($alreadyregisteredresult)) {
    $alreadyregistered[] = $row;
}

// 直接matchingparam_workに挿入する
// 既に登録されていれば更新。登録されていなければ挿入
if(count($alreadyregistered)){
    mysql_query("UPDATE matchingparam_work SET groupno='$groupno', worktype_prune='$worktype_prone', worktype_agriculture='$worktype_agriculture', worktype_cleaning='$worktype_cleaning', worktype_housework='$worktype_housework', worktype_shopping='$worktype_shopping', worktype_repair='$worktype_repair', worktype_caretaking='$worktype_caretaking', worktype_teaching='$worktype_teaching', worktype_consulting='$worktype_consulting', study_english='$study_english', study_foreignlanguage='$study_foreignlanguage', study_it='$study_it', study_business='$study_business', study_caretaking='$study_caretaking', study_housework='$study_housework', study_liberalarts='$study_liberalarts', study_art='$study_art', volunteer_health='$volunteer_health', volunteer_elderly='$volunteer_elderly', volunteer_disable='$volunteer_disable', volunteer_children='$volunteer_children', volunteer_sport='$volunteer_sport', volunteer_town='$volunteer_town', volunteer_safety='$volunteer_safety', volunteer_nature='$volunteer_nature', volunteer_disaster='$volunteer_disaster', volunteer_international='$volunteer_international', hobby_musicalinstrument='$hobby_musicalinstrument', hobby_chorus='$hobby_chorus', hobby_dance='$hobby_dance', hobby_shodo='$hobby_shodo', hobby_kado='$hobby_kado', hobby_sado='$hobby_sado', hobby_wasai='$hobby_wasai', hobby_knit='$hobby_knit', hobby_cooking='$hobby_cooking', hobby_gardening='$hobby_gardening', hobby_diy='$hobby_diy', hobby_painting='$hobby_painting', hobby_pottery='$hobby_portery', hobby_photo='$hobby_photo', hobby_writing='$hobby_writing', hobby_go='$hobby_go', hobby_camp='$hobby_camp', hobby_watchsport='$hobby_watchsport', hobby_watchperformance='$hobby_watchperformance', hobby_watchmovie='$hobby_watchmovie', hobby_listenmusic='$hobby_listenmusic', hobby_reading='$hobby_reading', hobby_pachinko='$hobby_pachinko', hobby_karaoke='$hobby_karaoke', hobby_game='$hobby_game', hobby_attraction='$hobby_attraction', hobby_train='$hobby_train', hobby_car='$hobby_car', trip_daytrip='$trip_daytrip', trip_domestic='$trip_domestic', trip_international='$trip_international', sport_baseball='$sport_baseball', sport_tabletennis='$sport_tabletennis', sport_tennis='$sport_tennis', sport_badminton='$sport_badminton', sport_golf='$sport_golf', sport_gateball='$sport_gateball', sport_bowling='$sport_bowling', sport_fishing='$sport_fishing', sport_swimming='$sport_swimming', sport_skiing='$sport_skiing', sport_climbing='$sport_climbing', sport_cycling='$sport_cycling', sport_jogging='$sport_jogging', sport_walking='$sport_walking' WHERE workid = $workid")
    or die ("Query error: " . mysql_error());

}else{
    mysql_query("INSERT INTO matchingparam_work (groupno, workid, worktype_prune, worktype_agriculture, worktype_cleaning, worktype_housework, worktype_shopping, worktype_repair, worktype_caretaking, worktype_teaching, worktype_consulting, study_english, study_foreignlanguage, study_it, study_business, study_caretaking, study_housework, study_liberalarts, study_art, volunteer_health, volunteer_elderly, volunteer_disable, volunteer_children, volunteer_sport, volunteer_town, volunteer_safety, volunteer_nature, volunteer_disaster, volunteer_international, hobby_musicalinstrument, hobby_chorus, hobby_dance, hobby_shodo, hobby_kado, hobby_sado, hobby_wasai, hobby_knit, hobby_cooking, hobby_gardening, hobby_diy, hobby_painting, hobby_pottery, hobby_photo, hobby_writing, hobby_go, hobby_camp, hobby_watchsport, hobby_watchperformance, hobby_watchmovie, hobby_listenmusic, hobby_reading, hobby_pachinko, hobby_karaoke, hobby_game, hobby_attraction, hobby_train, hobby_car, trip_daytrip, trip_domestic, trip_international, sport_baseball, sport_tabletennis, sport_tennis, sport_badminton, sport_golf, sport_gateball, sport_bowling, sport_fishing, sport_swimming, sport_skiing, sport_climbing, sport_cycling, sport_jogging, sport_walking) VALUES ('$groupno', '$workid', '$worktype_prune', '$worktype_agriculture', '$worktype_cleaning', '$worktype_housework', '$worktype_shopping', '$worktype_repair', '$worktype_caretaking', '$worktype_teaching', '$worktype_consulting', '$study_english', '$study_foreignlanguage', '$study_it', '$study_business', '$study_caretaking', '$study_housework', '$study_liberalarts', '$study_art', '$volunteer_health', '$volunteer_elderly', '$volunteer_disable', '$volunteer_children', '$volunteer_sport', '$volunteer_town', '$volunteer_safety', '$volunteer_nature', '$volunteer_disaster', '$volunteer_international', '$hobby_musicalinstrument', '$hobby_chorus', '$hobby_dance', '$hobby_shodo', '$hobby_kado', '$hobby_sado', '$hobby_wasai', '$hobby_knit', '$hobby_cooking', '$hobby_gardening', '$hobby_diy', '$hobby_painting', '$hobby_pottery', '$hobby_photo', '$hobby_writing', '$hobby_go', '$hobby_camp', '$hobby_watchsport', '$hobby_watchperformance', '$hobby_watchmovie', '$hobby_listenmusic', '$hobby_reading', '$hobby_pachinko', '$hobby_karaoke', '$hobby_game', '$hobby_attraction', '$hobby_train', '$hobby_car', '$trip_daytrip', '$trip_domestic', '$trip_international', '$sport_baseball', '$sport_tabletennis', '$sport_tennis', '$sport_badminton', '$sport_golf', '$sport_gateball', '$sport_bowling', '$sport_fishing', '$sport_swimming', '$sport_skiing', '$sport_climbing', '$sport_cycling', '$sport_jogging', '$sport_walking')")
    or die ("Query error: " . mysql_error());
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $post['userno'] . "', 'answerWorktag.php?groupno=" . $groupno . "&workid="
    . $workid . "', '" . date('Y-m-d G:i:s') . "')", $con) or die('Error: '
    . mysql_error());

echo $_GET['jsoncallback'] . '({"status":"succeed"});';

mysql_close($con);

?>