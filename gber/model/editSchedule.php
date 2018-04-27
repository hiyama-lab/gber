<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';

$post = json_decode(file_get_contents('php://input'), true);

if ($post['upload_num'] == 0) {
    $sql = "UPDATE schedule SET " . $post['inputid'] . "_am =0, " . $post['inputid']
        . "_pm =0, lastupdated='" . $post['lastupdated'] . "' WHERE userno="
        . $post['userno'] . " AND year=" . $post['year'] . " AND month="
        . $post['month'];
} else {
    if ($post['upload_num'] == 1) {
        $sql = "UPDATE schedule SET " . $post['inputid'] . "_am =1, "
            . $post['inputid'] . "_pm =1, lastupdated='" . $post['lastupdated']
            . "' WHERE userno=" . $post['userno'] . " AND year=" . $post['year']
            . " AND month=" . $post['month'];
    } else {
        if ($post['upload_num'] == 2) {
            $sql = "UPDATE schedule SET " . $post['inputid'] . "_am =1, "
                . $post['inputid'] . "_pm =0, lastupdated='" . $post['lastupdated']
                . "' WHERE userno=" . $post['userno'] . " AND year=" . $post['year']
                . " AND month=" . $post['month'];
        } else {
            if ($post['upload_num'] == 3) {
                $sql = "UPDATE schedule SET " . $post['inputid'] . "_am =0, "
                    . $post['inputid'] . "_pm =1, lastupdated='"
                    . $post['lastupdated'] . "' WHERE userno=" . $post['userno']
                    . " AND year=" . $post['year'] . " AND month=" . $post['month'];
            }
        }
    }
}

if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $post['userno'] . "', 'editSchedule.php', '" . date('Y-m-d G:i:s') . "')", $con)
or die('Error: ' . mysql_error());

mysql_close($con);

?>