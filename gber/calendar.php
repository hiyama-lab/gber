<?php
require_once __DIR__ . '/lib/auth.php';
require_logined_session();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
</head>
<body>

<div data-role="page" id="mypage">
    <?php
    require_once __DIR__ . '/lib/db.php';

    $userno = $_GET['userno'];
    $db = DB::getInstance();
    $db->addToActivityLog($userno, "calendar.php?userno=$userno");

    $useridlist = array();
    $useridlist[] = $_SESSION['userno'];

    //ユーザIDが、自分のもの、もしくは被世話人のものであることを確認する
    $viewcalendarflag = false;
    if ($userno == $_SESSION['userno']) {
        $viewcalendarflag = true;
    }
//    $result
//        = mysql_query("SELECT DISTINCT taker FROM caretakerlist WHERE giver='"
//        . $_SESSION['userno'] . "'") or die ("Query error: " . mysql_error());
//    while ($row = mysql_fetch_assoc($result)) {
//        $useridlist[] = $row['taker'];
//        if ($userno == $row['taker']) {
//            $viewcalendarflag = true;
//        }
//    }
    if (!$viewcalendarflag) {
        die("IDが異なるため操作できません");
    }

    $userlist = array();
    $nickname = "";
    foreach ($useridlist as $eachuserid) {
        $row2 = $db->findUserById($userno);
        $userlist[] = $row2;
        if ($row2['userno'] == $userno) {
            $nickname = $row2['nickname'];
        }
    }

    $now_year = date("Y"); // 現在の年を取得．西暦
    $now_month = date("n"); // 現在の月を取得．0をつけない
    if ($now_month + 1 > 12) {
        $next_month = 1;
        $next_year = $now_year + 1;
    } else {
        $next_month = $now_month + 1;
        $next_year = $now_year;
    }

    // 今月のスケジュール取得
    $records3 = $db->getSchedule($userno, $now_year, $now_month);
    if (count($records3) == 0) { // スケジュールに載ってなかったら挿入して取得する
        $db->addSchedule($userno, $now_year, $now_month);
        $records3 = $db->getSchedule($userno, $now_year, $now_month);
    }

    // 来月のスケジュール取得
    $records5 = $db->getSchedule($userno, $next_year, $next_month);
    if (count($records5) == 0) { // スケジュールに載ってなかったら挿入して取得する
        $db->addSchedule($userno, $next_year, $next_month);
        $records5 = $db->getSchedule($userno, $next_year, $next_month);
    }
    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <?php
        if (count($userlist) > 1) {
            echo "ユーザー切替　<select data-role=\"none\" name=\"user-selector\" id=\"user-selector\">";
            foreach ($userlist as $eachuser) {
                echo "<option value=\"" . $eachuser['userno'] . "\">"
                    . $eachuser['nickname'] . "</option>";
            }
            echo "</select>";
        }
        ?>
        <h3><a href="mypage.php?userno=<?php echo $userno; ?>"
               rel="external"><?php echo $nickname; ?></a>の予定</h3>
        <p>予定を登録すると、グループの仕事やスカウトを受けられるようになります。</p>
        <p>※◯:終日OK、△:午前OK、▼:午後OK、×:不可</p>

        <?php
        $now_day = date("j"); // 現在の日を取得．0をつけない
        $weekday = array("日", "月", "火", "水", "木", "金", "土");
        $fir_weekday = date("w", mktime(0, 0, 0, $now_month, 1, $now_year));
        $fir_weekday_next = date("w",
            mktime(0, 0, 0, $next_month, 1, $next_year));

        $mark = array("×", "◯", "△", "▼");

        echo "<table class=\"smallcalender\" style=\"text-align:center;\">\n";
        echo "<caption style=\"color:black; font-size:14px; padding:0px;\">"
            . $now_year . "年" . $now_month . "月</caption>\n<tr>\n";

        // 曜日セル<th>タグ設定
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
            $i++; //カウント値+1
        }
        echo "</tr>\n<tr>\n";

        //１日の曜日まで空白（&nbsp;）で埋める
        $i = 0;
        while ($i != $fir_weekday) {
            echo "\t<td>&nbsp;</td>\n";
            $i++;
        }

        // 今月の日付が存在している間ループする
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
                                            && $records3[0]["d" . $j . "_pm"] == 1
                                        ) {
                                            $d_value = 3;
                                        }
                                    }
                                }
                            }
                            echo "\t<td id=\"day_" . $j
                                . "\"><input data-role=\"none\" type=\"button\" id=\"button_d"
                                . $j . "\" value=\"" . $mark[$d_value]
                                . "\" onClick=\"switchMark('d" . $j
                                . "',value,$now_year,$now_month,'');\" /></td>\n";
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

            $i++; //カウント値（曜日カウンター）+1
        }
        $isave = $i;

        while ($i < 7) { //残りの曜日分空白（&nbsp;）で埋める
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
                    echo "\t<td id=\"day_" . $j
                        . "\"><input data-role=\"none\" type=\"button\" id=\"button_d"
                        . $j . "\" value=\"" . $mark[$d_value]
                        . "\" onClick=\"switchMark('d" . $j
                        . "',value,$now_year,$now_month,'');\" /></td>\n";
                } else {
                    echo "\t<td>&nbsp;</td>\n";
                }
            }
        }

        echo "</tr>\n</table>\n</br>\n";

        // ここから来月分！
        echo "<table class=\"smallcalender\" style=\"text-align:center;\">\n";
        echo "<caption style=\"color:black; font-size:14px; padding:0px;\">"
            . $next_year . "年" . $next_month . "月</caption>\n<tr>\n";
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
        for ($day = 1; checkdate($next_month, $day, $next_year); $day++) {
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
                        echo "\t<td id=\"next_day_" . $j
                            . "\"><input data-role=\"none\" type=\"button\" id=\"button_next_d"
                            . $j . "\" value=\"" . $mark[$d_value]
                            . "\" onClick=\"switchMark('d" . $j
                            . "',value,$next_year,$next_month,'next_');\" /></td>\n";
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
            if (checkdate($next_month, $j, $next_year)) {
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
                echo "\t<td id=\"next_day_" . $j
                    . "\"><input data-role=\"none\" type=\"button\" id=\"button_next_d"
                    . $j . "\" value=\"" . $mark[$d_value]
                    . "\" onClick=\"switchMark('d" . $j
                    . "',value,$next_year,$next_month,'next_');\" /></td>\n";
            } else {
                echo "\t<td>&nbsp;</td>\n";
            }
        }
        echo "</tr>\n</table>\n";
        ?>

    </div>

    <!-- 下記スクリプトはajaxでスケジュール変更するために使用 -->
    <script type="text/javascript">
        var workerno = <?php echo $userno; ?>;
        var hiduke = new Date();
        var year = hiduke.getFullYear();
        var month = ("0" + String(hiduke.getMonth() + 1)).slice(-2);
        var day = ("0" + hiduke.getDate()).slice(-2);
        var thisday = year + "-" + month + "-" + day;
    </script>
    <?php include("./common/commonFooter.php"); ?>
    <script type="text/javascript">
        function switchMark(inputid, value, year, month, buttonpre) {
            var upload_num = 0;
            if (value == "×") {
                $("input#button_" + buttonpre + inputid).val("◯");
                upload_num = 1;
            }
            else if (value == "◯") {
                $("input#button_" + buttonpre + inputid).val("△");
                upload_num = 2;
            }
            else if (value == "△") {
                $("input#button_" + buttonpre + inputid).val("▼");
                upload_num = 3;
            }
            else if (value == "▼") {
                $("input#button_" + buttonpre + inputid).val("×");
                upload_num = 0;
            }
            var JSONdata = {
                userno: workerno,
                inputid: inputid,
                upload_num: upload_num,
                year: year,
                month: month,
                lastupdated: thisday
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(JSONdata),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/editSchedule.php',
                timeout: 10000,
            })
        }

        $("[name='user-selector']").val("<?php echo $userno; ?>");
        $("[name='user-selector']").change(function () {
            location.href = baseurl + "calendar.php?userno=" + $(this).val();
        });
    </script>
</div>
</body>
</html>
