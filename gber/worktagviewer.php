<?php
require_once __DIR__ . '/lib/sessionUtil.php';
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
    include __DIR__ . '/lib/mysql_credentials.php';

    // マスター権限がないとこれは見せない

    $groupno = $_GET['groupno'];
    $workid = $_GET['workid'];

    if ($_SESSION['userno'] != 1) {
        echo "閲覧権限がありません";
        exit;
    }

    if ($groupno != "" && $workid != "") {

        $activitylog
            = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
            . $_SESSION['userno'] . "', 'worktagviewer.php?groupno=" . $groupno
            . "&workid=" . $workid . "', '" . date('Y-m-d G:i:s') . "')", $con)
        or die('Error: ' . mysql_error());

//スキル情報
        $matchingparamquery
            = mysql_query("SELECT * FROM matchingparam_work WHERE groupno = '"
            . $groupno . "' and workid = '" . $workid . "'") or die ("Query error: "
            . mysql_error());
        $matchingparam = mysql_fetch_assoc($matchingparamquery);

        if ($groupno == 0) {
            $result = mysql_query("SELECT DISTINCT * FROM helplist WHERE id='"
                . $workid . "'") or die ("Query error: " . mysql_error());
            if (mysql_num_rows($result) == 0) {
                echo "無効な募集IDです";
                exit;
            }
            $record = mysql_fetch_assoc($result);
        } else {
            $result = mysql_query("SELECT DISTINCT * FROM worklist WHERE id = $workid") or die ("Query error: "
                . mysql_error());
            if (mysql_num_rows($result) == 0) {
                die("データベースに存在しない案件です");
            }
            $record = mysql_fetch_assoc($result);
        }

    }

    $worklistresult
        = mysql_query("SELECT groupno,workid FROM matchingparam_work ORDER BY groupno")
    or die ("Query error: " . mysql_error());
    $worklist = array();
    while ($row = mysql_fetch_assoc($worklistresult)) {
        $worklist[] = $row;
    }

    mysql_close($con);

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>
    <!-- CONTENT -->
    <div data-role="content">


        <?php

        echo "仕事切替　<select data-role=\"none\" name=\"work-selector\" id=\"work-selector\">";
        echo "<option value=\"\" >仕事を選択してください</option>";
        echo "<option value=\"\" >仕事一覧</option>";
        foreach ($worklist as $eachwork) {
            echo "<option value=\"" . $eachwork['groupno'] . "," . $eachwork['workid']
                . "\">[" . $groupnamelist[$eachwork['groupno']] . "] "
                . $eachwork['workid'] . "</option>";
        }
        echo "</select><br><br>";


        echo "<h1>" . $record['worktitle'] . "</h1>";

        //定性的紹介文
        if ($record['content'] == "") {
            $record['content'] = "記載なし";
        }
        echo "<p>" . $record['content'] . "</p>\n";

        //場所
        echo "<br><br><h2>場所</h2>";
        echo "<table id=\"preferredworkplace\">";
        echo "<caption style=\"text-align:left\">募集位置</caption>";
        echo "<tr>";
        echo "<th class=\"memberprof\">緯度</th>";
        echo "<th class=\"memberprof\">経度</th>";
        echo "</tr>\n";
        echo "<tr>";
        echo "<td class=\"memberprof\">" . $record['lat'] . "</td>";
        echo "<td class=\"memberprof\">" . $record['lng'] . "</td>";
        echo "</tr>\n";
        echo "</table>";
        echo "<br>";

        //スキル
        echo "<br><br><h2>スキル</h2>";
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
        echo "<td class=\"memberprof\">" . $matchingparam['worktype_prune']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['worktype_agriculture']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['worktype_cleaning']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['worktype_housework']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['worktype_shopping']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['worktype_repair']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['worktype_caretaking']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['worktype_teaching']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['worktype_consulting']
            . "</td>";
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
        echo "<td class=\"memberprof\">" . $matchingparam['study_english']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['study_foreignlanguage']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['study_it'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['study_business']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['study_caretaking']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['study_housework']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['study_liberalarts']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['study_art'] . "</td>";
        echo "</tr>\n";
        echo "</table>";
        echo "<br>";
        echo "<table id=\"purposeofwork\">";
        echo "<caption style=\"text-align:left\">地域活動</caption>";
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
        echo "<td class=\"memberprof\">" . $matchingparam['volunteer_health']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['volunteer_elderly']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['volunteer_disable']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['volunteer_children']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['volunteer_sport']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['volunteer_town']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['volunteer_safety']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['volunteer_nature']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['volunteer_disaster']
            . "</td>";
        echo "<td class=\"memberprof\">"
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
        echo "<td class=\"memberprof\">"
            . $matchingparam['hobby_musicalinstrument'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_chorus'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_dance'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_shodo'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_kado'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_sado'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_wasai'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_knit'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_cooking']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_gardening']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_diy'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_painting']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_pottery']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_photo'] . "</td>";
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
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_writing']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_go'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_camp'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_watchsport']
            . "</td>";
        echo "<td class=\"memberprof\">"
            . $matchingparam['hobby_watchperformance'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_watchmovie']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_listenmusic']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_reading']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_pachinko']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_karaoke']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_game'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_attraction']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_train'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['hobby_car'] . "</td>";
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
        echo "<td class=\"memberprof\">" . $matchingparam['trip_daytrip'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['trip_domestic']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['trip_international']
            . "</td>";
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
        echo "<td class=\"memberprof\">" . $matchingparam['sport_baseball']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_tabletennis']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_tennis'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_badminton']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_golf'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_gateball']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_bowling']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_fishing']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_swimming']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_skiing'] . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_climbing']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_cycling']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_jogging']
            . "</td>";
        echo "<td class=\"memberprof\">" . $matchingparam['sport_walking']
            . "</td>";
        echo "</tr>\n";
        echo "</table>";


        echo "<br><br><br><br>";
        ?>


    </div><!-- END OF CONTENT -->
    <script>
        $("[name='work-selector']").change(function () {
            var splittedstr = $(this).val().split(",");
            location.href = baseurl + "worktagviewer.php?groupno=" + splittedstr[0] + "&workid=" + splittedstr[1];
        });
    </script>
    <?php include("./common/commonFooter.php"); ?>
</div>
</body>
</html>
