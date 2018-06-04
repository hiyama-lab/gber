<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';

$post = json_decode(file_get_contents('php://input'), true);
$userno = $post['userno'];
$gakureki = $post['gakureki'];
$gyoushu = $post['gyoushu'];
$gyoushudetail = $post['gyoushudetail'];
$shokushu = $post['shokushu'];
$shokushudetail = $post['shokushudetail'];
$doukyo = $post['doukyo'];
$undou_light = $post['undou_light'];
$undou_medium = $post['undou_medium'];
$undou_heavy = $post['undou_heavy'];
$shikaku = $post['shikaku'];

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['USER'], ['userno' => $userno])){
    http_response_code(403);
    exit;
}

// 登録済ならアップデート
$result
    = mysql_query("SELECT answerid FROM questionnaire_demographic WHERE userno='"
    . $userno . "'", $con) or die ("Query error: " . mysql_error());

if (mysql_num_rows($result) > 0) {
    $sql = "UPDATE questionnaire_demographic SET gakureki='" . $gakureki
        . "', gyoushu='" . $gyoushu . "', gyoushudetail='" . $gyoushudetail
        . "', shokushu='" . $shokushu . "', shokushudetail='" . $shokushudetail
        . "', doukyo='" . $doukyo . "', undou_light='" . $undou_light
        . "', undou_medium='" . $undou_medium . "', undou_heavy='" . $undou_heavy
        . "', shikaku='" . $shikaku . "' WHERE userno = '" . $userno . "'";
} else {
    $sql
        = "INSERT INTO questionnaire_demographic (userno, gakureki, gyoushu, gyoushudetail, shokushu, shokushudetail, doukyo, undou_light, undou_medium, undou_heavy, shikaku) VALUES ('$userno', '$gakureki', '$gyoushu', '$gyoushudetail', '$shokushu', '$shokushudetail', '$doukyo', '$undou_light', '$undou_medium', '$undou_heavy', '$shikaku')";
}

if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>