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

    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'schedule.php', '" . date('Y-m-d G:i:s') . "')",
        $con) or die('Error: ' . mysql_error());

    $userno = array();
    $userno[] = $_SESSION['userno'];

    // 自分が代理人を務めるユーザIDの一覧を取得してusernoに追加する
    // グループへの"応募"に限り使用
    $result11 = mysql_query("SELECT taker FROM caretakerlist WHERE giver='"
        . $_SESSION['userno'] . "'") or die ("Query error: " . mysql_error());
    while ($row11 = mysql_fetch_assoc($result11)) {
        $userno[] = $row11['taker'];
    }


    /****** 応募案件 ******/
    //"興味あり"の案件で今日以降のものを日付順に並び替えて$recordsに入れる
    $records = array();
    $demosql = $_ENV["IS_DEMO"] === 'true' ? "" : "and helpdate.workdate > DATE_SUB(CURRENT_DATE(),interval 1 day)";
    $result
        = mysql_query("SELECT helplist.id,helplist.worktitle,helplist.content,helpdate.workdate FROM helplist INNER JOIN helpdate ON helplist.id = helpdate.workid WHERE helplist.id IN (SELECT workid FROM helpmatching WHERE applyuserno = '"
        . $_SESSION['userno']
        . "' and interest = '1') and helplist.status<4 $demosql ORDER BY helpdate.workdate LIMIT 100")
    or die ("Query error: " . mysql_error());
    while ($row = mysql_fetch_assoc($result)) {
        $records[] = $row;
    }

    //グループ応募案件の取得
    //所属グループの確認。
    //処理高速化のために代理人は操作者の所属グループに内包されていると仮定
    $records8 = array();
    $specialistflag = false;
    $result8
        = mysql_query("SELECT DISTINCT groupno FROM grouplist WHERE groupno > 0 and userno='"
        . $_SESSION['userno'] . "'") or die ("Query error: " . mysql_error());
    while ($row8 = mysql_fetch_assoc($result8)) {
        $records8[] = $row8['groupno'];
        $specialistflag = true;
    }
    //グループごとにどのようなオファーや応募があったか取得
    $records9 = array();
    if ($specialistflag) {
        $i = 0;
        foreach ($records8 as $eachgroupno) {
            foreach ($userno as $eachuser) {
                $result9 = mysql_query("SELECT * FROM workdate LEFT JOIN worklist ON workdate.workid=worklist.id WHERE worklist.groupno=$eachgroupno AND workdate.workerno='" . $eachuser
                    . "' and workday>DATE_SUB(CURRENT_DATE(),interval 1 day)")
                or die ("Query error: " . mysql_error());
                while ($row9 = mysql_fetch_assoc($result9)) {
                    $records9[] = $row9;
                    $records9[$i]['groupno'] = $eachgroupno;
                    $i++;
                }
            }
        }
        $i = 0;
        foreach ($records9 as $eachrecord) {
            $result10
                = mysql_query("SELECT worktitle,content,userno,status FROM worklist WHERE id='" . $eachrecord['workid'] . "'")
            or die ("Query error: " . mysql_error());
            while ($row10 = mysql_fetch_assoc($result10)) {
                $records9[$i]['worktitle'] = $row10['worktitle'];
                $records9[$i]['content'] = $row10['content'];
                $records9[$i]['userno'] = $row10['userno'];
                $records9[$i]['status'] = $row10['status'];
                $i++;
            }
        }
        if (count($records9) > 0) {
            foreach ($records9 as $key => $value) {
                $key_id[$key] = $value['workday'];
            }
            array_multisort($key_id, SORT_ASC, $records9);
        }
    }
    /****** 募集案件 ******/

    // 今日以降の一般募集案件を日付順に並び替えて$records2に入れる
    $result2
        = mysql_query("SELECT helplist.id,helplist.worktitle,helplist.content,helplist.status,helpdate.workdate FROM helplist INNER JOIN helpdate ON helplist.id = helpdate.workid WHERE helplist.userno = '"
        . $_SESSION['userno']
        . "' and status < 4 $demosql ORDER BY helpdate.workdate LIMIT 100")
    or die ("Query error: " . mysql_error());
    $records2 = array();
    while ($row2 = mysql_fetch_assoc($result2)) {
        $records2[] = $row2;
    }

    mysql_close($con);

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <div data-role="tabs" id="tabs">
            <div data-role="navbar" id="navbar">
                <ul>
                    <li><a href="#apply" data-ajax="false">応募</a></li>
                    <?php if ($specialistflag) {
                        echo "<li><a href=\"#group\" data-ajax=\"false\">グループ応募</a></li>";
                    } ?>
                    <li><a href="#search" data-ajax="false">募集</a></li>
                </ul>
            </div>

            <!-- 応募案件 -->
            <div id="apply">
                <h3>興味ありした応募案件のスケジュール</h3>
                <ul data-role="listview" data-inset="true">
                    <?php
                    if (count($records) == 0) {
                        echo "<li data-theme=\"c\">「興味あり」に登録した案件はありません</li>";
                    } else {
                        $workdate = "2000-01-01";
                        foreach ($records as $eachwork) {
                            if ($eachwork['workdate'] > $workdate) {
                                // 日付で区切り線を入れる
                                echo "<li data-role=\"list-divider\" data-theme=\"c\">"
                                    . $eachwork['workdate'] . "</li>\n";
                                $workdate = $eachwork['workdate'];
                            }
                            echo "<li data-theme=\"c\"><a href=\"jobdetail.php?workid="
                                . $eachwork['id'] . "\" rel=\"external\"><h2>"
                                . $eachwork['worktitle'] . "</h2><p>"
                                . $eachwork['content'] . "</p></a></li>\n";
                        }
                    }
                    ?>
                </ul>

                <?php
                // 応募はグループに属している人にだけ表示
                if ($specialistflag) {
                    echo "</div><div id=\"group\">";
                    echo "<h3>グループ勤務予定</h3>";
                    echo "<ul data-role=\"listview\" data-inset=\"true\">";
                    if (count($records9) == 0) {
                        echo "<li>現在勤務予定はありません</li>";
                    } else {
                        $status = [
                            "見積書作成中",
                            "",
                            "<font color=\"red\">要勤務or要日報記入</font>",
                            "仕事終了",
                            "仕事終了",
                            "削除済み",
                        ];
                        $workday = "2000-01-01";
                        foreach ($records9 as $eachwork) { //あらかじめ日付順でソートされている
                            if ($eachwork['status'] < 3) {
                                if ($eachwork['workday'] > $workday) {
                                    // 日付で区切り線を入れる
                                    echo "<li data-role=\"list-divider\" data-theme=\"c\">"
                                        . $eachwork['workday'] . "</li>\n";
                                    $workday = $eachwork['workday'];
                                }
                                echo "<li data-theme=\"c\"><a href=\"quotation.php?workid="
                                    . $eachwork['workid'] . "&groupno="
                                    . $eachwork['groupno']
                                    . "\" rel=\"external\"><h2>"
                                    . $eachwork['worktitle'] . "</h2><p><strong>"
                                    . $status[$eachwork['status']] . "　"
                                    . $groupnamelist[$eachwork['groupno']]
                                    . "グループ</strong></p><p>" . $eachwork['content']
                                    . "</p></a></li>\n";
                            }
                        }
                    }
                    echo "</ul>";
                }
                ?>

            </div><!-- end of apply -->


            <!-- 募集案件 -->
            <div id="search">
                <h3>全体募集</h3>
                <ul data-role="listview" data-inset="true">
                    <?php
                    if (count($records2) == 0) {
                        echo "<li>現在募集していません</li>\n";
                    } else { // 募集があれば，整形して日付順に区切って表示
                        $workdate = "2000-01-01";
                        foreach ($records2 as $eachwork) {
                            if ($eachwork['workdate'] > $workdate) {
                                // 日付で区切り線を入れる
                                echo "<li data-role=\"list-divider\" data-theme=\"c\">"
                                    . $eachwork['workdate'] . "</li>\n";
                                $workdate = $eachwork['workdate'];
                            }
                            echo "<li data-theme=\"c\"><a href=\"jobdetail.php?workid="
                                . $eachwork['id'] . "\" rel=\"external\"><h2>"
                                . $eachwork['worktitle'] . "</h2><p>"
                                . $eachwork['content'] . "</p></a></li>\n";
                        }
                    }
                    ?>
                </ul>
            </div><!-- end of #search -->

        </div>

        <?php include("./common/commonFooter.php"); ?>
        <script>
            setTimeout(function () {
                $("#navbar li:nth-child(1) a").click();
            }, 100);
        </script>
    </div>
</body>
</html>
