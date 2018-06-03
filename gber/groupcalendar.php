<?php
require_once __DIR__ . '/lib/auth.php';
require_logined_session();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <!-- テーブルソート用 -->
    <link rel="stylesheet" type="text/css" href="js/tablesorter/style.css"/>
    <script type="text/javascript"
            src="js/tablesorter/jquery.tablesorter.min.js"></script>
</head>
<body>

<div data-role="page"><!-- WRAPPER -->
    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    $groupno = $_GET['groupno'];
    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'groupcalendar.php?groupno=" . $groupno . "', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    if ($groupno == 0 || $groupno > count($groupnamelist) - 1) {
        echo "グループ名が無効です";
        exit;
    }

    //****** 管理者権限のあるSESSION_IDかどうか確認 ******//
    $admincheck = mysql_query("SELECT admin FROM grouplist WHERE groupno = '"
        . $groupno . "' and userno = '" . $_SESSION['userno'] . "'")
    or die ("Query error: " . mysql_error());
    if (mysql_fetch_assoc($admincheck)['admin'] == 0) {
        echo "管理者権限がありません";
        exit;
    }

    //****** グループ構成員のプロフィール ******//
    $result
        = mysql_query("SELECT userno,mail,phone,nickname,gender,birthyear,mylat,mylng FROM db_user WHERE userno IN (SELECT DISTINCT userno FROM grouplist WHERE groupno = '"
        . $groupno . "' ORDER BY userno)") or die ("Query error: " . mysql_error());
    $records = array();
    while ($row = mysql_fetch_assoc($result)) {
        $records[] = $row;
    }

    // グループ構成員のスケジュールを$calendarに登録する
    $calendar = array();
    foreach ($records as $eachrecord) {
        $calendar[$eachrecord['userno']]['userno'] = $eachrecord['userno'];
        $calendar[$eachrecord['userno']]['active'] = 0;
        $calendar[$eachrecord['userno']]['next_active'] = 0;
        for ($j = 1; $j < 32; $j++) {
            $calendar[$eachrecord['userno']]['lastupdated'] = "2000-01-01";
            $calendar[$eachrecord['userno']]['next_lastupdated'] = "2000-01-01";
            $calendar[$eachrecord['userno']]['d' . $j . '_am'] = 0;
            $calendar[$eachrecord['userno']]['d' . $j . '_pm'] = 0;
            $calendar[$eachrecord['userno']]['next_d' . $j . '_am'] = 0;
            $calendar[$eachrecord['userno']]['next_d' . $j . '_pm'] = 0;
        }
    }
    $now_year = date("Y"); // 現在の年を取得．西暦
    $now_month = date("n"); // 現在の月を取得．0をつけない
    if ($now_month + 1 > 12) {
        $next_month = 1;
        $next_month_year = $now_year + 1;
    } else {
        $next_month = $now_month + 1;
        $next_month_year = $now_year;
    }
    $result3
        = mysql_query("SELECT * FROM schedule WHERE userno IN (SELECT DISTINCT userno FROM grouplist WHERE groupno = '"
        . $groupno . "' ORDER BY userno) AND year=$now_year AND month=$now_month")
    or die ("Query error: " . mysql_error());
    while ($row3 = mysql_fetch_assoc($result3)) {
        $calendar[$row3['userno']]['active'] = 1;
        $calendar[$row3['userno']]['lastupdated'] = $row3['lastupdated'];
        for ($j = 1; $j < 32; $j++) {
            if ($row3['d' . $j . '_am'] == 1) {
                $calendar[$row3['userno']]['d' . $j . '_am'] = 1;
            }
            if ($row3['d' . $j . '_pm'] == 1) {
                $calendar[$row3['userno']]['d' . $j . '_pm'] = 1;
            }
        }
    }
    $result5
        = mysql_query("SELECT * FROM schedule WHERE userno IN (SELECT DISTINCT userno FROM grouplist WHERE groupno = '"
        . $groupno
        . "' ORDER BY userno) AND year=$next_month_year AND month=$next_month")
    or die ("Query error: " . mysql_error());
    while ($row5 = mysql_fetch_assoc($result5)) {
        //予定表を開くと翌月までのスケジュールが登録されるので，スケジュールTableに載っている＝1ヶ月〜2ヶ月以内にログインしている
        $calendar[$row5['userno']]['next_active'] = 1;
        $calendar[$row5['userno']]['next_lastupdated'] = $row5['lastupdated'];
        for ($j = 1; $j < 32; $j++) {
            if ($row5['d' . $j . '_am'] == 1) {
                $calendar[$row5['userno']]['next_d' . $j . '_am'] = 1;
            }
            if ($row5['d' . $j . '_pm'] == 1) {
                $calendar[$row5['userno']]['next_d' . $j . '_pm'] = 1;
            }
        }
    }

    mysql_close($con);

    $weekday = array('日', '月', '火', '水', '木', '金', '土');
    $a = array('<td>×</td>', '<td>△</td>', '<td>◯</td>');
    $evalarray = array('未評価', '初心者', '中級者', '上級者');


    //var_dump($calendar);

    ?>


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content"><!-- START OF CONTENT -->
        <h1>メンバーカレンダー</h1>


        <div id="cal">
            <h2><?php echo $now_year . "年" . $now_month; ?>月のカレンダー</h2>
            <p>全員
                <button data-role="none" onclick="showall();">表示</button>
                <button data-role="none" onclick="hideall();">非表示</button>
            </p>
            <?php
            // カレンダーを更新した人の一覧を表示
            foreach ($records as $eachmember) {
                if ($calendar[$eachmember['userno']]['active'] == 1) {
                    $eachmemberupdatestr = "<p><a href=\"mypage.php?userno="
                        . $eachmember['userno'] . "\" rel=\"external\">"
                        . $eachmember['nickname'] . "</a> ";
                    if ($calendar[$eachmember['userno']]['lastupdated']
                        === "2000-01-01"
                    ) {
                        $eachmemberupdatestr = $eachmemberupdatestr
                            . " 入力されていません";
                    } else {
                        $eachmemberupdatestr = $eachmemberupdatestr . " 更新日"
                            . $calendar[$eachmember['userno']]['lastupdated']
                            . " <button data-role=\"none\" onclick=\"showuser("
                            . $eachmember['userno']
                            . ");\">表示</button><button data-role=\"none\" onclick=\"hideuser("
                            . $eachmember['userno'] . ");\">非表示</button></p>";
                    }
                    echo $eachmemberupdatestr;
                } else {
                    $eachmemberupdatestr = "<p><a href=\"mypage.php?userno="
                        . $eachmember['userno'] . "\" rel=\"external\">"
                        . $eachmember['nickname'] . "</a> ";
                    $eachmemberupdatestr = $eachmemberupdatestr
                        . " カレンダーを開いていません";
                    $eachmemberupdatestr = $eachmemberupdatestr . "</p>";
                    echo $eachmemberupdatestr;
                }
            }

            $now_day = date("j"); // 現在の日を取得．0をつけない
            $fir_weekday = date("w", mktime(0, 0, 0, $now_month, 1, $now_year));

            //************ 今月分 ************//
            echo "<table class=\"admin\" style=\"text-align:center;\">\n";

            // 曜日セル<th>タグ設定
            echo "<tr>\n";
            $i = 0;
            while ($i <= 6) {
                if ($i == 0) {
                    $style = "#C30";
                } else {
                    if ($i == 6) {
                        $style = "#03C";
                    } else {
                        $style = "black";
                    }
                }
                echo "\t<th style=\"color:" . $style . "\" colspan=\"2\">"
                    . $weekday[$i] . "</th>\n";
                $i++; //カウント値+1
            }
            echo "</tr>\n<tr>\n";

            //１日の曜日まで空白（&nbsp;）で埋める
            $i = 0;
            while ($i != $fir_weekday) {
                echo "\t<td colspan=\"2\">&nbsp;</td>\n";
                $i++;
            }

            // 今月の日付が存在している間ループする
            for ($day = 1; checkdate($now_month, $day, $now_year); $day++) {
                if ($i > 6) {
                    $i = 0;
                    echo "</tr>\n<tr>\n";
                    for ($j = $day - 7; $j < $day; $j++) {
                        if ($j < 1) {
                            echo "\t<td colspan=\"2\">&nbsp;</td>\n";
                        } else {
                            if ($j < $now_day) {
                                echo "\t<td colspan=\"2\">-</td>\n";
                            } else { // 終日空いている人を表示する
                                echo "\t<td colspan=\"2\" id=\"job_day_"
                                    . sprintf('%02d', $j) . "\">";
                                foreach ($records as $eachrecord) {
                                    if ($calendar[$eachrecord['userno']]['active']
                                        == 1
                                        && $calendar[$eachrecord['userno']]['d'
                                        . $j . '_am'] == 1
                                        && $calendar[$eachrecord['userno']]['d'
                                        . $j . '_pm'] == 1
                                    ) {
                                        echo "<span class=\"user"
                                            . $eachrecord['userno'] . "\">"
                                            . $eachrecord['nickname']
                                            . "</span></br>";
                                    }
                                }
                                echo "</td>\n";
                            }
                        }
                    }
                    echo "</tr>\n<tr>\n";
                    // 午前か午後のみ空いている人を表示する
                    echo "</tr>\n<tr>\n";
                    for ($j = $day - 7; $j < $day; $j++) {
                        if ($j < 1) {
                            echo "\t<td colspan=\"2\">&nbsp;</td>\n";
                        } else {
                            if ($j < $now_day) {
                                echo "\t<td colspan=\"2\">-</td>\n";
                            } else {
                                // まずは午前を表示
                                echo "\t<td id=\"day_" . $j . "_am\">";
                                foreach ($records as $eachrecord) {
                                    if ($calendar[$eachrecord['userno']]['active']
                                        == 1
                                        && $calendar[$eachrecord['userno']]['d'
                                        . $j . '_am'] == 1
                                        && $calendar[$eachrecord['userno']]['d'
                                        . $j . '_pm'] == 0
                                    ) {
                                        echo "<span class=\"user"
                                            . $eachrecord['userno'] . "\">"
                                            . $eachrecord['nickname']
                                            . "</span></br>";
                                    }
                                }
                                echo "</td>\n";
                                // 次に午後を表示
                                echo "\t<td id=\"day_" . $j . "_pm\">";
                                foreach ($records as $eachrecord) {
                                    if ($calendar[$eachrecord['userno']]['active']
                                        == 1
                                        && $calendar[$eachrecord['userno']]['d'
                                        . $j . '_am'] == 0
                                        && $calendar[$eachrecord['userno']]['d'
                                        . $j . '_pm'] == 1
                                    ) {
                                        echo "<span class=\"user"
                                            . $eachrecord['userno'] . "\">"
                                            . $eachrecord['nickname']
                                            . "</span></br>";
                                    }
                                }
                                echo "</td>\n";
                            }
                        }
                    }
                    echo "</tr>\n<tr>\n";
                }

                if ($i == 0) {
                    $style = "#C30";
                } else {
                    if ($i == 6) {
                        $style = "#03C";
                    } else {
                        $style = "black";
                    }
                }
                if ($day == $now_day) {
                    $style = $style . "; background:silver";
                }

                echo "\t<td style=\"color:" . $style . ";\" colspan=\"2\">" . $day
                    . "</td>\n";

                $i++; //カウント値（曜日カウンター）+1
            }
            $isave = $i;

            while ($i < 7) { //残りの曜日分空白（&nbsp;）で埋める
                echo "\t<td colspan=\"2\">&nbsp;</td>\n";
                $i++;
            }

            //****ここから最終週の分の予定を埋める****
            echo "</tr>\n<tr>\n";
            // 終日空いている人を表示
            for ($j = $day - $isave; $j < $day - $isave + 7; $j++) {
                if ($j < $now_day) {
                    echo "\t<td colspan=\"2\">-</td>\n";
                } else {
                    if (checkdate($now_month, $j, $now_year)) {
                        echo "\t<td colspan=\"2\" id=\"job_day_" . sprintf('%02d',
                                $j) . "\">";
                        foreach ($records as $eachrecord) {
                            if ($calendar[$eachrecord['userno']]['active'] == 1
                                && $calendar[$eachrecord['userno']]['d' . $j
                                . '_am'] == 1
                                && $calendar[$eachrecord['userno']]['d' . $j
                                . '_pm'] == 1
                            ) {
                                echo "<span class=\"user" . $eachrecord['userno']
                                    . "\">" . $eachrecord['nickname']
                                    . "</span></br>";
                            }
                        }
                        echo "</td>\n";
                    } else {
                        echo "\t<td colspan=\"2\">&nbsp;</td>\n";
                    }
                }
            }
            echo "</tr>\n<tr>\n";
            // 午前or午後のみ空いている人を表示する
            echo "</tr>\n<tr>\n";
            for ($j = $day - $isave; $j < $day - $isave + 7; $j++) {
                if ($j < $now_day) {
                    echo "\t<td colspan=\"2\">-</td>\n";
                } else {
                    if (checkdate($now_month, $j, $now_year)) {
                        // まずは午前を表示
                        echo "\t<td id=\"day_" . $j . "_am\">";
                        foreach ($records as $eachrecord) {
                            if ($calendar[$eachrecord['userno']]['active'] == 1
                                && $calendar[$eachrecord['userno']]['d' . $j
                                . '_am'] == 1
                                && $calendar[$eachrecord['userno']]['d' . $j
                                . '_pm'] == 0
                            ) {
                                echo "<span class=\"user" . $eachrecord['userno']
                                    . "\">" . $eachrecord['nickname']
                                    . "</span></br>";
                            }
                        }
                        echo "</td>\n";
                        // 次に午後を表示
                        echo "\t<td id=\"day_" . $j . "_pm\">";
                        foreach ($records as $eachrecord) {
                            if ($calendar[$eachrecord['userno']]['active'] == 1
                                && $calendar[$eachrecord['userno']]['d' . $j
                                . '_am'] == 0
                                && $calendar[$eachrecord['userno']]['d' . $j
                                . '_pm'] == 1
                            ) {
                                echo "<span class=\"user" . $eachrecord['userno']
                                    . "\">" . $eachrecord['nickname']
                                    . "</span></br>";
                            }
                        }
                        echo "</td>\n";
                    } else {
                        echo "\t<td colspan=\"2\">&nbsp;</td>\n";
                    }
                }
            }
            // テーブルを閉じる
            echo "</tr>\n</table>\n</br>\n";

            ?>


            <h2><?php echo $next_month_year . "年" . $next_month; ?>月のカレンダー</h2>
            <p>全員
                <button data-role="none" onclick="showall();">表示</button>
                <button data-role="none" onclick="hideall();">非表示</button>
            </p>
            <?php
            // カレンダーを更新した人の一覧を表示
            foreach ($records as $eachmember) {
                if ($calendar[$eachmember['userno']]['next_active'] == 1) {
                    $eachmemberlastupdatestr_next
                        = "<p><a href=\"mypage.php?userno="
                        . $eachmember['userno'] . "\" rel=\"external\">"
                        . $eachmember['nickname'] . "</a> ";
                    if ($calendar[$eachmember['userno']]['next_lastupdated']
                        === "2000-01-01"
                    ) {
                        $eachmemberlastupdatestr_next
                            = $eachmemberlastupdatestr_next . " 入力されていません";
                    } else {
                        $eachmemberlastupdatestr_next
                            = $eachmemberlastupdatestr_next . " 更新日"
                            . $calendar[$eachmember['userno']]['next_lastupdated']
                            . " <button data-role=\"none\" onclick=\"showuser("
                            . $eachmember['userno']
                            . ");\">表示</button><button data-role=\"none\" onclick=\"hideuser("
                            . $eachmember['userno'] . ");\">非表示</button></p>";
                    }
                    echo $eachmemberlastupdatestr_next;
                } else {
                    $eachmemberupdatestr_next
                        = "<p><a href=\"mypage.php?userno="
                        . $eachmember['userno'] . "\" rel=\"external\">"
                        . $eachmember['nickname'] . "</a> ";
                    $eachmemberupdatestr_next = $eachmemberupdatestr_next
                        . " カレンダーを開いていません";
                    $eachmemberupdatestr_next = $eachmemberupdatestr_next
                        . "</p>";
                    echo $eachmemberupdatestr_next;
                }
            }

            $fir_weekday_next = date("w",
                mktime(0, 0, 0, $next_month, 1, $next_month_year));


            //************ 来月分 ************//
            echo "<table class=\"admin\" style=\"text-align:center;\">\n";

            // 曜日セル<th>タグ設定
            echo "<tr>\n";
            $i = 0;
            while ($i <= 6) {
                if ($i == 0) {
                    $style = "#C30";
                } else {
                    if ($i == 6) {
                        $style = "#03C";
                    } else {
                        $style = "black";
                    }
                }
                echo "\t<th style=\"color:" . $style . "\" colspan=\"2\">"
                    . $weekday[$i] . "</th>\n";
                $i++; //カウント値+1
            }
            echo "</tr>\n<tr>\n";

            //１日の曜日まで空白（&nbsp;）で埋める
            $i = 0;
            while ($i != $fir_weekday_next) {
                echo "\t<td colspan=\"2\">&nbsp;</td>\n";
                $i++;
            }

            // 今月の日付が存在している間ループする
            for (
                $day = 1; checkdate($next_month, $day, $next_month_year); $day++
            ) {
                if ($i > 6) {
                    $i = 0;
                    // 終日空いている人を表示する
                    echo "</tr>\n<tr>\n";
                    for ($j = $day - 7; $j < $day; $j++) {
                        if ($j < 1) {
                            echo "\t<td colspan=\"2\">&nbsp;</td>\n";
                        } else {
                            echo "\t<td colspan=\"2\" id=\"next_job_day_"
                                . sprintf('%02d', $j) . "\">";
                            foreach ($records as $eachrecord) {
                                if ($calendar[$eachrecord['userno']]['next_active']
                                    == 1
                                    && $calendar[$eachrecord['userno']]['next_d'
                                    . $j . '_am'] == 1
                                    && $calendar[$eachrecord['userno']]['next_d'
                                    . $j . '_pm'] == 1
                                ) {
                                    echo "<span class=\"user"
                                        . $eachrecord['userno'] . "\">"
                                        . $eachrecord['nickname'] . "</span></br>";
                                }
                            }
                            echo "</td>\n";
                        }
                    }
                    echo "</tr>\n<tr>\n";
                    // 午前or午後のみ空いている人を表示する
                    echo "</tr>\n<tr>\n";
                    for ($j = $day - 7; $j < $day; $j++) {
                        if ($j < 1) {
                            echo "\t<td colspan=\"2\">&nbsp;</td>\n";
                        } else {
                            // まずは午前を表示
                            echo "\t<td id=\"next_day_" . $j . "_am\">";
                            foreach ($records as $eachrecord) {
                                if ($calendar[$eachrecord['userno']]['next_active']
                                    == 1
                                    && $calendar[$eachrecord['userno']]['next_d'
                                    . $j . '_am'] == 1
                                    && $calendar[$eachrecord['userno']]['next_d'
                                    . $j . '_pm'] == 0
                                ) {
                                    echo "<span class=\"user"
                                        . $eachrecord['userno'] . "\">"
                                        . $eachrecord['nickname'] . "</span></br>";
                                }
                            }
                            echo "</td>\n";
                            // 次に午後を表示
                            echo "\t<td id=\"next_day_" . $j . "_pm\">";
                            foreach ($records as $eachrecord) {
                                if ($calendar[$eachrecord['userno']]['next_active']
                                    == 1
                                    && $calendar[$eachrecord['userno']]['next_d'
                                    . $j . '_am'] == 0
                                    && $calendar[$eachrecord['userno']]['next_d'
                                    . $j . '_pm'] == 1
                                ) {
                                    echo "<span class=\"user"
                                        . $eachrecord['userno'] . "\">"
                                        . $eachrecord['nickname'] . "</span></br>";
                                }
                            }
                            echo "</td>\n";
                        }
                    }
                    echo "</tr>\n<tr>\n";
                }

                if ($i == 0) {
                    $style = "#C30";
                } else {
                    if ($i == 6) {
                        $style = "#03C";
                    } else {
                        $style = "black";
                    }
                }

                echo "\t<td style=\"color:" . $style . ";\" colspan=\"2\">" . $day
                    . "</td>\n";

                $i++; //カウント値（曜日カウンター）+1
            }
            $isave = $i;

            while ($i < 7) { //残りの曜日分空白（&nbsp;）で埋める
                echo "\t<td colspan=\"2\">&nbsp;</td>\n";
                $i++;
            }

            //****ここから最終週の分の予定を埋める****
            echo "</tr>\n<tr>\n";
            // 終日空いている人を表示
            for ($j = $day - $isave; $j < $day - $isave + 7; $j++) {
                if (checkdate($next_month, $j, $next_month_year)) {
                    echo "\t<td colspan=\"2\" id=\"next_job_day_"
                        . sprintf('%02d', $j) . "\">";
                    foreach ($records as $eachrecord) {
                        if ($calendar[$eachrecord['userno']]['next_active'] == 1
                            && $calendar[$eachrecord['userno']]['next_d' . $j
                            . '_am'] == 1
                            && $calendar[$eachrecord['userno']]['next_d' . $j
                            . '_pm'] == 1
                        ) {
                            echo "<span class=\"user" . $eachrecord['userno']
                                . "\">" . $eachrecord['nickname'] . "</span></br>";
                        }
                    }
                    echo "</td>\n";
                } else {
                    echo "\t<td colspan=\"2\">&nbsp;</td>\n";
                }
            }
            echo "</tr>\n<tr>\n";
            // 片方だけ空いている人を表示する
            echo "</tr>\n<tr>\n";
            for ($j = $day - $isave; $j < $day - $isave + 7; $j++) {
                if (checkdate($next_month, $j, $next_month_year)) {
                    // まずは午前を表示
                    echo "\t<td id=\"next_day_" . $j . "_am\">";
                    foreach ($records as $eachrecord) {
                        if ($calendar[$eachrecord['userno']]['next_active'] == 1
                            && $calendar[$eachrecord['userno']]['next_d' . $j
                            . '_am'] == 1
                            && $calendar[$eachrecord['userno']]['next_d' . $j
                            . '_pm'] == 0
                        ) {
                            echo "<span class=\"user" . $eachrecord['userno']
                                . "\">" . $eachrecord['nickname'] . "</span></br>";
                        }
                    }
                    echo "</td>\n";
                    // 次に午後を表示
                    echo "\t<td id=\"next_day_" . $j . "_pm\">";
                    foreach ($records as $eachrecord) {
                        if ($calendar[$eachrecord['userno']]['next_active'] == 1
                            && $calendar[$eachrecord['userno']]['next_d' . $j
                            . '_am'] == 0
                            && $calendar[$eachrecord['userno']]['next_d' . $j
                            . '_pm'] == 1
                        ) {
                            echo "<span class=\"user" . $eachrecord['userno']
                                . "\">" . $eachrecord['nickname'] . "</span></br>";
                        }
                    }
                    echo "</td>\n";
                } else {
                    echo "\t<td colspan=\"2\">&nbsp;</td>\n";
                }
            }
            // テーブルを閉じる
            echo "</tr>\n</table>\n</br>\n";

            ?>
        </div>


    </div><!-- end of content -->


    <?php include("./common/commonFooter.php"); ?>
    <script>
        var groupmemberidlist = [<?php
            $firstlist = true;
            foreach ($records as $eachrecord) {
                if ($firstlist) {
                    echo "\"" . $eachrecord["userno"] . "\"";
                    $firstlist = false;
                } else {
                    echo ",\"" . $eachrecord["userno"] . "\"";
                }
            }
            ?>];

        function showuser(userno) {
            $("span.user" + userno).show();
        }

        function hideuser(userno) {
            $("span.user" + userno).hide();
        }

        function showall() {
            $.each(groupmemberidlist, function (index, val) {
                $("span.user" + val).show();
            });
        }

        function hideall() {
            $.each(groupmemberidlist, function (index, val) {
                $("span.user" + val).hide();
            });
        }
    </script>
</div><!-- end of wrapper -->
</body>
</html>