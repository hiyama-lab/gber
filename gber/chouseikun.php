<?php
require_once __DIR__ . '/lib/auth.php';
require_logined_session();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css"/>
    <script type="text/javascript" src="js/calendar/jquery-ui.min.js"></script>
    <script type="text/javascript"
            src="js/calendar/jquery-ui-i18n.min.js"></script>
    <!-- インライン編集用 -->
    <script type="text/javascript"
            src="js/editable/jquery.editable.min.js"></script>
</head>
<body>
<div data-role="page" data-url="map-page">

    <!-- このファイルは全体募集のものにのみ使用する -->

    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    $workid = $_GET['workid'];
    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'chouseikun.php?workid=" . $workid . "', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    $today = date("Y-m-d");

    //募集情報の詳細
    $result = mysql_query("SELECT DISTINCT * FROM helplist WHERE id='" . $workid
        . "'") or die ("Query error: " . mysql_error());
    if (mysql_num_rows($result) == 0) {
        echo "無効な募集IDです";
        exit;
    }
    $record = mysql_fetch_assoc($result);

    $resultworkdate
        = mysql_query("SELECT helpdateid,workdate,comment FROM helpdate WHERE workid='"
        . $workid . "' ORDER BY workdate") or die ("Query error: " . mysql_error());
    $recordsworkdate = array();
    while ($rowworkdate = mysql_fetch_assoc($resultworkdate)) {
        $recordsworkdate[] = $rowworkdate;
    }

    //募集ステータス
    // 0: 募集中
    // 1: 締切済
    // 4: 非公開
    if ($record['status'] < 4) {
        //興味あり者一覧を取得
        $result3
            = mysql_query("SELECT * FROM helpchousei WHERE helpdateid IN (SELECT helpdateid FROM helpdate WHERE workid='"
            . $workid . "')") or die ("Query error: " . mysql_error());
        $attendancelist = array();
        while ($row3 = mysql_fetch_assoc($result3)) {
            $attendancelist[$row3['helpdateid']][$row3['workerno']]
                = $row3['attendance'];
        }
        $result4
            = mysql_query("SELECT userno,nickname FROM db_user WHERE userno IN (SELECT applyuserno FROM helpmatching WHERE workid='"
            . $workid . "')") or die ("Query error: " . mysql_error());
        $nicknamelist = array();
        while ($row4 = mysql_fetch_assoc($result4)) {
            $nicknamelist[$row4['userno']] = $row4['nickname'];
        }
    } else {
        echo "非公開になった案件です";
        exit;
    }

    mysql_close($con);

    $marubatsu = array("×", "○");

    ?>

    <script type="text/javascript">
        // 緯度経度をjavascriptに渡して，後でマップに使う
        var work_detail = <?php echo json_encode($record); ?>;
    </script>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <h2 id="worktitle"><?php if ($record['worktitle'] == "") {
                echo "未入力";
            } else {
                echo $record['worktitle'];
            } ?></h2>
        <!--p id="content"><?php if ($record['content'] == "") {
            echo "未入力";
        } else {
            echo nl2br($record['content']);
        } ?></p-->
        <h3>日程調整</h3>
        <?php
        if (count($recordsworkdate) == 0) {
            echo "日程なし";
        } else {
            foreach ($recordsworkdate as $eachworkdate) {
                //募集者なら、日程を削除したり、コメントを追加したりできる
                if ($_SESSION['userno'] == $record['userno']
                    || $_SESSION['userno'] == 1
                ) {
                    echo "<br><h3 id=\"eachworkdate_" . $eachworkdate['workdate']
                        . "\">" . $eachworkdate['workdate']
                        . "　<a onclick=\"deleteeachdate('"
                        . $eachworkdate['helpdateid'] . "')\">削除</a></h3>";
                    if ($eachworkdate['comment'] == "") {
                        $eachworkdate['comment'] = "時間を入力する場合ダブルクリック";
                    }
                    echo "<span id=\"comment_" . $eachworkdate['helpdateid']
                        . "\" class=\"jobreport\">" . $eachworkdate['comment']
                        . "</span><br><script>$(\"span#comment_"
                        . $eachworkdate['helpdateid']
                        . "\").editable(\"click\",function(e){editcomment("
                        . $eachworkdate['helpdateid'] . ",e.value);});</script>";
                } //通常の応募者なら、その結果を閲覧するだけ
                else {
                    echo "<br><h3>" . $eachworkdate['workdate'] . "</h3>";
                    echo "<span>" . $eachworkdate['comment'] . "</span><br>";
                }


                //参加、不参加を未回答なら回答。回答済みなら、変更ボタン
                if (!isset($attendancelist[$eachworkdate['helpdateid']][$_SESSION['userno']])) {
                    echo "<div>";
                    echo "<div class=\"ui-block-a\"><input type=\"button\" data-mini=\"true\" onclick=\"answerattendance("
                        . $eachworkdate['helpdateid'] . "," . $_SESSION['userno']
                        . ", 1)\" value=\"参加\"></div>";
                    echo "<div class=\"ui-block-b\"><input type=\"button\" data-mini=\"true\" data-theme=\"c\" onclick=\"answerattendance("
                        . $eachworkdate['helpdateid'] . "," . $_SESSION['userno']
                        . ", 0)\" value=\"不参加\"></div>";
                    echo "</div>";
                } else {
                    if ($attendancelist[$eachworkdate['helpdateid']][$_SESSION['userno']]
                        == 1
                    ) {
                        echo "<p>【参加】→<input type=\"button\" data-role=\"none\" data-inline=\"true\" value=\"不参加に変更\" onClick=\"changeattendance("
                            . $eachworkdate['helpdateid'] . "," . $_SESSION['userno']
                            . ", 0);\" /></p>";
                    } else {
                        if ($attendancelist[$eachworkdate['helpdateid']][$_SESSION['userno']]
                            == 0
                        ) {
                            echo "<p>【不参加】→<input type=\"button\" data-role=\"none\" data-inline=\"true\" value=\"参加に変更\" onClick=\"changeattendance("
                                . $eachworkdate['helpdateid'] . ","
                                . $_SESSION['userno'] . ", 1);\" /></p>";
                        }
                    }
                }


                //参加者一覧の表示
                if (count($attendancelist[$eachworkdate['helpdateid']]) > 0) {
                    arsort($attendancelist[$eachworkdate['helpdateid']]);
                    echo "<p style=\"clear:both;\">参加者一覧</p>";
                    echo "<table style=\"text-align: center;\"><tr>";
                    reset($attendancelist[$eachworkdate['helpdateid']]);
                    while (($eachattendance
                            = current($attendancelist[$eachworkdate['helpdateid']]))
                        !== false) {
                        echo "<td>" . $marubatsu[$eachattendance] . "</td>";
                        next($attendancelist[$eachworkdate['helpdateid']]);
                    }
                    echo "</tr><tr>";
                    reset($attendancelist[$eachworkdate['helpdateid']]);
                    while (($eachattendance
                            = current($attendancelist[$eachworkdate['helpdateid']]))
                        !== false) {
                        echo "<td><a href=\"mypage.php?userno="
                            . key($attendancelist[$eachworkdate['helpdateid']])
                            . "\" rel=\"external\"><img src=\"./model/showuserimage.php?userno="
                            . key($attendancelist[$eachworkdate['helpdateid']])
                            . "\" onerror=\"this.src='img/noimage.svg';\" class=\"bbsphoto\" width=\"50px\"></a></td>";
                        next($attendancelist[$eachworkdate['helpdateid']]);
                    }
                    echo "</tr><tr>";
                    reset($attendancelist[$eachworkdate['helpdateid']]);
                    while (($eachattendance
                            = current($attendancelist[$eachworkdate['helpdateid']]))
                        !== false) {
                        echo "<td><a href=\"mypage.php?userno="
                            . key($attendancelist[$eachworkdate['helpdateid']])
                            . "\" rel=\"external\">"
                            . $nicknamelist[key($attendancelist[$eachworkdate['helpdateid']])]
                            . "</a></td>";
                        next($attendancelist[$eachworkdate['helpdateid']]);
                    }
                    echo "</tr></table>";
                }


                //すきま
                echo "<br><br><br>";
            }
        }
        //日程追加ボタンを表示
        if ($_SESSION['userno'] == $record['userno']
            || $_SESSION['userno'] == 1
        ) {
            echo "<br><button data-role=\"none\" id=\"addworkdatebutton\" onclick=\"$('#visibleaddworkdate').show();$('#addworkdatebutton').hide();\">日程を追加</button>";
        }
        ?>
        <div id="visibleaddworkdate" style="display:none;">
            <input data-role="date" type="text" name="addworkdate"
                   id="addworkdate" placeholder="追加日程" required/>
            <input type="button" data-theme="c" value="日程を追加する"
                   onClick="addworkdate(1);"/>
            </br>
        </div>
    </div>
    <?php include("./common/commonFooter.php"); ?>
    <script>$(function () {
            $.datepicker.setDefaults($.datepicker.regional['ja']);
            $('#addworkdate').datepicker({dateFormat: 'yy-mm-dd'});
        });</script>
    <script type="text/javascript" src="js/jobDetail.js"></script>
</div>
</body>
</html>