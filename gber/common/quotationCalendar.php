<?php

//****** カレンダーUI開始 ******//
echo "</br>\n";

echo "<h4>" . $now_year . "年" . $now_month . "月のカレンダー</h4>";

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
    echo "\t<th style=\"color:" . $style . "\" colspan=\"2\">" . $weekday[$i]
        . "</th>\n";
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
                    echo "\t<td colspan=\"2\" id=\"job_day_" . sprintf('%02d', $j)
                        . "\">";
                    foreach ($calendar as $eachrecord) {
                        if ($eachrecord['active'] == 1
                            && $eachrecord['d' . $j . '_am'] == 1
                            && $eachrecord['d' . $j . '_pm'] == 1
                        ) {
                            echo "<span class=\"user" . $eachrecord['userno']
                                . "\">" . $nicknamelist[$eachrecord['userno']]
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
                    foreach ($calendar as $eachrecord) {
                        if ($eachrecord['active'] == 1
                            && $eachrecord['d' . $j . '_am'] == 1
                            && $eachrecord['d' . $j . '_pm'] == 0
                        ) {
                            echo "<span class=\"user" . $eachrecord['userno']
                                . "\">" . $nicknamelist[$eachrecord['userno']]
                                . "</span></br>";
                        }
                    }
                    echo "</td>\n";
                    // 次に午後を表示
                    echo "\t<td id=\"day_" . $j . "_pm\">";
                    foreach ($calendar as $eachrecord) {
                        if ($eachrecord['active'] == 1
                            && $eachrecord['d' . $j . '_am'] == 0
                            && $eachrecord['d' . $j . '_pm'] == 1
                        ) {
                            echo "<span class=\"user" . $eachrecord['userno']
                                . "\">" . $nicknamelist[$eachrecord['userno']]
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

    echo "\t<td style=\"color:" . $style . ";\" colspan=\"2\">" . $day . "</td>\n";

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
            echo "\t<td colspan=\"2\" id=\"job_day_" . sprintf('%02d', $j) . "\">";
            foreach ($calendar as $eachrecord) {
                if ($eachrecord['active'] == 1 && $eachrecord['d' . $j . '_am'] == 1
                    && $eachrecord['d' . $j . '_pm'] == 1
                ) {
                    echo "<span class=\"user" . $eachrecord['userno'] . "\">"
                        . $nicknamelist[$eachrecord['userno']] . "</span></br>";
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
            foreach ($calendar as $eachrecord) {
                if ($eachrecord['active'] == 1 && $eachrecord['d' . $j . '_am'] == 1
                    && $eachrecord['d' . $j . '_pm'] == 0
                ) {
                    echo "<span class=\"user" . $eachrecord['userno'] . "\">"
                        . $nicknamelist[$eachrecord['userno']] . "</span></br>";
                }
            }
            echo "</td>\n";
            // 次に午後を表示
            echo "\t<td id=\"day_" . $j . "_pm\">";
            foreach ($calendar as $eachrecord) {
                if ($eachrecord['active'] == 1 && $eachrecord['d' . $j . '_am'] == 0
                    && $eachrecord['d' . $j . '_pm'] == 1
                ) {
                    echo "<span class=\"user" . $eachrecord['userno'] . "\">"
                        . $nicknamelist[$eachrecord['userno']] . "</span></br>";
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


echo "<h4>" . $next_month_year . "年" . $next_month . "月のカレンダー</h4>";

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
    echo "\t<th style=\"color:" . $style . "\" colspan=\"2\">" . $weekday[$i]
        . "</th>\n";
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
for ($day = 1; checkdate($next_month, $day, $next_month_year); $day++) {
    if ($i > 6) {
        $i = 0;
        // 終日空いている人を表示する
        echo "</tr>\n<tr>\n";
        for ($j = $day - 7; $j < $day; $j++) {
            if ($j < 1) {
                echo "\t<td colspan=\"2\">&nbsp;</td>\n";
            } else {
                echo "\t<td colspan=\"2\" id=\"next_job_day_" . sprintf('%02d',
                        $j) . "\">";
                foreach ($calendar as $eachrecord) {
                    if ($eachrecord['next_active'] == 1
                        && $eachrecord['next_d' . $j . '_am'] == 1
                        && $eachrecord['next_d' . $j . '_pm'] == 1
                    ) {
                        echo "<span class=\"user" . $eachrecord['userno'] . "\">"
                            . $nicknamelist[$eachrecord['userno']]
                            . "</span></br>";
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
                foreach ($calendar as $eachrecord) {
                    if ($eachrecord['next_active'] == 1
                        && $eachrecord['next_d' . $j . '_am'] == 1
                        && $eachrecord['next_d' . $j . '_pm'] == 0
                    ) {
                        echo "<span class=\"user" . $eachrecord['userno'] . "\">"
                            . $nicknamelist[$eachrecord['userno']]
                            . "</span></br>";
                    }
                }
                echo "</td>\n";
                // 次に午後を表示
                echo "\t<td id=\"next_day_" . $j . "_pm\">";
                foreach ($calendar as $eachrecord) {
                    if ($eachrecord['next_active'] == 1
                        && $eachrecord['next_d' . $j . '_am'] == 0
                        && $eachrecord['next_d' . $j . '_pm'] == 1
                    ) {
                        echo "<span class=\"user" . $eachrecord['userno'] . "\">"
                            . $nicknamelist[$eachrecord['userno']]
                            . "</span></br>";
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

    echo "\t<td style=\"color:" . $style . ";\" colspan=\"2\">" . $day . "</td>\n";

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
        echo "\t<td colspan=\"2\" id=\"next_job_day_" . sprintf('%02d', $j) . "\">";
        foreach ($calendar as $eachrecord) {
            if ($eachrecord['next_active'] == 1
                && $eachrecord['next_d' . $j . '_am'] == 1
                && $eachrecord['next_d' . $j . '_pm'] == 1
            ) {
                echo "<span class=\"user" . $eachrecord['userno'] . "\">"
                    . $nicknamelist[$eachrecord['userno']] . "</span></br>";
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
        foreach ($calendar as $eachrecord) {
            if ($eachrecord['next_active'] == 1
                && $eachrecord['next_d' . $j . '_am'] == 1
                && $eachrecord['next_d' . $j . '_pm'] == 0
            ) {
                echo "<span class=\"user" . $eachrecord['userno'] . "\">"
                    . $nicknamelist[$eachrecord['userno']] . "</span></br>";
            }
        }
        echo "</td>\n";
        // 次に午後を表示
        echo "\t<td id=\"next_day_" . $j . "_pm\">";
        foreach ($calendar as $eachrecord) {
            if ($eachrecord['next_active'] == 1
                && $eachrecord['next_d' . $j . '_am'] == 0
                && $eachrecord['next_d' . $j . '_pm'] == 1
            ) {
                echo "<span class=\"user" . $eachrecord['userno'] . "\">"
                    . $nicknamelist[$eachrecord['userno']] . "</span></br>";
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