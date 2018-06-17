<?php
require_once __DIR__ . '/lib/auth.php';
require_logined_session();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <!--入力カレンダー用-->
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css"/>
    <script type="text/javascript" src="js/calendar/jquery-ui.min.js"></script>
    <script type="text/javascript"
            src="js/calendar/jquery-ui-i18n.min.js"></script>
    <!-- 評価の星をつける用 -->
    <link rel="stylesheet" type="text/css" href="js/rateit/rateit.css"/>
    <script type="text/javascript"
            src="js/rateit/jquery.rateit.min.js"></script>
    <script type="text/javascript"
            src="js/editable/jquery.editable.min.js"></script>
    <!-- テーブルソート用 -->
    <link rel="stylesheet" type="text/css" href="js/tablesorter/style.css"/>
    <script type="text/javascript"
            src="js/tablesorter/jquery.tablesorter.min.js"></script>
    <!-- インライン編集用 -->
    <script type="text/javascript"
            src="js/editable/jquery.editable.min.js"></script>
    <!-- 地図スタイル -->
    <script src="js/googlemap_style.json"></script>
</head>
<body>
<div data-role="page" data-url="map-page">
    <style>
        .ui-widget-content {
            border: none;
        }
    </style>

    <!-- このファイルは職能集団の見積書用に使用する -->

    <?php
    include __DIR__ . '/lib/mysql_credentials.php';
    require_once __DIR__ . '/lib/db.php';
    require_once __DIR__ . '/model/calcMatch.php';

    //高速化のために、不必要なPHPのローディングは消す

    $workid = $_GET['workid'];
    $today = date("Y-m-d");
    $groupno = $_GET['groupno'];

    function calcDistance($x1, $y1, $x2, $y2)
    {
        $radius = 6367000;
        $dx = ($x2 - $x1) * 3.1415 / 180;
        $dy = ($y2 - $y1) * 3.1415 / 180;
        $distance = $radius * sqrt(pow($dx, 2) + pow($dy, 2));

        return floor($distance);
    }

    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'quotation.php?groupno=" . $groupno . "&workid="
        . $workid . "', '" . date('Y-m-d G:i:s') . "')", $con) or die('Error: '
        . mysql_error());


    // 仕事の詳細を取得し$records2に登録
    $result2 = mysql_query("SELECT * FROM worklist WHERE id = $workid") or die ("Query error: " . mysql_error());
    if (mysql_num_rows($result2) == 0) {
        die("データベースに存在しない案件です");
    }
    $records2 = array();
    while ($row2 = mysql_fetch_assoc($result2)) {
        $records2[] = $row2;
    }


    // 閲覧者が管理人なら$vieweradmin=1,グループメンバーなら$groupmember=1。0,0で依頼者でもなければexit;
    $admincheck
        = mysql_query("SELECT admin, registeredid FROM grouplist WHERE groupno = '"
        . $groupno . "' and userno = '" . $_SESSION['userno'] . "'")
    or die ("Query error: " . mysql_error());
    $admincheckassoc = mysql_fetch_assoc($admincheck);
    if ($admincheckassoc['admin'] == 1) {
        $vieweradmin = 1;
        $groupmember = 1;
    } else {
        if ($admincheckassoc['registeredid'] > 0) {
            $vieweradmin = 0;
            $groupmember = 1;
        } else {
            if ($_SESSION['userno'] == $records2[0]['userno']) {
                $vieweradmin = 0;
                $groupmember = 0;
            } else {
                echo "グループに所属していないため閲覧権限がありません。";
                exit;
            }
        }
    }


    //****** グループ構成員のプロフィール ******//
    $result
        = mysql_query("SELECT userno,mail,phone,nickname,gender,birthyear,mylat,mylng FROM db_user WHERE userno IN (SELECT DISTINCT userno FROM grouplist WHERE groupno = '"
        . $groupno . "' ORDER BY userno)") or die ("Query error: " . mysql_error());
    $records = array();
    while ($row = mysql_fetch_assoc($result)) {
        $records[] = $row;
    }

    $nicknamelist = array();
    foreach ($records as $eachrecord) {
        $nicknamelist[$eachrecord['userno']] = $eachrecord['nickname'];
    }
    // グループ構成員のスケジュールを$calendarに登録する
    if ($vieweradmin) {
        $calendar = array();
        foreach ($records as $eachrecord) {
            $calendar[$eachrecord['userno']]['userno'] = $eachrecord['userno'];
            $calendar[$eachrecord['userno']]['active'] = 0;
            $calendar[$eachrecord['userno']]['next_active'] = 0;
            for ($j = 1; $j < 32; $j++) {
                $calendar[$eachrecord['userno']]["lastupdated"] = "2000-01-01";
                $calendar[$eachrecord['userno']]["next_lastupdated"]
                    = "2000-01-01";
                $calendar[$eachrecord['userno']]["d" . $j . "_am"] = 0;
                $calendar[$eachrecord['userno']]["d" . $j . "_pm"] = 0;
                $calendar[$eachrecord['userno']]["next_d" . $j . "_am"] = 0;
                $calendar[$eachrecord['userno']]["next_d" . $j . "_pm"] = 0;
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
            = mysql_query("SELECT * FROM schedule WHERE userno IN (SELECT DISTINCT userno FROM grouplist WHERE groupno=$groupno ORDER BY userno) AND year=$now_year AND month=$now_month")
        or die ("Query error: " . mysql_error());
        while ($row3 = mysql_fetch_assoc($result3)) {
            $calendar[$row3['userno']]["active"] = 1;
            $calendar[$row3['userno']]["lastupdated"] = $row3["lastupdated"];
            for ($j = 1; $j < 32; $j++) {
                if ($row3["d" . $j . "_am"] == 1) {
                    $calendar[$row3['userno']]["d" . $j . "_am"] = 1;
                }
                if ($row3["d" . $j . "_pm"] == 1) {
                    $calendar[$row3['userno']]["d" . $j . "_pm"] = 1;
                }
            }
        }
        $result5
            = mysql_query("SELECT * FROM schedule WHERE userno IN (SELECT DISTINCT userno FROM grouplist WHERE groupno=$groupno ORDER BY userno) AND year=$next_month_year AND month=$next_month")
        or die ("Query error: " . mysql_error());
        while ($row5 = mysql_fetch_assoc($result5)) {
            //予定表を開くと翌月までのスケジュールが登録されるので，スケジュールTableに載っている＝1ヶ月〜2ヶ月以内にログインしている
            $calendar[$row5['userno']]["next_active"] = 1;
            $calendar[$row5['userno']]["next_lastupdated"]
                = $row5["lastupdated"];
            for ($j = 1; $j < 32; $j++) {
                if ($row5["d" . $j . "_am"] == 1) {
                    $calendar[$row5['userno']]["next_d" . $j . "_am"] = 1;
                }
                if ($row5["d" . $j . "_pm"] == 1) {
                    $calendar[$row5['userno']]["next_d" . $j . "_pm"] = 1;
                }
            }
        }
    }

    //****** 依頼者の評価ボタンを表示するために，ワーカー一覧を取得する＋依頼者情報 ******//
    //****** 評価段階(ステータス3)or結果表示段階(ステータス4)の時のみ取得 ******//
    if ($records2[0]['status'] == 3 || $records2[0]['status'] == 4) {
        $result8 = mysql_query("SELECT * FROM workeval WHERE workid='" . $workid . "' ORDER BY workerno")
        or die ("Query error: " . mysql_error());
        $records8 = array();
        while ($row8 = mysql_fetch_assoc($result8)) {
            $records8[] = $row8;
        }
        //クライアント情報を取得する
        $result20 = mysql_query("SELECT * FROM clientinfo WHERE workid='" . $workid . "'") or die ("Query error: "
            . mysql_error());
        $clientcomment = "";
        if (mysql_num_rows($result20) == 1) {
            $row20 = mysql_fetch_assoc($result20);
            $clientcomment = $row20['comment'];
        }
    }

    //****** 確定済みのワーカーを表示する．ニックネームも統合する ******//
    //これは常に検索
    $result10 = mysql_query("SELECT * FROM workdate WHERE workid='"
        . $workid . "' and status='1' ORDER BY workday") or die ("Query error: "
        . mysql_error());
    $records10 = array();
    while ($row10 = mysql_fetch_assoc($result10)) {
        $records10[] = $row10;
    }

    //****** グループメンバーのみ。承諾ボタン，棄却ボタンを表示する。世話人の分も合わせて表示する。******//
    if ($groupmember == 1) {
        if ($vieweradmin) {
            $result7 = mysql_query("SELECT * FROM workdate WHERE workid='" . $workid . "' ORDER BY workday")
            or die ("Query error: " . mysql_error());
        } else {
            $result7 = mysql_query("SELECT * FROM workdate WHERE workid='" . $workid . "' AND (workerno='"
                . $_SESSION['userno']
                . "' OR workerno IN (SELECT taker FROM caretakerlist WHERE giver = "
                . $_SESSION['userno'] . ")) ORDER BY workday")
            or die ("Query error: " . mysql_error());
        }
        $records7 = array();
        while ($row7 = mysql_fetch_assoc($result7)) {
            $records7[] = $row7;
        }
    }


    // グループ構成員の評価と参加希望を$recordsに統合する
    if ($vieweradmin) {
        $result12
            = mysql_query("SELECT eval,memo,userno FROM grouplist WHERE groupno = '"
            . $groupno . "'") or die ("Query error: " . mysql_error());
        while ($row12 = mysql_fetch_assoc($result12)) {
            $i = 0;
            foreach ($records as $eachrecord) {
                if ($eachrecord['userno'] == $row12['userno']) {
                    $records[$i]['eval'] = $row12['eval'];
                    $records[$i]['memo'] = $row12['memo'];
                    $records[$i]['accumulation'] = "0.0";
                    $records[$i]['interest'] = 0;
                    $records[$i]['comment'] = "";
                    break;
                }
                $i++;
            }
        }
        $result1200 = mysql_query("SELECT workerno,SUM(worktime) FROM workdate LEFT JOIN worklist ON workdate.workid=worklist.id WHERE worklist.groupno=$groupno AND workdate.workday>DATE_SUB(CURRENT_DATE(),interval 30 day) GROUP BY workerno ORDER BY workerno")
        or die ("Query error: " . mysql_error());
        while ($row1200 = mysql_fetch_assoc($result1200)) {
            $i = 0;
            foreach ($records as $eachrecord) {
                if ($eachrecord['userno'] == $row1200['workerno']) {
                    $records[$i]['accumulation'] = $row1200['SUM(worktime)'];
                }
                $i++;
            }
        }
    }

    $result120 = mysql_query("SELECT userno,interest,comment FROM workinterest WHERE workid = '" . $workid . "'") or die ("Query error: "
        . mysql_error());
    while ($row120 = mysql_fetch_assoc($result120)) {
        $i = 0;
        foreach ($records as $eachrecord) {
            if ($eachrecord['userno'] == $row120['userno']) {
                if ($row120['interest']) {
                    $records[$i]['interest'] = 1;
                } else {
                    $records[$i]['interest'] = -1;
                }
                $records[$i]['comment'] = $row120['comment'];
                break;
            }
            $i++;
        }
    }

    if($vieweradmin){
        $db = DB::getInstance();
        $workp = $db->getMatchingParamByWorkid($workid);
        $matching_enabled = false;
        foreach ($records as &$user) {
            // マッチングパラメータを計算
            $userp = $db->getMatchingParamByUserno($user['userno']);
            // 興味ベクトルの大きさが0のユーザはマッチングしない
            $user["match"] = calcMatch($userp, $workp);
        }
        unset($user);

        // ORDER BY 参加希望(あり->未定義->なし) マッチング係数(正の数->未定義->負の数)
        foreach ($records as $key => $row) {
            $interest[$key]  = $row['interest'];
            $match[$key] = $row['match'];
        }
        array_multisort($interest, SORT_DESC, $match, SORT_DESC, $records);
    }

    // 日報が全て記入済みなら、仕事終了処理に移行できる
    // ステータス2で管理者が見てるなら、断った人一覧を表示
    if ($records2[0]['status'] == 2 && $vieweradmin) {
        $evalfinishedflag = false;
        $result15 = mysql_query("SELECT evalid FROM workeval WHERE workid='" . $workid . "' and selfeval='0'")
        or die ("Query error: " . mysql_error());
        if (mysql_num_rows($result15) == 0) {
            $evalfinishedflag = true;
        }
        $numberdeclined = 0;
        $records111 = array();
        $result111 = mysql_query("SELECT * FROM workcancel WHERE workid='" . $workid . "'") or die ("Query error: "
            . mysql_error());
        $numberdeclined = mysql_num_rows($result111);
        while ($row111 = mysql_fetch_assoc($result111)) {
            $records111[] = $row111;
        }
    }

    // ステータス2でグループメンバーが閲覧しているなら
    $answeredinterest = 0;
    if ($records2[0]['status'] == 2 && $groupmember) {
        $evaluatedflag = false;
        $result30 = mysql_query("SELECT interest,comment FROM workinterest WHERE workid='" . $workid . "' and userno='"
            . $_SESSION['userno'] . "'") or die ("Query error: " . mysql_error());
        if (mysql_num_rows($result30) > 0) {
            $evaluatedflag = true;
        }
        $row30 = mysql_fetch_assoc($result30);
        $answeredinterest = $row30['interest'];
        $answeredcomment = $row30['comment'];
    }

    //依頼画面に依頼済みの人を取得する
    $result6 = mysql_query("SELECT * FROM workdate WHERE workid='"
        . $workid . "'") or die ("Query error: " . mysql_error());
    $records6 = array();
    while ($row6 = mysql_fetch_assoc($result6)) {
        $records6[] = $row6;
    }
    $weekday = array('日', '月', '火', '水', '木', '金', '土');


    mysql_close($con);
    ?>

    <script type="text/javascript">
        //管理者が見ている時は、javascriptにスケジュール情報を渡す
        <?php if ($vieweradmin == 1) {
            echo "var workerschedule = " . json_encode($calendar) . ";";
        } ?>
        // 緯度経度をjavascriptに渡して，後でマップに使う
        var work_detail = <?php echo json_encode($records2); ?>;
        var worker_candidate = <?php echo json_encode($records6); ?>;
        var sendoffergroupno = '<?php echo $_GET['groupno']; ?>';
    </script>


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>


    <!-- CONTENT -->
    <div data-role="content">
        <?php


        //【ステータス5】DONE
        //削除済みなら削除済みと表示
        if ($records2[0]['status'] == 5) {
            echo "<p>削除済</p>";
            exit;
        }













        //【ステータス4】
        //評価まで全て終わっていたら業務レポートを表示する．
        else {
            if ($records2[0]['status'] == 4) {

                //管理者には全機能を開放
                if ($vieweradmin == 1) {
                    echo "<h2 id=\"worktitle\">" . h($records2[0]['worktitle'])
                        . "【終了】</h2>";
                    if ($records2[0]['content'] == "") {
                        $records2[0]['content'] = "仕事詳細が空欄です";
                    }
                    $records2[0]['content'] = nl2br(h($records2[0]['content']));
                    echo "<p id=\"content\">" . $records2[0]['content'] . "</p>";
                    if ($records2[0]['workdatetime'] == "") {
                        $records2[0]['workdatetime'] = "勤務予定日時が空欄です";
                    }
                    $records2[0]['workdatetime']
                        = nl2br(h($records2[0]['workdatetime']));
                    echo "<p id=\"workdatetime\">勤務予定日時：</br><span id=\"editworkdatetime\">"
                        . $records2[0]['workdatetime'] . "</span></p>";
                    echo "<p id=\"contact\">依頼者連絡先：<span id=\"editcontact\">"
                        . h($records2[0]['contact']) . "</span></p>";
                    echo "<p id=\"place\">住所(自動生成)：</p><!-- jsでappend -->";
                    if ($vieweradmin || $_SESSION['userno'] == 1) {
                        echo "<button data-role=\"none\" id=\"changeaddressbutton\" onclick=\"editPin();\">住所変更</button></br>";
                    }
                    echo "<div id=\"visiblelatlng\" style=\"display:none;\">";
                    echo "<form id=\"changelatlng\">";
                    echo "<p><font color=\"red\">住所変更中</font></p>";
                    echo "<div style=\"display: none;\">";
                    echo "<input type=\"number\" name=\"workid\" readonly=\"readonly\" value=\""
                        . $records2[0]['id'] . "\" required /></br>";
                    echo "<input type=\"number\" name=\"groupno\" readonly=\"readonly\" value=\""
                        . $groupno . "\" required /></br>";
                    echo "<input type=\"text\" id=\"changelat\" size=\"20\" name=\"changelat\" readonly=\"readonly\" value=\""
                        . $records2[0]['lat'] . "\" required /></br>";
                    echo "<input type=\"text\" id=\"changelng\" size=\"20\" name=\"changelng\" readonly=\"readonly\" value=\""
                        . $records2[0]['lng'] . "\" required /></br>";
                    echo "</div>";
                    echo "<input type=\"text\" name=\"changeaddress\" id=\"mapsearch\" placeholder=\"住所入力欄\">";
                    echo "<p>緯度：<span id=\"visiblelat\">" . $records2[0]['lat']
                        . "</span>　経度：<span id=\"visiblelng\">"
                        . $records2[0]['lng'] . "</span></p>";
                    echo "<input type=\"button\" data-theme=\"c\" value=\"住所変更を確定する\" onClick=\"confirmAddressChange("
                        . $groupno . "," . $records2[0]['id'] . ");\" />";
                    echo "</form>";
                    echo "</div></br>";
                    echo "<div id=\"detail-map\"></div><!-- jsでappend -->";
                    echo "<hr />";
                    echo "<h3>依頼者情報</h3>";
                    echo "<p>" . nl2br(h($clientcomment)) . "</p>";
                    echo "<hr />";
                    echo "<h3>日報</h3>";
                    if (count($records10) > 0) {
                        foreach ($records10 as $eachrecord) {
                            if (array_key_exists($eachrecord['workerno'],
                                $nicknamelist)
                            ) {
                                echo "<p>" . $eachrecord['workday']
                                    . ": <a href=\"mypage.php?userno="
                                    . $eachrecord['workerno']
                                    . "\" rel=\"external\">"
                                    . $nicknamelist[$eachrecord['workerno']]
                                    . "</a></p>";
                            } else {
                                echo "<p>" . $eachrecord['workday'] . ": 退会済</p>";
                            }
                            echo "<p>　" . nl2br(h($eachrecord['workreport'])) . "</p>";
                        }
                    }
                    echo "<hr />";
                    echo "<h3>評価</h3>";
                    if (count($records8) > 0) {
                        foreach ($records8 as $eachrecord) {
                            if (array_key_exists($eachrecord['workerno'],
                                $nicknamelist)
                            ) {
                                echo "<p><a href=\"mypage.php?userno="
                                    . $eachrecord['workerno']
                                    . "\" rel=\"external\">"
                                    . h($nicknamelist[$eachrecord['workerno']])
                                    . "</a></p>";
                            } else {
                                echo "<p>退会済</p>";
                            }
                            echo "<div class=\"rateit\" data-rateit-value=\""
                                . h($eachrecord['evaluation'])
                                . "\" data-rateit-min=\"0\" data-rateit-max=\"10\" data-rateit-ispreset=\"true\" data-rateit-readonly=\"true\"></div>";
                            echo "<p>" . nl2br(h($eachrecord['comment']))
                                . "</p><hr />";
                        }
                    }
                } //グループメンバーの場合、自分の履歴のみ見せる。ただし、星の評価は隠す。
                else {
                    if ($groupmember == 1) {
                        echo "<h2 id=\"worktitle\">" . $records2[0]['worktitle']
                            . "【終了】</h2>";
                        if ($records2[0]['content'] == "") {
                            $records2[0]['content'] = "仕事詳細が空欄です";
                        }
                        $records2[0]['content']
                            = nl2br(h($records2[0]['content']));
                        echo "<p id=\"content\">" . $records2[0]['content']
                            . "</p>";
                        if ($records2[0]['workdatetime'] == "") {
                            $records2[0]['workdatetime'] = "勤務予定日時が空欄です";
                        }
                        $records2[0]['workdatetime']
                            = nl2br(h($records2[0]['workdatetime']));
                        echo "<p id=\"workdatetime\">勤務予定日時：</br><span id=\"editworkdatetime\">"
                            . $records2[0]['workdatetime'] . "</span></p>";
                        echo "<p id=\"place\">住所(自動生成)：</p><!-- jsでappend -->";
                        echo "<div id=\"detail-map\"></div><!-- jsでappend -->";
                        echo "<hr />";
                        echo "<h3>日報</h3>";
                        if (count($records10) > 0) {
                            foreach ($records10 as $eachrecord) {
                                if ($eachrecord['workerno']
                                    == $_SESSION['userno']
                                ) {
                                    echo "<p>" . $eachrecord['workday'] . "</p>";
                                    echo "<p>　" . nl2br(h($eachrecord['workreport']))
                                        . "</p>";
                                }
                            }
                        }
                        echo "<hr />";
                        echo "<h3>管理者からのコメント</h3>";
                        if (count($records8) > 0) {
                            foreach ($records8 as $eachrecord) {
                                if ($eachrecord['workerno']
                                    == $_SESSION['userno']
                                ) {
                                    echo "<p>" . nl2br(h($eachrecord['comment']))
                                        . "</p><hr />";
                                }
                            }
                        }
                    }
                }
            }














            // 【ステータス3】
            // 作業と日報記入が全て終わっていたら、依頼者の情報入力を行う
            // 『依頼者』もしくは『管理者』のとき。代理入力に対応
            else {
                if ($records2[0]['status'] == 3) {
                    echo "<h2 id=\"worktitle\">" . h($records2[0]['worktitle'])
                        . " 【作業終了】</h2>";
                    if ($records2[0]['content'] == "") {
                        $records2[0]['content'] = "仕事詳細が空欄です";
                    }
                    $records2[0]['content'] = nl2br(h($records2[0]['content']));
                    echo "<p id=\"content\">" . $records2[0]['content'] . "</p>";
                    if ($records2[0]['workdatetime'] == "") {
                        $records2[0]['workdatetime'] = "勤務予定日時が空欄です";
                    }
                    $records2[0]['workdatetime']
                        = nl2br(h($records2[0]['workdatetime']));
                    echo "<p id=\"workdatetime\">勤務予定日時：</br><span id=\"editworkdatetime\">"
                        . $records2[0]['workdatetime'] . "</span></p>";
                    if ($vieweradmin) {
                        echo "<p id=\"contact\">依頼者連絡先：<span id=\"editcontact\">"
                            . h($records2[0]['contact']) . "</span></p>";
                    }
                    echo "<p id=\"place\">住所(自動生成)：</p><!-- jsでappend -->";

                    if ($vieweradmin) {
                        echo "<button data-role=\"none\" id=\"changeaddressbutton\" onclick=\"editPin();\">住所変更</button></br>";
                    }
                    echo "<div id=\"visiblelatlng\" style=\"display:none;\">";
                    echo "<form id=\"changelatlng\">";
                    echo "<p><font color=\"red\">住所変更中</font></p>";
                    echo "<div style=\"display: none;\">";
                    echo "<input type=\"number\" name=\"workid\" readonly=\"readonly\" value=\""
                        . $records2[0]['id'] . "\" required /></br>";
                    echo "<input type=\"number\" name=\"groupno\" readonly=\"readonly\" value=\""
                        . $groupno . "\" required /></br>";
                    echo "<input type=\"text\" id=\"changelat\" size=\"20\" name=\"changelat\" readonly=\"readonly\" value=\""
                        . $records2[0]['lat'] . "\" required /></br>";
                    echo "<input type=\"text\" id=\"changelng\" size=\"20\" name=\"changelng\" readonly=\"readonly\" value=\""
                        . $records2[0]['lng'] . "\" required /></br>";
                    echo "</div>";
                    echo "<input type=\"text\" name=\"changeaddress\" id=\"mapsearch\" placeholder=\"住所入力欄\">";
                    echo "<p>緯度：<span id=\"visiblelat\">" . $records2[0]['lat']
                        . "</span>　経度：<span id=\"visiblelng\">"
                        . $records2[0]['lng'] . "</span></p>";
                    echo "<input type=\"button\" data-theme=\"c\" value=\"住所変更を確定する\" onClick=\"confirmAddressChange("
                        . $groupno . "," . $records2[0]['id'] . ");\" />";
                    echo "</form>";
                    echo "</div></br>";

                    echo "<div id=\"detail-map\"></div><!-- jsでappend -->";
                    //ここから管理者専用
                    if ($vieweradmin == 1) {
                        echo "<hr />";
                        if ($records2[0]['clientid'] == 0) {
                            echo "<p>(新規顧客)</p>";
                        } else {
                            echo "<p>(既存顧客)</p>";
                        }
                        echo "<h3>依頼人、依頼場所の情報を記入してください。</h3>";
                        echo "<textarea data-role=\"none\" name=\"clientinfo\" placeholder=\"残すべき情報があればご記入ください\" required>"
                            . h($clientcomment) . "</textarea>\n";
                        echo "<input type=\"button\" value=\"情報を登録する\" onClick=\"registerClientInfo('"
                            . $workid . "','" . $groupno . "','"
                            . h($records2[0]['clientid']) . "');\" />\n";
                        echo "<hr />";
                        if (count($records8) == 0) {
                            echo "<p>この仕事で働いた人はいません。終了報告を行います。</p></br>\n";
                            echo "<input type=\"button\" value=\"終了報告する\" onClick=\"eraselogspecialist("
                                . $groupno . "," . $workid . ");\" />\n";
                        } else {
                            echo "<h3>各ワーカーについて星で評価を決め、評価コメントをご記入ください。</h3>\n";
                            $ampmarray = ["午後", "午前"];
                            foreach ($records8 as $eachworker) {
                                if (array_key_exists($eachworker['workerno'],
                                    $nicknamelist)
                                ) {
                                    echo "<p><a href=\"mypage.php?userno="
                                        . $eachworker['workerno']
                                        . "\" rel=\"external\">"
                                        . h($nicknamelist[$eachworker['workerno']])
                                        . "</a></p>\n";
                                } else {
                                    echo "<p>退会済</p>\n";
                                }
                                //その人の日報を表示する
                                foreach ($records10 as $eachrecord) {
                                    if ($eachrecord['workerno']
                                        == $eachworker['workerno']
                                    ) {
                                        echo "<p>" . $eachrecord['workday'] . " "
                                            . $ampmarray[$eachrecord['am']]
                                            . " 日報：" . h($eachrecord['workreport'])
                                            . "</p>";
                                    }
                                }
                                if ($eachworker['evaluation'] == 0) { //未評価の時
                                    echo "<input data-role=\"none\" type=\"range\" value=\"5\" step=\"1\" name=\"backing"
                                        . $eachworker['workerno']
                                        . "\" id=\"backing"
                                        . $eachworker['workerno'] . "\">\n";
                                    echo "<span class=\"rateit\" data-rateit-backingfld=\"#backing"
                                        . $eachworker['workerno']
                                        . "\" data-rateit-resetable=\"false\" data-rateit-ispreset=\"true\" data-rateit-min=\"0\" data-rateit-max=\"10\"></span></p>\n";
                                    echo "<textarea data-role=\"none\" id=\"comment"
                                        . $eachworker['workerno']
                                        . "\" name=\"comment"
                                        . $eachworker['workerno']
                                        . "\" placeholder=\"評価コメントがあればご記入ください\" required></textarea>\n";
                                    echo "<input type=\"button\" value=\"評価を登録する\" onClick=\"registerevalspecialistnew('"
                                        . $workid . "','" . $groupno . "','"
                                        . $eachworker['workerno'] . "');\" />\n";
                                } else {
                                    if ($eachworker['evaluation']
                                        > 0
                                    ) { //評価済みなら評価結果を表示
                                        echo "<span class=\"rateit\" data-rateit-value=\""
                                            . h($eachworker['evaluation'])
                                            . "\" data-rateit-min=\"0\" data-rateit-max=\"10\" data-rateit-ispreset=\"true\" data-rateit-readonly=\"true\"></span> 評価済";
                                        echo "<p>" . nl2br(h($eachworker['comment']))
                                            . "</p>";
                                    }
                                }
                                echo "<hr />";
                            }
                        }
                    } //管理者以外は見られない
                    else {
                        echo "<p>管理者の評価待ちです。終了次第日報を表示します。</p>\n";
                    }
                }

















                //【ステータス2】
                //金額についての合意が得られていたら、ワーカーの割り振りなどを行う。
                else {
                    if ($records2[0]['status'] == 2) {

                        //まずは全員向けに共通情報を
                        echo "<h2 id=\"worktitle\">" . $records2[0]['worktitle']
                            . "</h2>";
                        if ($groupmember) {
                            if ($evaluatedflag) {
                                if ($answeredinterest) {
                                    echo "<p>【参加希望あり】→　<input type=\"button\" data-role=\"none\" data-inline=\"true\" value=\"希望なしに変更\" onClick=\"changeinterest("
                                        . $groupno . "," . $workid . ","
                                        . $_SESSION['userno'] . ",0);\" /></p>";
                                } else {
                                    echo "<p>【参加希望なし】→　<input type=\"button\" data-role=\"none\" data-inline=\"true\" value=\"希望ありに変更\" onClick=\"changeinterest("
                                        . $groupno . "," . $workid . ","
                                        . $_SESSION['userno'] . ",1);\" /></p>";
                                }
                                echo "<span>※管理者に伝えたいコメントがあれば以下をクリックして記載してください</span><br>";
                                echo "<span id=\"interestcomment\" class=\"jobreport\">"
                                    . h($answeredcomment)
                                    . "</span><script>$(\"span#interestcomment\").editable(\"click\",function(e){editcomment('"
                                    . $groupno . "','" . $workid . "','"
                                    . $_SESSION['userno']
                                    . "',e.value);});</script>";
                            } else {
                                echo "<p>（参加希望未回答）</p>";
                            }
                        }
                        echo "<p>【勤務詳細】</p>";
                        if ($records2[0]['content'] == "") {
                            $records2[0]['content'] = "仕事詳細が空欄です";
                        }
                        $records2[0]['content']
                            = nl2br(h($records2[0]['content']));
                        echo "<p id=\"content\">" . $records2[0]['content']
                            . "</p>";
                        if ($records2[0]['workdatetime'] == "") {
                            $records2[0]['workdatetime'] = "勤務予定日時が空欄です";
                        }
                        $records2[0]['workdatetime']
                            = nl2br(h($records2[0]['workdatetime']));
                        echo "<p id=\"workdatetime\">勤務予定日時：</br><span id=\"editworkdatetime\">"
                            . $records2[0]['workdatetime'] . "</span></p>";
                        if ($vieweradmin) {
                            echo "<p id=\"contact\">依頼者連絡先：<span id=\"editcontact\">"
                                . h($records2[0]['contact']) . "</span></p>";
                        }
                        echo "<p id=\"place\">住所(自動生成)：</p><!-- jsでappend -->";
                        //住所変更用モジュール
                        if ($vieweradmin) {
                            echo "<button data-role=\"none\" id=\"changeaddressbutton\" onclick=\"editPin();\">住所変更</button></br>";
                        }
                        echo "<div id=\"visiblelatlng\" style=\"display:none;\">";
                        echo "<form id=\"changelatlng\">";
                        echo "<p><font color=\"red\">住所変更中</font></p>";
                        echo "<div style=\"display: none;\">";
                        echo "<input type=\"number\" name=\"workid\" readonly=\"readonly\" value=\""
                            . $records2[0]['id'] . "\" required /></br>";
                        echo "<input type=\"number\" name=\"groupno\" readonly=\"readonly\" value=\""
                            . $groupno . "\" required /></br>";
                        echo "<input type=\"text\" id=\"changelat\" size=\"20\" name=\"changelat\" readonly=\"readonly\" value=\""
                            . $records2[0]['lat'] . "\" required /></br>";
                        echo "<input type=\"text\" id=\"changelng\" size=\"20\" name=\"changelng\" readonly=\"readonly\" value=\""
                            . $records2[0]['lng'] . "\" required /></br>";
                        echo "</div>";
                        echo "<input type=\"text\" name=\"changeaddress\" id=\"mapsearch\" placeholder=\"住所入力欄\">";
                        echo "<p>緯度：<span id=\"visiblelat\">"
                            . $records2[0]['lat']
                            . "</span>　経度：<span id=\"visiblelng\">"
                            . $records2[0]['lng'] . "</span></p>";
                        echo "<input type=\"button\" data-theme=\"c\" value=\"住所変更を確定する\" onClick=\"confirmAddressChange("
                            . $groupno . "," . $records2[0]['id'] . ");\" />";
                        echo "</form>";
                        echo "</div></br>";

                        // 参加希望有無が未回答なら、そのボタンを表示
                        if ($groupmember == 1) {
                            if (!$evaluatedflag) {
                                echo "<div class=\"ui-grid-a\">";
                                echo "<div class=\"ui-block-a\"><input type=\"button\" onclick=\"answerspecialistinterest("
                                    . $groupno . "," . $workid . ","
                                    . $_SESSION['userno']
                                    . ", 1)\" value=\"参加希望あり\"></div>";
                                echo "<div class=\"ui-block-b\"><input type=\"button\" data-theme=\"c\" onclick=\"answerspecialistinterest("
                                    . $groupno . "," . $workid . ","
                                    . $_SESSION['userno']
                                    . ", 0)\" value=\"参加希望なし\"></div>";
                                echo "</div></br>";
                            }
                        }

                        echo "<p>【参加希望ありメンバー】</p>";
                        echo "<table style=\"text-align: center;\"><tr>";
                        $someoneinterested = false;
                        foreach ($records as $eachmember) {
                            if ($eachmember['interest'] == "◯") {
                                echo "<td><a href=\"mypage.php?userno="
                                    . $eachmember['userno']
                                    . "\" rel=\"external\"><img src=\"./model/showuserimage.php?userno="
                                    . $eachmember['userno']
                                    . "\" onerror=\"this.src='img/noimage.svg';\" class=\"bbsphoto\" width=\"50px\"></a></td>";
                                $someoneinterested = true;
                            }
                        }
                        echo "</tr><tr>";
                        foreach ($records as $eachmember) {
                            if ($eachmember['interest'] == "◯") {
                                echo "<td><a href=\"mypage.php?userno="
                                    . $eachmember['userno']
                                    . "\" rel=\"external\">"
                                    . h($eachmember['nickname']) . "</a></td>";
                                $someoneinterested = true;
                            }
                        }
                        echo "</tr></table>";
                        if (!$someoneinterested) {
                            echo "<p>まだ参加希望ありと回答した人はいません</p>";
                        }
                        echo "<br><br>";

                        echo "<div id=\"detail-map\"></div><!-- jsでappend -->";
                        //確定したワーカー一覧を表示

                        //**********折りたたみ表示に変更したいな****************//

                        if (count($records10) > 0) {
                            echo "<ul data-role=\"listview\" data-inset=\"true\">";
                            echo "<span data-theme=\"c\" data-role=\"collapsible\" data-collapsed=\"true\" data-iconpos=\"right\" data-inset=\"true\" style=\"margin-bottom: -1px;\"><h3>⓪ 確定したワーカー</h3>";
                            foreach ($records10 as $eachrecord) {
                                $kakuteistr = "<p>" . $eachrecord['workday'];
                                if ($eachrecord['am'] == 1) {
                                    $kakuteistr .= " 午前 ";
                                } else {
                                    if ($eachrecord['pm'] == 1) {
                                        $kakuteistr .= " 午後 ";
                                    }
                                }
                                if (array_key_exists($eachrecord['workerno'],
                                    $nicknamelist)
                                ) {
                                    $kakuteistr .= "　<a href=\"mypage.php?userno="
                                        . $eachrecord['workerno']
                                        . "\" rel=\"external\">"
                                        . h($nicknamelist[$eachrecord['workerno']])
                                        . "</a>";
                                } else {
                                    $kakuteistr .= "　退会済";
                                }
                                if ($vieweradmin) {
                                    $kakuteistr .= "　<span id=\""
                                        . $eachrecord['workerno'] . "_"
                                        . $eachrecord['workday'] . "_"
                                        . $eachrecord['am'] . "_" . $eachrecord['pm']
                                        . "\"><input type=\"button\" data-role=\"none\" value=\"勤務記録を削除\" onClick=\"acceptOffer('"
                                        . $groupno . "','" . $workid . "','"
                                        . $eachrecord['workday'] . "','"
                                        . $eachrecord['workerno'] . "','"
                                        . $eachrecord['am'] . "','"
                                        . $eachrecord['pm'] . "','0');\" />";
                                }
                                if ($eachrecord['worktime'] > 0) {
                                    $kakuteistr .= "　勤務時間："
                                        . $eachrecord['worktime'] . "時間";
                                }
                                if ($eachrecord['reportflag'] == 1) {
                                    $kakuteistr .= "　日報："
                                        . h($eachrecord['workreport']);
                                }
                                if ($vieweradmin) {
                                    $kakuteistr .= "</span>";
                                }
                                $kakuteistr .= "</p>";
                                echo $kakuteistr;
                            }
                            echo "</span></ul>";
                        }

                        //『管理者』のとき
                        //グループメンバーに仕事を割り振る．カレンダーUI．
                        if ($vieweradmin == 1) {
                            // カレンダーUIではなく、日付選択→空いている人表示→業務内容入力→依頼ボタン。DONE!!!
                            echo "<ul data-role=\"listview\" data-inset=\"true\">";
                            echo "<span data-theme=\"c\" data-role=\"collapsible\" data-collapsed=\"true\" data-iconpos=\"right\" data-inset=\"true\" style=\"margin-bottom: -1px;\"><h3>① 作業の割り振り</h3>";
                            $evalarray = ["未評価", "初心者", "中級者", "上級者"];
                            echo "<input data-role=\"date\" type=\"text\" name=\"workdate\" id=\"workdate\" placeholder=\"日付を選択してください\" required />";
                            echo "<div>【午前】依頼済み: <span id=\"waiting_am\">0</span>人、承認済み: <span id=\"accepted_am\">0</span>人</div>";
                            echo "<div>【午後】依頼済み: <span id=\"waiting_pm\">0</span>人、承認済み: <span id=\"accepted_pm\">0</span>人</div>";
                            echo "<table id=\"sorttable\">";
                            echo "<thead><tr>";
                            echo "<th class=\"memberprof\"></th>";
                            echo "<th class=\"memberprof\">ID</th>";
                            echo "<th class=\"memberprof\">名前</th>";
                            echo "<th class=\"memberprof\">参加希望</th>";
                            echo "<th class=\"memberprof\">スコア</th>";
                            echo "<th class=\"memberprof\">午前</th>";
                            echo "<th class=\"memberprof\">午後</th>";
                            echo "<th class=\"memberprof\">評価</th>";
                            echo "<th class=\"memberprof\">距離(m)</th>";
                            echo "<th class=\"memberprof\">コメント</th>";
                            echo "<th class=\"memberprof\">メモ</th>";
                            echo "<th class=\"memberprof\">勤務時間(30日)</th>";
                            echo "</tr></thead><tbody>";
                            foreach ($records as $eachmember) {
                                switch($eachmember['interest']){
                                    case 1:
                                        $interest = "○";
                                        break;
                                    case -1:
                                        $interest = "×";
                                        break;
                                    default:
                                        $interest = "未回答";
                                }
                                $score = $eachmember['match'] == UNDEFINED_SCORE ? "未回答" : $eachmember['match'];
                                $str = "<tr>";
                                $str .= "<td><a href=\"mypage.php?userno="
                                    . $eachmember['userno']
                                    . "\" rel=\"external\"><img src=\"./model/showuserimage.php?userno="
                                    . $eachmember['userno']
                                    . "\" onerror=\"this.src='img/noimage.svg';\" width=\"50px\" /></a></td>";
                                $str .= "<td class=\"memberprof\">"
                                    . $eachmember['userno'] . "</td>";
                                $str .= "<td class=\"memberprof\">"
                                    . h($eachmember['nickname']) . "</td>";
                                $str .= "<td class=\"memberprof\">"
                                    . $interest . "</td>";
                                $str .= "<td class=\"memberprof\">"
                                    . $score . "</td>";
                                $str .= "<td class=\"memberprof clear\" id=\"am_"
                                    . $eachmember['userno'] . "\"></td>";
                                $str .= "<td class=\"memberprof clear\" id=\"pm_"
                                    . $eachmember['userno'] . "\"></td>";
                                $str .= "<td class=\"memberprof\">"
                                    . h($evalarray[$eachmember['eval']]) . "</td>";
                                if ($eachmember['mylat'] == 0) {
                                    $distance = "未登録";
                                } else {
                                    $distance
                                        = calcDistance($records2[0]['lat'],
                                        $records2[0]['lng'],
                                        $eachmember['mylat'],
                                        $eachmember['mylng']);
                                }
                                $str .= "<td class=\"memberprof\" id=\"distance_"
                                    . $eachmember['userno'] . "\">" . $distance
                                    . "</td>";
                                $str .= "<td class=\"memberprof\">"
                                    . h($eachmember['comment']) . "</td>";
                                $str .= "<td class=\"memberprof\">"
                                    . h($eachmember['memo']) . "</td>";
                                $str .= "<td class=\"memberprof\">"
                                    . h($eachmember['accumulation']) . "</td>";
                                $str .= "</tr>\n";
                                echo $str;
                            }
                            echo "</tbody></table>";
                            echo "</span></ul><!--end of #jobs-->";
                        }

                        //『メンバー』のとき
                        //応募ボタンを出す．応募済みなら日報作成ボタンを出す
                        if ($groupmember == 1) {
                            echo "<ul data-role=\"listview\" data-inset=\"true\">";
                            $offercount = 0;
                            foreach ($records7 as $eachrecord) {
                                if ($eachrecord['status'] == 0) {
                                    $offercount++;
                                }
                            }
                            if ($vieweradmin) {
                                echo "<span data-theme=\"c\" data-role=\"collapsible\" data-collapsed=\"true\" data-iconpos=\"right\" data-inset=\"true\" style=\"margin-bottom: -1px;\"><h3>② 未回答オファー<span class=\"ui-li-count\">"
                                    . $offercount . "</span></h3>";
                            } else {
                                echo "<span data-theme=\"c\" data-role=\"collapsible\" data-collapsed=\"true\" data-iconpos=\"right\" data-inset=\"true\" style=\"margin-bottom: -1px;\"><h3>① 未回答オファー<span class=\"ui-li-count\">"
                                    . $offercount . "</span></h3>";
                            }
                            if ($offercount == 0) {
                                echo "<p>現在オファーはありません</p>";
                            } else {
                                foreach ($records7 as $eachoffer) {
                                    if ($eachoffer['status']
                                        == 0
                                    ) {//status=0なら，承諾or棄却のボタン
                                        echo $eachoffer['workday'] . " ";
                                        if ($eachoffer['am'] == 1) {
                                            echo "午前　";
                                        } else {
                                            if ($eachoffer['pm'] == 1) {
                                                echo "午後　";
                                            }
                                        }
                                        if (array_key_exists($eachoffer['workerno'],
                                            $nicknamelist)
                                        ) {
                                            echo "<a href=\"mypage.php?userno="
                                                . $eachoffer['workerno']
                                                . "\" rel=\"external\">"
                                                . h($nicknamelist[$eachoffer['workerno']])
                                                . "</a>";
                                        } else {
                                            echo "退会済";
                                        }
                                        echo "<span id=\""
                                            . $eachoffer['workerno'] . "_"
                                            . $eachoffer['workday'] . "_"
                                            . $eachoffer['am'] . "_"
                                            . $eachoffer['pm'] . "\">";
                                        echo "<input type=\"button\" data-inline=\"true\" data-mini=\"true\" onclick=\"acceptOffer('"
                                            . $groupno . "','" . $workid . "','"
                                            . $eachoffer['workday'] . "','"
                                            . $eachoffer['workerno'] . "','"
                                            . $eachoffer['am'] . "','"
                                            . $eachoffer['pm']
                                            . "','1')\" value=\"承諾\"> ";
                                        echo "<input type=\"button\" data-inline=\"true\" data-mini=\"true\" onclick=\"acceptOffer('"
                                            . $groupno . "','" . $workid . "','"
                                            . $eachoffer['workday'] . "','"
                                            . $eachoffer['workerno'] . "','"
                                            . $eachoffer['am'] . "','"
                                            . $eachoffer['pm']
                                            . "','0')\" value=\"棄却\" data-theme=\"c\">";
                                        echo "</span></br>";
                                        //echo "<span>".$eachoffer['task']."</span></br></br>";
                                    }
                                }
                            }
                            echo "</span></ul>";

                            if ($vieweradmin && $numberdeclined > 0) {
                                echo "<div data-role=\"collapsible\" data-theme=\"c\">";
                                echo "<h3>棄却した人一覧</h3>";
                                foreach ($records111 as $eachrecord) {
                                    if ($eachrecord['am'] == 1) {
                                        echo "<p>" . $eachrecord['workday'] . " 午前 "
                                            . h($nicknamelist[$eachrecord['workerno']])
                                            . "</p>";
                                    } else {
                                        echo "<p>" . $eachrecord['workday'] . " 午後 "
                                            . h($nicknamelist[$eachrecord['workerno']])
                                            . "</p>";
                                    }
                                }
                                echo "</div>";
                            }

                            // 日報の記入
                            echo "<ul data-role=\"listview\" data-inset=\"true\">";
                            $unwrittenreport = 0;
                            foreach ($records7 as $eachrecord) {
                                if ($eachrecord['reportflag'] == 0
                                    && $eachrecord['status'] == 1
                                ) {
                                    $unwrittenreport++;
                                }
                            }
                            if ($vieweradmin) {
                                echo "<span data-theme=\"c\" data-role=\"collapsible\" data-collapsed=\"true\" data-iconpos=\"right\" data-inset=\"true\" style=\"margin-bottom: -1px;\"><h3>③ 日報記入<span class=\"ui-li-count\">"
                                    . $unwrittenreport . "</span></h3>";
                            } else {
                                echo "<span data-theme=\"c\" data-role=\"collapsible\" data-collapsed=\"true\" data-iconpos=\"right\" data-inset=\"true\" style=\"margin-bottom: -1px;\"><h3>② 日報記入<span class=\"ui-li-count\">"
                                    . $unwrittenreport . "</span></h3>";
                            }
                            $reportcount = 0;
                            $writtenreport = "";
                            $writtenreportcount = 0;
                            echo "<br>";
                            foreach ($records7 as $eachoffer) {
                                if ($eachoffer['status']
                                    == 1
                                ) {//status=1なら日報用フォームand削除ボタン
                                    //見積もりの場合、記入はスキップ
                                    if ($eachoffer['am'] == 0
                                        && $eachoffer['pm'] == 0
                                    ) {
                                        //echo $jobreportstr."</br></br>";
                                        //$reportcount = $reportcount + 1;
                                        continue;
                                    }

                                    //日報提出時に，登録されている全ての日程について日報が存在していれば，selfeval=1にする
                                    $jobreportstr = "<span id=\"report_"
                                        . $eachoffer['workerno'] . "_"
                                        . $eachoffer['workday'] . "_"
                                        . $eachoffer['am'] . "_" . $eachoffer['pm']
                                        . "\"><span>" . $eachoffer['workday'] . " ";
                                    if ($eachoffer['am'] == 1) {
                                        $jobreportstr .= "午前　";
                                    } else {
                                        if ($eachoffer['pm'] == 1) {
                                            $jobreportstr .= "午後　";
                                        } else {
                                            $jobreportstr .= "見積もり　";
                                        }
                                    }
                                    if (array_key_exists($eachoffer['workerno'],
                                        $nicknamelist)
                                    ) {
                                        $jobreportstr .= "<a href=\"mypage.php?userno="
                                            . $eachoffer['workerno']
                                            . "\" rel=\"external\">"
                                            . $nicknamelist[$eachoffer['workerno']]
                                            . "</a>";
                                    } else {
                                        $jobreportstr .= "退会済";
                                    }
                                    $jobreportstr .= "</span></br>";
                                    //時間を記入
                                    $jobreportstr .= "<select id=\"worktime_day_"
                                        . $eachoffer['workday'] . "_"
                                        . $eachoffer['am'] . "_" . $eachoffer['pm']
                                        . "_" . $eachoffer['workerno']
                                        . "\" data-inline=\"true\" data-mini=\"true\" data-theme=\"c\" value=\"2.0\" onchange='insertworktime(this,\""
                                        . $groupno . "\",\"" . $workid . "\",\""
                                        . $eachoffer['workday'] . "\",\""
                                        . $eachoffer['workerno'] . "\",\""
                                        . $eachoffer['am'] . "\",\""
                                        . $eachoffer['pm']
                                        . "\");'><option value=\"勤務時間をお選びください\">勤務時間をお選びください</option>";
                                    for ($i = 0.5; $i < 12.5; $i = $i + 0.5) {
                                        if ($eachoffer['worktime'] == $i) {
                                            $jobreportstr .= "<option value=\""
                                                . $i . "\" selected>" . $i
                                                . "時間</option>";
                                        }
                                        $jobreportstr .= "<option value=\"" . $i
                                            . "\">" . $i . "時間</option>";
                                    }
                                    $jobreportstr .= "</select>";

                                    $jobreportstr .= "</br></br><span id=\"day_"
                                        . $eachoffer['workday'] . "_"
                                        . $eachoffer['am'] . "_" . $eachoffer['pm']
                                        . "_" . $eachoffer['workerno']
                                        . "\" class=\"jobreport\">"
                                        . h($eachoffer['workreport'])
                                        . "</span></br></br></span>\n";
                                    $jobreportstr .= "<script>$(\"span#day_"
                                        . $eachoffer['workday'] . "_"
                                        . $eachoffer['am'] . "_" . $eachoffer['pm']
                                        . "_" . $eachoffer['workerno']
                                        . "\").editable(\"click\",function(e){editreport('"
                                        . $groupno . "','" . $workid . "','"
                                        . $eachoffer['workday'] . "','"
                                        . $eachoffer['workerno'] . "','"
                                        . $eachoffer['am'] . "','" . $eachoffer['pm']
                                        . "',e.value);});</script>";
                                    if ($eachoffer['reportflag'] == 0) {
                                        echo $jobreportstr;
                                        $reportcount = $reportcount + 1;
                                    } else {
                                        $writtenreport .= $jobreportstr;
                                        $writtenreportcount++;
                                    }
                                }
                            }
                            if ($reportcount == 0) {
                                echo "<p>現在未記入の日報はありません。</p>";
                            }
                            if ($writtenreportcount > 0) {
                                echo "<div data-role=\"collapsible\" data-theme=\"c\"><h3>記入済み日報一覧</h3>"
                                    . $writtenreport . "</div>";
                            }
                            echo "</span>";
                            echo "</span></ul>";
                        }
                        if ($vieweradmin == 1) {
                            echo "<ul data-role=\"listview\" data-inset=\"true\">";
                            echo "<span data-theme=\"c\" data-role=\"collapsible\" data-collapsed=\"true\" data-iconpos=\"right\" data-inset=\"true\" style=\"margin-bottom: -1px;\"><h3>④ 終了報告</h3>";
                            if ($evalfinishedflag) {
                                echo "<p>仕事の全日程が終了し、全日報が提出されましたら下のボタンから終了報告してください。</p>";
                                echo "<input type=\"button\" value=\"終了報告\" data-theme=\"c\" onclick=\"workfinishreport("
                                    . $groupno . "," . $workid . ");\">";
                            } else {
                                echo "<p>日報未入力のワーカーがいるため終了報告が行えません。</p>";
                            }
                            echo "</span></ul><!--end of 終了報告-->";
                            echo "<div data-role=\"collapsible\" data-theme=\"c\">";
                            echo "<h3>メール連絡</h3>";
                            echo "<p>確定済のワーカー全員にメールを送信します。</p>";
                            echo "<input type=\"text\" placeholder=\"メールタイトル\" name=\"mailsubject\"/>";
                            echo "<textarea data-role=\"none\" placeholder=\"メール本文\" name=\"mailcontent\"/></textarea>";
                            echo "<input type=\"button\" value=\"メール送信\" data-theme=\"c\" onclick=\"sendEmail2workers("
                                . $groupno . ");\">";
                            echo "</div>";
                        }//if(admin)終わり
                    }//ステータス2の終わり


                    //【ステータス0】最新版への改良完了！


                    //「管理者」のとき
                    //仕事詳細を入力する．日時表示用にカレンダーも下に載せる
                    else {
                        if ($records2[0]['status'] == 0 && $vieweradmin == 1) {
                            //仕事詳細
                            echo "<h2 id=\"worktitle\">"
                                . h($records2[0]['worktitle']) . "</h2>";
                            if ($records2[0]['content'] == "") {
                                $records2[0]['content'] = "仕事詳細が空欄です";
                            }
                            echo "<p id=\"content\">"
                                . nl2br(h($records2[0]['content'])) . "</p>";
                            if ($records2[0]['workdatetime'] == "") {
                                $records2[0]['workdatetime'] = "勤務予定日時が空欄です";
                            }
                            echo "<p id=\"workdatetime\">勤務予定日時：</br><span id=\"editworkdatetime\">"
                                . nl2br(h($records2[0]['workdatetime']))
                                . "</span></p>";
                            echo "<p id=\"contact\">依頼者連絡先：<span id=\"editcontact\">"
                                . h($records2[0]['contact']) . "</span></p>";
                            echo "<p id=\"place\">住所(自動生成)：</p><!-- jsでappend -->";

                            if ($vieweradmin || $_SESSION['userno'] == 1) {
                                echo "<button data-role=\"none\" id=\"changeaddressbutton\" onclick=\"editPin();\">住所変更</button></br>";
                            }
                            echo "<div id=\"visiblelatlng\" style=\"display:none;\">";
                            echo "<form id=\"changelatlng\">";
                            echo "<p><font color=\"red\">住所変更中</font></p>";
                            echo "<div style=\"display: none;\">";
                            echo "<input type=\"number\" name=\"workid\" readonly=\"readonly\" value=\""
                                . $records2[0]['id'] . "\" required /></br>";
                            echo "<input type=\"number\" name=\"groupno\" readonly=\"readonly\" value=\""
                                . $groupno . "\" required /></br>";
                            echo "<input type=\"text\" id=\"changelat\" size=\"20\" name=\"changelat\" readonly=\"readonly\" value=\""
                                . $records2[0]['lat'] . "\" required /></br>";
                            echo "<input type=\"text\" id=\"changelng\" size=\"20\" name=\"changelng\" readonly=\"readonly\" value=\""
                                . $records2[0]['lng'] . "\" required /></br>";
                            echo "</div>";
                            echo "<input type=\"text\" name=\"changeaddress\" id=\"mapsearch\" placeholder=\"住所入力欄\">";
                            echo "<p>緯度：<span id=\"visiblelat\">"
                                . $records2[0]['lat']
                                . "</span>　経度：<span id=\"visiblelng\">"
                                . $records2[0]['lng'] . "</span></p>";
                            echo "<input type=\"button\" data-theme=\"c\" value=\"住所変更を確定する\" onClick=\"confirmAddressChange("
                                . $groupno . "," . $records2[0]['id'] . ");\" />";
                            echo "</form>";
                            echo "</div></br>";

                            echo "<div id=\"detail-map\"></div><!-- jsでappend --></br>";

                            if ($mitsumorilist[$groupno] == 1) {
                                //これまでの見積もり時間数。見積もり有り設定の場合のみ表示。
                                if (count($records10) > 0) {
                                    echo "<h3>これまでに見積もりに行った人</h3>";
                                    echo "<p>※この数値の変更は、見積書を締結した後の「日報記入」から出来ます。</p>";
                                    foreach ($records10 as $eachrecord) {
                                        if (array_key_exists($eachrecord['workerno'],
                                            $nicknamelist)
                                        ) {
                                            echo $eachrecord['workday'] . " "
                                                . h($nicknamelist[$eachrecord['workerno']])
                                                . " " . $eachrecord['worktime']
                                                . "時間</br>";
                                        } else {
                                            echo $eachrecord['workday'] . " 退会済 "
                                                . $eachrecord['worktime']
                                                . "時間</br>";
                                        }
                                    }
                                    echo "</br>";
                                }
                                //時間を記入
                                echo "<h3>見積もり人の新規登録</h3>";
                                $estimator_str
                                    = "<input data-role=\"date\" type=\"text\" name=\"new_workdate\" data-inline=\"true\" data-mini=\"true\" id=\"new_workdate\" placeholder=\"日付を選択してください\" required />";
                                $estimator_str .= "<select id=\"new_workerid\" name=\"new_workerid\" data-inline=\"true\" data-mini=\"true\" data-theme=\"c\"><option value=\"ユーザID\">ユーザID</option>";
                                foreach ($records as $eachrecord) {
                                    if (array_key_exists($eachrecord['userno'],
                                        $nicknamelist)
                                    ) {
                                        $estimator_str .= "<option value=\""
                                            . $eachrecord['userno'] . "\">"
                                            . h($nicknamelist[$eachrecord['userno']])
                                            . " " . $eachrecord['userno']
                                            . "</option>";
                                    } else {
                                        $estimator_str .= "<option value=\""
                                            . $eachrecord['userno'] . "\">退会済 "
                                            . $eachrecord['userno'] . "</option>";
                                    }
                                }
                                $estimator_str .= "</select>";
                                $estimator_str .= "<select id=\"new_worktime\" name=\"new_worktime\" data-inline=\"true\" data-mini=\"true\" data-theme=\"c\"><option value=\"勤務時間\">勤務時間</option><option value=\"0.5\">0.5時間</option><option value=\"1\">1時間</option><option value=\"1.5\">1.5時間</option><option value=\"2\">2時間</option><option value=\"2.5\">2.5時間</option><option value=\"3\">3時間</option><option value=\"3.5\">3.5時間</option><option value=\"4\">4時間</option><option value=\"4.5\">4.5時間</option><option value=\"5\">5時間</option><option value=\"5.5\">5.5時間</option><option value=\"6\">6時間</option><option value=\"6.5\">6.5時間</option><option value=\"7\">7時間</option><option value=\"7.5\">7.5時間</option><option value=\"8\">8時間</option><option value=\"8.5\">8.5時間</option><option value=\"9\">9時間</option><option value=\"9.5\">9.5時間</option><option value=\"10\">10時間</option><option value=\"10.5\">10.5時間</option><option value=\"11\">11時間</option><option value=\"11.5\">11.5時間</option><option value=\"12\">12時間</option></select>";
                                $estimator_str .= "<input type=\"button\" data-inline=\"true\" data-mini=\"true\" onclick=\"registerNewEstimator("
                                    . $workid . "," . $groupno . ")\" value=\"登録\">\n";
                                $estimator_str .= "</br></br>";
                                echo $estimator_str;
                            }//見積もり画面はここで終わり

                            // 新入力フォーム。オフラインでやり取りは済んでいて、あとは決定を押すだけの設計
                            if ($records2[0]['message'] != null) {
                                echo "<p>依頼者からのメッセージ：</br>"
                                    . $records2[0]['message'] . "</p></br>";
                            }
                            echo "<h3>【契約内容の確認】</h3>";
                            echo "<form id=\"adminpropose\">\n";
                            echo "<div style=\"display:none;\"><label for=\"price\">見積価格</label><input type=\"text\" name=\"price\" value=\""
                                . h($records2[0]['price']) . "\"/></br></div>\n";
                            //echo "<label for=\"content\">仕事詳細</label>";
                            echo "<label for=\"content\">【具体的な内容】</label>";
                            echo "<textarea data-role=\"none\" id=\"content\" name=\"content\" required>"
                                . h($records2[0]['content']) . "</textarea></br>";
                            echo "</br>";
                            echo "<label for=\"workdatetime\">【勤務予定日時】</label>";
                            echo "<span>※ 後から変更可能です。未定の場合「未定」と記入してください。</span>";
                            echo "<textarea data-role=\"none\" id=\"workdatetime\" name=\"workdatetime\" required>"
                                . $records2[0]['workdatetime']
                                . "</textarea></br>";
                            echo "<input type=\"button\" onclick=\"sendProposalAndAccept("
                                . $workid . "," . $groupno . "," . $_SESSION['userno']
                                . ",'" . h($records2[0]['worktitle'])
                                . "')\" value=\"仕事の割り振りに進む\">\n";
                            echo "</form></br>\n";
                            // 日程を決めやすくするために、グループメンバーのカレンダーを表示する。長いので外部ファイル。
                            //include("./common/quotationCalendar.php");

                            echo "</br></br></br><p>もし契約破棄になった場合は右のボタンを押して削除してください → <input type=\"button\" data-role=\"none\" data-inline=\"true\" value=\"データを削除する\" onClick=\"eraselogspecialist("
                                . $groupno . "," . $workid . ");\" /></p>\n";
                        }
                        //「依頼者」のとき
                        //自分の依頼内容だけ見れる
                        else {
                            if ($records2[0]['status'] == 0
                                && $_SESSION['userno'] == $records2[0]['userno']
                            ) {
                                //仕事詳細
                                echo "<h2 id=\"worktitle\">"
                                    . h($records2[0]['worktitle']) . "</h2>";
                                if ($records2[0]['content'] == "") {
                                    $records2[0]['content'] = "仕事詳細が空欄です";
                                }
                                $records2[0]['content']
                                    = nl2br(h($records2[0]['content']));
                                echo "<p id=\"content\">"
                                    . $records2[0]['content'] . "</p>";
                                if ($records2[0]['workdatetime'] == "") {
                                    $records2[0]['workdatetime']
                                        = "勤務予定日時が空欄です";
                                }
                                $records2[0]['workdatetime']
                                    = nl2br(h($records2[0]['workdatetime']));
                                echo "<p id=\"workdatetime\">勤務予定日時：</br><span id=\"editworkdatetime\">"
                                    . $records2[0]['workdatetime'] . "</span></p>";
                                echo "<p id=\"place\">住所(自動生成)：</p><!-- jsでappend -->";
                                echo "<div id=\"detail-map\"></div><!-- jsでappend -->";
                                echo "<p>見積もり中</p>";
                            } //上記に当てはまらない場合、情報を公開しない。
                            else {
                                echo "<p>閲覧権限がありません</p>";
                            }
                        }
                    }
                }
            }
        }
        ?>
    </div>
    <?php include("./common/commonFooter.php"); ?>
    <script type="text/javascript" src="js/quotation.js"></script>
    <script>
        setTimeout(function () {
            var bbswidth = screen.width - 100;
            $(".jobreport").attr("style", "display:inline-block; padding:10px; border:1px #ccc solid; margin-left:6px;width:" + bbswidth + "px;");
        }, 10);
        setTimeout(function () {
            $("#navbar_member li:nth-child(1) a").click();
            $("#navbar_admin li:nth-child(1) a").click();
            $("#sorttable").tablesorter({
                sortMultiSortKey: 'altKey',
                headers: {
                    0: {sorter: false},
                    2: {sorter: false}
                }
            });
        }, 100);
        <?php
        if ($vieweradmin) {
            echo "$(\"p#content\").editable({type:\"textarea\", action:\"dblclick\"}, function(e){editcontent("
                . $groupno . "," . $workid . ",e.value);});";
            echo "$(\"span#editworkdatetime\").editable({type:\"textarea\", action:\"dblclick\"}, function(e){editworkdatetime("
                . $groupno . "," . $workid . ",e.value);});";
            echo "$(\"h2#worktitle\").editable({action:\"dblclick\"}, function(e){edittitle("
                . $groupno . "," . $workid . ",e.value);});";
            echo "$(\"#editcontact\").editable({action:\"dblclick\"}, function(e){editcontact("
                . $groupno . "," . $workid . ",e.value);});";
        }
        ?>
    </script>
    <?php $googleMap_callback = "initMap";
    include("./common/googleMapApi.php"); ?>
</div>
</body>
</html>
