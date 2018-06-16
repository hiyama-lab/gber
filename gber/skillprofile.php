<?php
require_once __DIR__ . '/lib/auth.php';
require_logined_session();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <link rel="stylesheet" type="text/css" href="js/rateit/rateit.css"/>
    <script type="text/javascript"
            src="js/rateit/jquery.rateit.min.js"></script>
    <script type="text/javascript" src="js/logout.js"></script>
</head>
<body>
<div data-role="page" id="mypage">

    <?php
    include __DIR__ . '/lib/mysql_credentials.php';


    $userno = $_GET['userno'];
    $userselect = true;

    // ユーザー番号が指定されていない時は、セレクターのみ表示させる。
    if ($userno == "") {
        $userselect = false;
    }

    // 閲覧したいユーザーのIDが指定されている時
    if ($userselect) {

        $activitylog
            = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
            . $_SESSION['userno'] . "', 'skillprofile.php?userno=" . $userno . "', '"
            . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

// ユーザの情報を取得
        $sql = "SELECT * FROM db_user WHERE userno='" . $userno . "'";
        $result = mysql_query($sql) or die ("Query error: " . mysql_error());
        if (mysql_num_rows($result) == 0) {
            echo "ユーザIDが存在しません";
            exit;
        }
        $records = array();
        while ($row = mysql_fetch_assoc($result)) {
            $records[] = $row;
        }

// 参加しているグループのリスト
        $result2 = mysql_query("SELECT * FROM grouplist WHERE userno='" . $userno
            . "' and groupno>0") or die ("Query error: " . mysql_error());
        $records2 = array();
        while ($row2 = mysql_fetch_assoc($result2)) {
            $records2[] = $row2;
        }

        $i = 0;
        foreach ($records2 as $eachrecord) {
            $result20
                = mysql_query("SELECT count(distinct workid), count(distinct workday) FROM workdate LEFT JOIN worklist ON workdate.workid=worklist.id WHERE worklist.groupno=". $eachrecord['groupno'] . " AND workdate.workerno='" . $userno . "'")
            or die ("Query error: " . mysql_error());
            $row20 = mysql_fetch_assoc($result20);
            $records2[$i]['workid'] = $row20['count(distinct workid)'];
            $records2[$i]['workday'] = $row20['count(distinct workday)'];
            $i++;
        }


//スキル情報
        $answerdemographic = false;
        $demographic
            = mysql_query("SELECT * FROM questionnaire_demographic WHERE userno = '"
            . $userno . "'") or die ("Query error: " . mysql_error());
        if (mysql_num_rows($demographic) > 0) {
            $answerdemographic = true;
            $demographicresult = mysql_fetch_assoc($demographic);
        }
        $answerworkstyle = false;
        $workstyle
            = mysql_query("SELECT * FROM questionnaire_workstyle WHERE userno = '"
            . $userno . "'") or die ("Query error: " . mysql_error());
        if (mysql_num_rows($workstyle) > 0) {
            $answerworkstyle = true;
            $workstyleresult = mysql_fetch_assoc($workstyle);
        }
        $answersocialactivity = false;
        $socialactivity
            = mysql_query("SELECT * FROM questionnaire_socialactivity WHERE userno = '"
            . $userno . "'") or die ("Query error: " . mysql_error());
        if (mysql_num_rows($socialactivity) > 0) {
            $answersocialactivity = true;
            $socialactivityresult = mysql_fetch_assoc($socialactivity);
        }
        $matchingparamquery
            = mysql_query("SELECT * FROM matchingparam_human WHERE userno = '"
            . $userno . "'") or die ("Query error: " . mysql_error());
        $matchingparam = mysql_fetch_assoc($matchingparamquery);

//時間情報
        $now_year = date("Y");
        $now_month = date("n");
        if ($now_month + 1 > 12) {
            $next_month = 1;
            $next_month_year = $now_year + 1;
        } else {
            $next_month = $now_month + 1;
            $next_month_year = $now_year;
        }
// 今月のスケジュール取得
        $sql3
            = "SELECT * FROM schedule WHERE userno=$userno AND year=$now_year AND month=$now_month";
        $result3 = mysql_query($sql3) or die ("Query error: " . mysql_error());
        $records3 = array();
        while ($row3 = mysql_fetch_assoc($result3)) {
            $records3[] = $row3;
        }
        if (count($records3) == 0) { // スケジュールに載ってなかったら挿入して取得する
            $sql4
                = "INSERT INTO schedule (userno, year, month) VALUES ($userno, $now_year, $now_month)";
            $result4 = mysql_query($sql4) or die ("Query error: "
                . mysql_error());
            $result3 = mysql_query($sql3) or die ("Query error: "
                . mysql_error());
            $records3 = array();
            while ($row3 = mysql_fetch_assoc($result3)) {
                $records3[] = $row3;
            }
        }
// 来月のスケジュール取得
        $sql5
            = "SELECT * FROM schedule WHERE userno=$userno AND year=$next_month_year AND month=$next_month";
        $result5 = mysql_query($sql5) or die ("Query error: " . mysql_error());
        $records5 = array();
        while ($row5 = mysql_fetch_assoc($result5)) {
            $records5[] = $row5;
        }
        if (count($records5) == 0) { // スケジュールに載ってなかったら挿入して取得する
            $sql6
                = "INSERT INTO schedule (userno, year, month) VALUES ($userno, $next_month_year, $next_month)";
            $result6 = mysql_query($sql6) or die ("Query error: "
                . mysql_error());
            $result5 = mysql_query($sql5) or die ("Query error: "
                . mysql_error());
            $records5 = array();
            while ($row5 = mysql_fetch_assoc($result5)) {
                $records5[] = $row5;
            }
        }

        $jobevent = array();
        $jobevent[0] = 0;
        $jobevent[1] = 0;
        $jobeventquery
            = mysql_query("SELECT interest,count(interest) FROM helpmatching WHERE applyuserno = '"
            . $userno . "' GROUP BY interest") or die ("Query error: "
            . mysql_error());
        while ($row50 = mysql_fetch_assoc($jobeventquery)) {
            $jobevent[$row50['interest']] = $row50['count(interest)'];
        }
        $jobeventquery
            = mysql_query("SELECT count(id) FROM helplist WHERE userno = '"
            . $userno . "'") or die ("Query error: " . mysql_error());
        while ($row50 = mysql_fetch_assoc($jobeventquery)) {
            $jobevent[2]
                = $row50['count(id)'];
        }


    }//ユーザ番号が指定されている時終わり

    $userlistresult
        = mysql_query("SELECT userno,nickname FROM db_user WHERE mail <> \"\" ORDER BY userno")
    or die ("Query error: " . mysql_error());
    $userlist = array();
    while ($row = mysql_fetch_assoc($userlistresult)) {
        $userlist[] = $row;
    }

    $marubatsu = ["×", "○"];
    $marubatsublank = ["×", "", "○"];
    $evalarray = array('未評価', '初心者', '中級者', '上級者');

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>
    <!-- CONTENT -->
    <div data-role="content">


        <?php

        if ($_SESSION['userno'] == 1) {

            echo "ユーザー切替　<select data-role=\"none\" name=\"user-selector\" id=\"user-selector\">";
            echo "<option value=\"\" >ユーザを選択してください</option>";
            echo "<option value=\"\" >ユーザ一覧</option>";
            foreach ($userlist as $eachuser) {
                echo "<option value=\"" . $eachuser['userno'] . "\">["
                    . $eachuser['userno'] . "] " . h($eachuser['nickname']) . "</option>";
            }
            echo "</select><br><br>";

        }

        if ($userselect) {


            //名前と写真、本人確認済かどうか
            echo "<div id=\"skill-profile-container\"><img src=\"./model/showuserimage.php?userno="
                . $userno . "\" onerror=\"this.src='img/noimage.svg';\" />";
            echo "<h1>" . h($records[0]['nickname']);
            echo "</h1></div>";

            //定性的紹介文
            if ($records[0]['intro'] == "") {
                $records[0]['intro'] = "記載なし";
            }
            echo "<p>【自己紹介文】" . $records[0]['intro'] . "</p>\n";
            if ($records[0]['adminmemo'] == "") {
                $records[0]['adminmemo'] = "記載なし";
            }
            echo "<p>【運営者メモ】" . h($records[0]['adminmemo']) . "</p>\n";

            //基本情報
            echo "<br><br><h2>基本情報</h2>";
            echo "<table id=\"basicprofile\">";
            echo "<tr>";
            echo "<th class=\"memberprof\">ID</th>";
            echo "<th class=\"memberprof\">ニックネーム</th>";
            echo "<th class=\"memberprof\">メールアドレス</th>";
            echo "<th class=\"memberprof\">電話番号</th>";
            echo "<th class=\"memberprof\">生年</th>";
            echo "<th class=\"memberprof\">性別</th>";
            echo "<th class=\"memberprof\">同居人</th>";
            echo "<th class=\"memberprof\">登録日</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . $records[0]['userno'] . "</td>";
            echo "<td class=\"memberprof\">" . h($records[0]['nickname']) . "</td>";
            echo "<td class=\"memberprof\">" . h($records[0]['mail']) . "</td>";
            echo "<td class=\"memberprof\">" . h($records[0]['phone']) . "</td>";
            echo "<td class=\"memberprof\">" . $records[0]['birthyear'] . "</td>";
            echo "<td class=\"memberprof\">" . h($records[0]['gender']) . "</td>";
            echo "<td class=\"memberprof\">" . h($demographicresult['doukyo'])
                . "</td>";
            echo "<td class=\"memberprof\">" . $records[0]['registered_date']
                . "</td>";
            echo "</tr>\n";
            echo "</table>";


            //時間
            echo "<br><br><h2>時間</h2>";
            echo "<caption style=\"text-align:left\">予定表</caption><br>";
            $now_day = date("j");
            $weekday = array("日", "月", "火", "水", "木", "金", "土");
            $fir_weekday = date("w", mktime(0, 0, 0, $now_month, 1, $now_year));
            $fir_weekday_next = date("w",
                mktime(0, 0, 0, $next_month, 1, $next_month_year));
            $mark = array("×", "◯", "△", "▼");
            echo "<table class=\"smallcalender\" style=\"text-align:center; display:inline-block\">\n";
            echo "<caption style=\"color:black; font-size:14px; padding:0px;\">"
                . $now_year . "年" . $now_month . "月</caption>\n<tr>\n";
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
                echo "\t<th style=\"color:" . $style . "\">" . $weekday[$i] . "</th>\n";
                $i++;
            }
            echo "</tr>\n<tr>\n";
            $i = 0;
            while ($i != $fir_weekday) {
                echo "\t<td>&nbsp;</td>\n";
                $i++;
            }
            for ($day = 1; checkdate($now_month, $day, $now_year); $day++) {
                if ($i > 6) {
                    $i = 0;
                    echo "</tr>\n<tr>\n";
                    for ($j = $day - 7; $j < $day; $j++) {
                        if ($j < 1) {
                            echo "\t<td>&nbsp;</td>\n";
                        } else {
                            if ($j < $now_day) {
                                echo "\t<td>-</td>\n";
                            } else {
                                if ($records3[0]["d" . $j . "_am"] == 0
                                    && $records3[0]["d" . $j . "_pm"] == 0
                                ) {
                                    $d_value = 0;
                                } else {
                                    if ($records3[0]["d" . $j . "_am"] == 1
                                        && $records3[0]["d" . $j . "_pm"] == 1
                                    ) {
                                        $d_value = 1;
                                    } else {
                                        if ($records3[0]["d" . $j . "_am"] == 1
                                            && $records3[0]["d" . $j . "_pm"] == 0
                                        ) {
                                            $d_value = 2;
                                        } else {
                                            if ($records3[0]["d" . $j . "_am"] == 0
                                                && $records3[0]["d" . $j . "_pm"]
                                                == 1
                                            ) {
                                                $d_value = 3;
                                            }
                                        }
                                    }
                                }
                                echo "\t<td id=\"day_" . $j . "\">" . $mark[$d_value]
                                    . "</td>\n";
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
                echo "\t<td style=\"color:" . $style . ";\">" . $day . "</td>\n";
                $i++;
            }
            $isave = $i;
            while ($i < 7) {
                echo "\t<td>&nbsp;</td>\n";
                $i++;
            }
            echo "</tr>\n<tr>\n";
            for ($j = $day - $isave; $j < $day - $isave + 7; $j++) {
                if ($j < $now_day) {
                    echo "\t<td>-</td>\n";
                } else {
                    if (checkdate($now_month, $j, $now_year)) {
                        if ($records3[0]["d" . $j . "_am"] == 0
                            && $records3[0]["d" . $j . "_pm"] == 0
                        ) {
                            $d_value = 0;
                        } else {
                            if ($records3[0]["d" . $j . "_am"] == 1
                                && $records3[0]["d" . $j . "_pm"] == 1
                            ) {
                                $d_value = 1;
                            } else {
                                if ($records3[0]["d" . $j . "_am"] == 1
                                    && $records3[0]["d" . $j . "_pm"] == 0
                                ) {
                                    $d_value = 2;
                                } else {
                                    if ($records3[0]["d" . $j . "_am"] == 0
                                        && $records3[0]["d" . $j . "_pm"] == 1
                                    ) {
                                        $d_value = 3;
                                    }
                                }
                            }
                        }
                        echo "\t<td id=\"day_" . $j . "\">" . $mark[$d_value]
                            . "</td>\n";
                    } else {
                        echo "\t<td>&nbsp;</td>\n";
                    }
                }
            }
            echo "</tr>\n</table>\n";
            echo "<table class=\"smallcalender\" style=\"text-align:center; display:inline-block\">\n";
            echo "<caption style=\"color:black; font-size:14px; padding:0px;\">"
                . $next_month_year . "年" . $next_month . "月</caption>\n<tr>\n";
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
                echo "\t<th style=\"color:" . $style . "\">" . $weekday[$i] . "</th>\n";
                $i++;
            }
            echo "</tr>\n<tr>\n";
            $i = 0;
            while ($i != $fir_weekday_next) {
                echo "\t<td>&nbsp;</td>\n";
                $i++;
            }
            for (
                $day = 1; checkdate($next_month, $day, $next_month_year); $day++
            ) {
                if ($i > 6) {
                    $i = 0;
                    echo "</tr>\n<tr>\n";
                    for ($j = $day - 7; $j < $day; $j++) {
                        if ($j < 1) {
                            echo "\t<td>&nbsp;</td>\n";
                        } else {
                            if ($records5[0]["d" . $j . "_am"] == 0
                                && $records5[0]["d" . $j . "_pm"] == 0
                            ) {
                                $d_value = 0;
                            } else {
                                if ($records5[0]["d" . $j . "_am"] == 1
                                    && $records5[0]["d" . $j . "_pm"] == 1
                                ) {
                                    $d_value = 1;
                                } else {
                                    if ($records5[0]["d" . $j . "_am"] == 1
                                        && $records5[0]["d" . $j . "_pm"] == 0
                                    ) {
                                        $d_value = 2;
                                    } else {
                                        if ($records5[0]["d" . $j . "_am"] == 0
                                            && $records5[0]["d" . $j . "_pm"] == 1
                                        ) {
                                            $d_value = 3;
                                        }
                                    }
                                }
                            }
                            echo "\t<td id=\"next_day_" . $j . "\">" . $mark[$d_value]
                                . "</td>\n";
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
                echo "\t<td style=\"color:" . $style . ";\">" . $day . "</td>\n";
                $i++;
            }
            $isave = $i;
            while ($i < 7) {
                echo "\t<td>&nbsp;</td>\n";
                $i++;
            }
            echo "</tr>\n<tr>\n";
            for ($j = $day - $isave; $j < $day - $isave + 7; $j++) {
                if (checkdate($next_month, $j, $next_month_year)) {
                    if ($records5[0]["d" . $j . "_am"] == 0
                        && $records5[0]["d" . $j . "_pm"] == 0
                    ) {
                        $d_value = 0;
                    } else {
                        if ($records5[0]["d" . $j . "_am"] == 1
                            && $records5[0]["d" . $j . "_pm"] == 1
                        ) {
                            $d_value = 1;
                        } else {
                            if ($records5[0]["d" . $j . "_am"] == 1
                                && $records5[0]["d" . $j . "_pm"] == 0
                            ) {
                                $d_value = 2;
                            } else {
                                if ($records5[0]["d" . $j . "_am"] == 0
                                    && $records5[0]["d" . $j . "_pm"] == 1
                                ) {
                                    $d_value = 3;
                                }
                            }
                        }
                    }
                    echo "\t<td id=\"next_day_" . $j . "\">" . $mark[$d_value]
                        . "</td>\n";
                } else {
                    echo "\t<td>&nbsp;</td>\n";
                }
            }
            echo "</tr>\n</table>\n";
            echo "<br><br>";
            echo "<table id=\"preferredtime\">";
            echo "<caption style=\"text-align:left\">希望就労時間</caption>";
            echo "<tr>";
            echo "<th class=\"memberprof\">1週間の希望就業日数</th>";
            echo "<th class=\"memberprof\">1日の希望就業時間</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . h($workstyleresult['workdayperweek'])
                . "</td>";
            echo "<td class=\"memberprof\">" . h($workstyleresult['worktimeperday'])
                . "</td>";
            echo "</tr>\n";
            echo "</table>";


            //場所
            echo "<br><br><h2>場所</h2>";
            echo "<table id=\"preferredworkplace\">";
            echo "<caption style=\"text-align:left\">自宅位置</caption>";
            echo "<tr>";
            echo "<th class=\"memberprof\">住所</th>";
            echo "<th class=\"memberprof\">自宅緯度</th>";
            echo "<th class=\"memberprof\">自宅経度</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . h($records[0]['address_string'])
                . "</td>";
            echo "<td class=\"memberprof\">" . $records[0]['mylat'] . "</td>";
            echo "<td class=\"memberprof\">" . $records[0]['mylng'] . "</td>";
            echo "</tr>\n";
            echo "</table>";
            echo "<br>";
            echo "<table id=\"preferredworkplace\">";
            echo "<caption style=\"text-align:left\">交通手段</caption>";
            echo "<tr>";
            echo "<th class=\"memberprof\">自家用車</th>";
            echo "<th class=\"memberprof\">電車</th>";
            echo "<th class=\"memberprof\">バス</th>";
            echo "<th class=\"memberprof\">自転車</th>";
            echo "<th class=\"memberprof\">徒歩</th>";
            echo "<th class=\"memberprof\">その他</th>";
            echo "<th class=\"memberprof\">希望通勤時間</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['transit_car']]) . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['transit_train']]) . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['transit_bus']]) . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['transit_bicycle']]) . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['transit_onfoot']]) . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['transit_other']]) . "</td>";
            echo "<td class=\"memberprof\">" . h($workstyleresult['commutetime'])
                . "</td>";
            echo "</tr>\n";
            echo "</table>";


            //スキル
            echo "<br><br><h2>スキル</h2>";
            echo "<table id=\"exercisehabit\">";
            echo "<caption style=\"text-align:left\">運動習慣</caption>";
            echo "<tr>";
            echo "<th class=\"memberprof\">息が弾まない軽い運動</th>";
            echo "<th class=\"memberprof\">多少息がはずむ運動</th>";
            echo "<th class=\"memberprof\">激しく息がはずむ運動</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . h($demographicresult['undou_light'])
                . "</td>";
            echo "<td class=\"memberprof\">" . h($demographicresult['undou_medium'])
                . "</td>";
            echo "<td class=\"memberprof\">" . h($demographicresult['undou_heavy'])
                . "</td>";
            echo "</tr>\n";
            echo "</table>";
            echo "<br>";
            echo "<table id=\"basicprofile2\">";
            echo "<caption style=\"text-align:left\">経歴</caption>";
            echo "<tr>";
            echo "<th class=\"memberprof\">最終学歴</th>";
            echo "<th class=\"memberprof\">業種(大分類)</th>";
            echo "<th class=\"memberprof\">業種(中分類)</th>";
            echo "<th class=\"memberprof\">職種(大分類)</th>";
            echo "<th class=\"memberprof\">職種(中分類)</th>";
            echo "<th class=\"memberprof\">資格</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . h($demographicresult['gakureki'])
                . "</td>";
            echo "<td class=\"memberprof\">" . h($demographicresult['gyoushu'])
                . "</td>";
            echo "<td class=\"memberprof\">" . h($demographicresult['gyoushudetail'])
                . "</td>";
            echo "<td class=\"memberprof\">" . h($demographicresult['shokushu'])
                . "</td>";
            echo "<td class=\"memberprof\">"
                . h($demographicresult['shokushudetail']) . "</td>";
            echo "<td class=\"memberprof\">" . h($demographicresult['shikaku'])
                . "</td>";
            echo "</tr>\n";
            echo "</table>";
            echo "<br>";
            echo "<table id=\"purposeofwork\">";
            echo "<caption style=\"text-align:left\">就労目的</caption>";
            echo "<tr>";
            echo "<th class=\"memberprof\">収入の補填</th>";
            echo "<th class=\"memberprof\">生活水準の向上</th>";
            echo "<th class=\"memberprof\">生きがい</th>";
            echo "<th class=\"memberprof\">健康の保持</th>";
            echo "<th class=\"memberprof\">社会参加・貢献</th>";
            echo "<th class=\"memberprof\">頼まれた</th>";
            echo "<th class=\"memberprof\">時間がある</th>";
            echo "<th class=\"memberprof\">能力経験の活用</th>";
            echo "<th class=\"memberprof\">その他</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['workobject_money_1']]) . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['workobject_money_2']]) . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['workobject_purposeoflife']])
                . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['workobject_health']]) . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['workobject_contribution']])
                . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['workobject_asked']]) . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['workobject_sparetime']]) . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['workobject_skill']]) . "</td>";
            echo "<td class=\"memberprof\">"
                . h($marubatsu[$workstyleresult['workobject_other']]) . "</td>";
            echo "</tr>\n";
            echo "</table>";
            echo "<br>";
            echo "<table id=\"purposeofwork\">";
            echo "<caption style=\"text-align:left\">就労業種</caption>";
            echo "<tr>";
            echo "<th class=\"memberprof\">植木剪定</th>";
            echo "<th class=\"memberprof\">農業</th>";
            echo "<th class=\"memberprof\">清掃</th>";
            echo "<th class=\"memberprof\">家事代行</th>";
            echo "<th class=\"memberprof\">買い物代行</th>";
            echo "<th class=\"memberprof\">組立修理</th>";
            echo "<th class=\"memberprof\">子守介護</th>";
            echo "<th class=\"memberprof\">趣味教室</th>";
            echo "<th class=\"memberprof\">コンサルティング</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['worktype_prune']] . " "
                . $matchingparam['worktype_prune'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['worktype_agriculture']] . " "
                . $matchingparam['worktype_agriculture'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['worktype_cleaning']] . " "
                . $matchingparam['worktype_cleaning'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['worktype_housework']] . " "
                . $matchingparam['worktype_housework'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['worktype_shopping']] . " "
                . $matchingparam['worktype_shopping'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['worktype_repair']] . " "
                . $matchingparam['worktype_repair'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['worktype_caretaking']] . " "
                . $matchingparam['worktype_caretaking'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['worktype_teaching']] . " "
                . $matchingparam['worktype_teaching'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['worktype_consulting']] . " "
                . $matchingparam['worktype_consulting'] . "</td>";
            echo "</tr>\n";
            echo "</table>";
            echo "<br>";
            echo "<table id=\"purposeofwork\">";
            echo "<caption style=\"text-align:left\">学習・自己啓発・訓練</caption>";
            echo "<tr>";
            echo "<th class=\"memberprof\">英語</th>";
            echo "<th class=\"memberprof\">英語以外</th>";
            echo "<th class=\"memberprof\">情報処理</th>";
            echo "<th class=\"memberprof\">ビジネス</th>";
            echo "<th class=\"memberprof\">介護</th>";
            echo "<th class=\"memberprof\">家政</th>";
            echo "<th class=\"memberprof\">科学</th>";
            echo "<th class=\"memberprof\">芸術</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['study_english']] . " "
                . $matchingparam['study_english'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['study_foreignlanguage']] . " "
                . $matchingparam['study_foreignlanguage'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['study_it']] . " "
                . $matchingparam['study_it'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['study_business']] . " "
                . $matchingparam['study_business'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['study_caretaking']] . " "
                . $matchingparam['study_caretaking'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['study_housework']] . " "
                . $matchingparam['study_housework'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['study_liberalarts']] . " "
                . $matchingparam['study_liberalarts'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['study_art']] . " "
                . $matchingparam['study_art'] . "</td>";
            echo "</tr>\n";
            echo "</table>";
            echo "<br>";
            echo "<table id=\"purposeofwork\">";
            echo "<caption style=\"text-align:left\">ボランティア</caption>";
            echo "<tr>";
            echo "<th class=\"memberprof\">健康や医療</th>";
            echo "<th class=\"memberprof\">高齢者対象</th>";
            echo "<th class=\"memberprof\">障害者対象</th>";
            echo "<th class=\"memberprof\">子供対象</th>";
            echo "<th class=\"memberprof\">スポーツ文化</th>";
            echo "<th class=\"memberprof\">まちづくり</th>";
            echo "<th class=\"memberprof\">安全な生活</th>";
            echo "<th class=\"memberprof\">環境保全</th>";
            echo "<th class=\"memberprof\">災害</th>";
            echo "<th class=\"memberprof\">国際協力</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['volunteer_health']] . " "
                . $matchingparam['volunteer_health'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['volunteer_elderly']] . " "
                . $matchingparam['volunteer_elderly'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['volunteer_disable']] . " "
                . $matchingparam['volunteer_disable'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['volunteer_children']] . " "
                . $matchingparam['volunteer_children'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['volunteer_sport']] . " "
                . $matchingparam['volunteer_sport'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['volunteer_town']] . " "
                . $matchingparam['volunteer_town'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['volunteer_safety']] . " "
                . $matchingparam['volunteer_safety'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['volunteer_nature']] . " "
                . $matchingparam['volunteer_nature'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['volunteer_disaster']] . " "
                . $matchingparam['volunteer_disaster'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['volunteer_international']] . " "
                . $matchingparam['volunteer_international'] . "</td>";
            echo "</tr>\n";
            echo "</table>";
            echo "<br>";
            echo "<table id=\"purposeofwork\">";
            echo "<caption style=\"text-align:left\">趣味・娯楽</caption>";
            echo "<tr>";
            echo "<th class=\"memberprof\">楽器</th>";
            echo "<th class=\"memberprof\">コーラス声楽</th>";
            echo "<th class=\"memberprof\">舞踏ダンス</th>";
            echo "<th class=\"memberprof\">書道</th>";
            echo "<th class=\"memberprof\">華道</th>";
            echo "<th class=\"memberprof\">茶道</th>";
            echo "<th class=\"memberprof\">和裁洋裁</th>";
            echo "<th class=\"memberprof\">編み物手芸</th>";
            echo "<th class=\"memberprof\">料理菓子</th>";
            echo "<th class=\"memberprof\">園芸</th>";
            echo "<th class=\"memberprof\">日曜大工</th>";
            echo "<th class=\"memberprof\">絵画彫刻</th>";
            echo "<th class=\"memberprof\">陶芸工芸</th>";
            echo "<th class=\"memberprof\">写真</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_musicalinstrument']] . " "
                . $matchingparam['hobby_musicalinstrument'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_chorus']] . " "
                . $matchingparam['hobby_chorus'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_dance']] . " "
                . $matchingparam['hobby_dance'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_shodo']] . " "
                . $matchingparam['hobby_shodo'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_kado']] . " "
                . $matchingparam['hobby_kado'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_sado']] . " "
                . $matchingparam['hobby_sado'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_wasai']] . " "
                . $matchingparam['hobby_wasai'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_knit']] . " "
                . $matchingparam['hobby_knit'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_cooking']] . " "
                . $matchingparam['hobby_cooking'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_gardening']] . " "
                . $matchingparam['hobby_gardening'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_diy']] . " "
                . $matchingparam['hobby_diy'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_painting']] . " "
                . $matchingparam['hobby_painting'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_pottery']] . " "
                . $matchingparam['hobby_pottery'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_photo']] . " "
                . $matchingparam['hobby_photo'] . "</td>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<th class=\"memberprof\">執筆</th>";
            echo "<th class=\"memberprof\">囲碁将棋</th>";
            echo "<th class=\"memberprof\">キャンプ釣り</th>";
            echo "<th class=\"memberprof\">スポーツ観戦</th>";
            echo "<th class=\"memberprof\">演芸演劇鑑賞</th>";
            echo "<th class=\"memberprof\">映画鑑賞</th>";
            echo "<th class=\"memberprof\">音楽鑑賞</th>";
            echo "<th class=\"memberprof\">読書</th>";
            echo "<th class=\"memberprof\">ギャンブル</th>";
            echo "<th class=\"memberprof\">カラオケ</th>";
            echo "<th class=\"memberprof\">ゲーム</th>";
            echo "<th class=\"memberprof\">遊園地など</th>";
            echo "<th class=\"memberprof\">鉄道</th>";
            echo "<th class=\"memberprof\">車</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_writing']] . " "
                . $matchingparam['hobby_writing'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_go']] . " "
                . $matchingparam['hobby_go'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_camp']] . " "
                . $matchingparam['hobby_camp'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_watchsport']] . " "
                . $matchingparam['hobby_watchsport'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_watchperformance']] . " "
                . $matchingparam['hobby_watchperformance'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_watchmovie']] . " "
                . $matchingparam['hobby_watchmovie'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_listenmusic']] . " "
                . $matchingparam['hobby_listenmusic'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_reading']] . " "
                . $matchingparam['hobby_reading'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_pachinko']] . " "
                . $matchingparam['hobby_pachinko'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_karaoke']] . " "
                . $matchingparam['hobby_karaoke'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_game']] . " "
                . $matchingparam['hobby_game'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_attraction']] . " "
                . $matchingparam['hobby_attraction'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_train']] . " "
                . $matchingparam['hobby_train'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['hobby_car']] . " "
                . $matchingparam['hobby_car'] . "</td>";
            echo "</tr>\n";
            echo "</table>";
            echo "<br>";
            echo "<table id=\"purposeofwork\">";
            echo "<caption style=\"text-align:left\">旅行</caption>";
            echo "<tr>";
            echo "<th class=\"memberprof\">日帰り旅行</th>";
            echo "<th class=\"memberprof\">国内旅行(泊)</th>";
            echo "<th class=\"memberprof\">海外旅行</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['trip_daytrip']] . " "
                . $matchingparam['trip_daytrip'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['trip_domestic']] . " "
                . $matchingparam['trip_domestic'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['trip_international']] . " "
                . $matchingparam['trip_international'] . "</td>";
            echo "</tr>\n";
            echo "</table>";
            echo "<br>";
            echo "<table id=\"purposeofwork\">";
            echo "<caption style=\"text-align:left\">スポーツ</caption>";
            echo "<tr>";
            echo "<th class=\"memberprof\">野球</th>";
            echo "<th class=\"memberprof\">卓球</th>";
            echo "<th class=\"memberprof\">テニス</th>";
            echo "<th class=\"memberprof\">バドミントン</th>";
            echo "<th class=\"memberprof\">ゴルフ</th>";
            echo "<th class=\"memberprof\">ゲートボール</th>";
            echo "<th class=\"memberprof\">ボウリング</th>";
            echo "<th class=\"memberprof\">釣り</th>";
            echo "<th class=\"memberprof\">水泳</th>";
            echo "<th class=\"memberprof\">スキー</th>";
            echo "<th class=\"memberprof\">登山ハイキング</th>";
            echo "<th class=\"memberprof\">サイクリング</th>";
            echo "<th class=\"memberprof\">ジョギング</th>";
            echo "<th class=\"memberprof\">ウォーキング</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_baseball']] . " "
                . $matchingparam['sport_baseball'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_tabletennis']] . " "
                . $matchingparam['sport_tabletennis'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_tennis']] . " "
                . $matchingparam['sport_tennis'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_badminton']] . " "
                . $matchingparam['sport_badminton'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_golf']] . " "
                . $matchingparam['sport_golf'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_gateball']] . " "
                . $matchingparam['sport_gateball'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_bowling']] . " "
                . $matchingparam['sport_bowling'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_fishing']] . " "
                . $matchingparam['sport_fishing'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_swimming']] . " "
                . $matchingparam['sport_swimming'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_skiing']] . " "
                . $matchingparam['sport_skiing'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_climbing']] . " "
                . $matchingparam['sport_climbing'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_cycling']] . " "
                . $matchingparam['sport_cycling'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_jogging']] . " "
                . $matchingparam['sport_jogging'] . "</td>";
            echo "<td class=\"memberprof\">" . $marubatsublank[1
                + $socialactivityresult['sport_walking']] . " "
                . $matchingparam['sport_walking'] . "</td>";
            echo "</tr>\n";
            echo "</table>";


            //応募集状況
            echo "<br><br><h2>仕事/イベント</h2>";
            echo "<table id=\"basicprofile\">";
            echo "<tr>";
            echo "<th class=\"memberprof\">興味あり</th>";
            echo "<th class=\"memberprof\">興味なし</th>";
            echo "<th class=\"memberprof\">募集</th>";
            echo "<th class=\"memberprof\">詳細</th>";
            echo "</tr>\n";
            echo "<tr>";
            echo "<td class=\"memberprof\">" . $jobevent[1] . "</td>";
            echo "<td class=\"memberprof\">" . $jobevent[0] . "</td>";
            echo "<td class=\"memberprof\">" . $jobevent[2] . "</td>";
            echo "<td class=\"memberprof\"><a href=\"groupmemberrecord.php?groupno=0&userno="
                . $userno . "\" rel=\"external\">詳細</a></td>";
            echo "</tr>\n";
            echo "</table>";


            //グループ
            echo "<br><br><h2>グループ情報</h2>";
            echo "<table id=\"basicprofile\">";
            echo "<tr>";
            echo "<th class=\"memberprof\">グループ名</th>";
            echo "<th class=\"memberprof\">勤務件数</th>";
            echo "<th class=\"memberprof\">勤務日数</th>";
            echo "<th class=\"memberprof\">評価</th>";
            echo "<th class=\"memberprof\">詳細</th>";
            echo "</tr>\n";
            foreach ($records2 as $eachrecord) {
                echo "<tr>";
                echo "<td class=\"memberprof\">"
                    . $groupnamelist[$eachrecord['groupno']] . "</td>";
                echo "<td class=\"memberprof\">" . $eachrecord['workid'] . "</td>";
                echo "<td class=\"memberprof\">" . $eachrecord['workday'] . "</td>";
                echo "<td class=\"memberprof\">" . $evalarray[$eachrecord['eval']]
                    . "</td>";
                echo "<td class=\"memberprof\"><a href=\"groupmemberrecord.php?groupno="
                    . $eachrecord['groupno'] . "&userno=" . $userno
                    . "\" rel=\"external\">詳細</a></td>";
                echo "</tr>\n";
            }
            echo "</table>";


            echo "<br><br><br><br>";
        }//end of userselect=true

        else {

            $tablecounter = 1;
            echo "<table style=\"border-style: none; text-align: center\">";
            echo "<tr>";
            foreach ($userlist as $eachuser) {
                echo "<td><img src=\"./model/showuserimage.php?userno="
                    . $eachuser['userno']
                    . "\" onerror=\"this.src='img/noimage.svg';\" width=\"150px\" height=\"150px\"/></br><a href=\"skillprofile.php?userno="
                    . $eachuser['userno'] . "\" rel=\"external\">"
                    . h($eachuser['nickname']) . "</a></td>";
                $tablecounter++;
                if ($tablecounter == 6) {
                    $tablecounter = 1;
                    echo "</tr><tr>";
                }
            }
            while ($tablecounter < 6) {
                echo "<td></td>";
                $tablecounter++;
            }
            echo "</tr></table>";

        }

        ?>


    </div><!-- END OF CONTENT -->

    <?php include("./common/commonFooter.php"); ?>
    <script>
        $("[name='user-selector']").change(function () {
            location.href = baseurl + "skillprofile.php?userno=" + $(this).val();
        });
        setTimeout(function () {
            $("#navbar li:nth-child(1) a").click();
            autosize.update(autosizebox);
        }, 100);
    </script>
</div>
</body>
</html>
