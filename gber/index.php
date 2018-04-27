<?php
include __DIR__ . '/lib/sessioncheck.php';
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <link rel="stylesheet" type="text/css" href="js/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="js/slick/slick-theme.css"/>
    <script type="text/javascript" src="js/slick/slick.min.js"></script>
</head>
<body>

<?php
include __DIR__ . '/lib/mysql_credentials.php';

$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $_SESSION['userno'] . "', 'index.php', '" . date('Y-m-d G:i:s') . "')", $con)
or die('Error: ' . mysql_error());

// マスター権限か調べる
$result = mysql_query("SELECT master FROM db_user WHERE userno="
    . $_SESSION['userno']) or die ("Query error: " . mysql_error());
$masterflag = mysql_fetch_assoc($result)['master'] == 1 ? true : false;

//Q&Aカード_ソーシャルアクティビティ用に、未回答質問一覧をダウンロードする
$qanda_social_sql
    = mysql_query("SELECT * FROM questionnaire_socialactivity WHERE userno='"
    . $_SESSION['userno'] . "'") or die ("Query error: " . mysql_error());
while ($qanda_social_row = mysql_fetch_assoc($qanda_social_sql)) {
    $qanda_social = $qanda_social_row;
}

//Q&Aカード_ワーク用に、未興味一覧をダウンロードする。
//本来なら、未来の案件だけ表示するようにしたいが案件数が足りない...
//グループ仕事はとりあえず除外で、サマリー入力済みのもの。
$qanda_work_sql
    = mysql_query("SELECT id,worktitle,summary FROM helplist WHERE id NOT IN (SELECT DISTINCT workid FROM helpmatching WHERE applyuserno='"
    . $_SESSION['userno'] . "') and summary IS NOT NULL") or die ("Query error: "
    . mysql_error());
$qanda_work = array();
while ($qanda_work_row = mysql_fetch_assoc($qanda_work_sql)) {
    $qanda_work[] = $qanda_work_row;
}

// 所属グループを調べる。管理者になっているグループも調べる
$groupmembersql
    = mysql_query("SELECT groupno,admin FROM grouplist WHERE userno='"
    . $_SESSION['userno'] . "' and groupno > 0") or die ("Query error: "
    . mysql_error());
$groupresult = array();
$groupresultflag = false;
while ($groupmemberrow = mysql_fetch_assoc($groupmembersql)) {
    $groupresultflag = true;
    $groupresult[] = $groupmemberrow;
}

//*** 通知用 ***//

//通知用文章は$noticestrに統一。
$noticestr = "";


// グループメンバーで，今日以降の未回答のオファーがあったとき通知(workdate_"groupno"のstatus==0)
$numunansweredoffer = array();
if ($groupresultflag) {
    foreach ($groupresult as $group) {
        $numunansweredoffer[$group['groupno']] = 0;
        $result6 = mysql_query("SELECT dateid FROM workdate LEFT JOIN worklist ON workdate.workid=worklist.id WHERE (workerno IN (SELECT taker FROM caretakerlist WHERE giver='"
            . $_SESSION['userno'] . "') or workerno='" . $_SESSION['userno']
            . "') and worklist.groupno=". $group['groupno'] . " and workdate.status='0' and workdate.workday > DATE_SUB(CURRENT_DATE(),interval 1 day)")
        or die ("Query error: " . mysql_error());
        $numunansweredoffer[$group['groupno']] = mysql_num_rows($result6);
        if ($numunansweredoffer[$group['groupno']] > 0) {
            $noticestr .= "<p><a href=\"jobofferlist.php?groupno="
                . $group['groupno'] . "\" rel=\"external\">オファーが未回答です</a></p>";
        }
    }
}


// グループメンバーで，今日までで未入力の日報があったとき通知(workdateの，今日>workday && status=1 && reportflag=0)
$numunwrittenreport = array();
if ($groupresultflag) {
    foreach ($groupresult as $group) {
        $numunwrittenreport[$group['groupno']] = 0;
        $result11 = mysql_query("SELECT dateid FROM workdate LEFT JOIN worklist ON workdate.workid=worklist.id WHERE (workerno IN (SELECT taker FROM caretakerlist WHERE giver='"
            . $_SESSION['userno'] . "') or workerno='" . $_SESSION['userno']
            . "') and worklist.groupno=". $group['groupno'] . " and workdate.status='1' and reportflag='0' and workdate.workday < CURRENT_DATE()")
        or die ("Query error: " . mysql_error());
        $numunwrittenreport[$group['groupno']] = mysql_num_rows($result11);
        if ($numunwrittenreport[$group['groupno']] > 0) {
            $noticestr .= "<p><a href=\"jobreportlist.php?groupno="
                . $group['groupno'] . "\" rel=\"external\">日報が未記入です</a></p>";
        }
    }
}


// 24時間以内に掲示板に新規投稿があれば表示
$numnewbbspost = array();
$numnewbbspost[0] = 0;
$result7
    = mysql_query("SELECT messageid FROM bbs_group WHERE datetime > DATE_SUB(CURRENT_DATE(),interval 1 day) AND groupno=0")
or die ("Query error: " . mysql_error());
$numnewbbspost[0] = mysql_num_rows($result7);
if ($numnewbbspost[0] > 0) {
    $noticestr .= "<p><a href=\"groupbbs.php?groupno=0\" rel=\"external\">全体掲示板に新規投稿があります</a></p>";
}
if ($groupresultflag) {
    foreach ($groupresult as $group) {
        $numnewbbspost[$group['groupno']] = 0;
        $result7 = mysql_query("SELECT messageid FROM bbs_group WHERE datetime > DATE_SUB(CURRENT_DATE(),interval 1 day) AND groupno=" . $group['groupno'])
        or die ("Query error: " . mysql_error());
        $numnewbbspost[$group['groupno']] = mysql_num_rows($result7);
        if ($numnewbbspost[$group['groupno']] > 0) {
            $noticestr .= "<p><a href=\"groupbbs.php?groupno=" . $group['groupno']
                . "\" rel=\"external\">" . $groupnamelist[$group['groupno']]
                . "G掲示板に新規投稿があります</a></p>";
        }
    }
}


// 24時間以内に新規メッセージがあれば表示
$numnewmessage = 0;
$result8
    = mysql_query("SELECT messageeach.messageeachid FROM messageeach LEFT JOIN messagemember ON messageeach.messageid = messagemember.messageid WHERE messageeach.messagedate > DATE_SUB(CURRENT_DATE(),interval 1 day) and messagemember.memberid='"
    . $_SESSION['userno'] . "'") or die ("Query error: " . mysql_error());
$numnewmessage = mysql_num_rows($result8);
if ($numnewmessage > 0) {
    $noticestr .= "<p><a href=\"messagelist.php\" rel=\"external\">新規メッセージがあります</a></p>";
}


// アンケートに回答済みかどうか取得する。

$resultinterestuserlist
    = mysql_query("SELECT socialactivityid FROM interest_user_list WHERE userno = '"
    . $_SESSION['userno'] . "'") or die ("Query error: " . mysql_error());
$numinterestuserlist = mysql_num_rows($resultinterestuserlist);


mysql_close($con);
?>

<div data-role="page">


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <div id="toplogo">
            <img src="./img/logo1.svg"/><br>
        </div>
        <!--h2 class="h2design">通知</h2-->
        <div id="notification">
            <?php
            //通知文章はここで表示
            if ($noticestr === "") {
                echo "<p>新着通知はありません。</p>";
            } else {
                echo $noticestr;
            }
            ?>
        </div>
        <?php
        if ($qanda_social['answered'] < 72) {
            include("./common/qandacard_socialactivity.php");
        } else {
            echo "<p>興味調査回答済みです。ご協力ありがとうございました。</p>";
            include("./common/qandacard_work.php");
        }
        ?>


        <!-- グループに所属している人にだけ表示するように変更 -->
        <?php
        //グループメンバーなら
        if ($groupresultflag) {
            foreach ($groupresult as $eachgroup) {

                echo "<ul data-role=\"listview\" data-inset=\"true\">";


                //リストの分割
                echo "<li data-role=\"list-divider\">"
                    . $groupnamelist[$eachgroup['groupno']] . "グループ</li>";

                //管理者なら、仕事登録画面を表示
                if ($eachgroup['admin'] == 1) {
                    if ($masterflag) {
                        echo "<span data-theme=\"c\" data-role=\"collapsible\" data-collapsed=\"true\" data-iconpos=\"right\" data-inset=\"true\" style=\"margin-bottom: -1px;\"><h3>管理者機能</h3>";
                    } else {
                        echo "<span data-theme=\"c\" data-role=\"collapsible\" data-collapsed=\"false\" data-iconpos=\"right\" data-inset=\"true\" style=\"margin-bottom: -1px;\"><h3>管理者機能</h3>";
                    }
                    echo "<ul data-role=\"listview\" data-inset=\"false\">";
                    echo "<li data-theme=\"c\"><a href=\"uploadspecialist.php?groupno="
                        . $eachgroup['groupno']
                        . "\" rel=\"external\"><img src=\"img/index/number1.svg\" class=\"list-icon\"><h2>仕事の登録</h2><p>仕事を登録します</p></a></li>";
                    echo "<li data-theme=\"c\"><a href=\"groupmitsumorilist.php?groupno="
                        . $eachgroup['groupno']
                        . "\" rel=\"external\"><img src=\"img/index/number2.svg\" class=\"list-icon\"><h2>見積もり</h2><p>仕事を遂行するか決定します</p></a></li>";
                    echo "<li data-theme=\"c\"><a href=\"groupwarifurilist.php?groupno="
                        . $eachgroup['groupno']
                        . "\" rel=\"external\"><img src=\"img/index/number3.svg\" class=\"list-icon\"><h2>作業割り振り・日報記入</h2><p>作業を割り振り日報を記入させます</p></a></li>";
                    echo "<li data-theme=\"c\"><a href=\"grouphyoukalist.php?groupno="
                        . $eachgroup['groupno']
                        . "\" rel=\"external\"><img src=\"img/index/number4.svg\" class=\"list-icon\"><h2>メンバーフィードバック</h2><p>メンバーにフィードバックを行います</p></a></li>";
                    echo "<li data-theme=\"c\"><a href=\"groupadmin.php?groupno="
                        . $eachgroup['groupno']
                        . "\" rel=\"external\"><img src=\"img/index/group.svg\" class=\"list-icon\"><h2>その他グループ管理</h2><p>メンバー管理、カレンダー確認、過去ログ</p></a></li>";
                    echo "</ul></span>";
                }

                if ($eachgroup['admin'] == 1) {
                    echo "<span data-theme=\"c\" data-role=\"collapsible\" data-collapsed=\"true\" data-iconpos=\"right\" data-inset=\"true\"><h3>メンバー機能</h3><ul data-role=\"listview\" data-inset=\"false\">";
                } else {
                    echo "<span data-theme=\"c\" data-role=\"collapsible\" data-collapsed=\"false\" data-iconpos=\"right\" data-inset=\"true\"><h3>メンバー機能</h3><ul data-role=\"listview\" data-inset=\"false\">";
                }
                echo "<li data-theme=\"c\"><a href=\"calendar.php?userno="
                    . $_SESSION['userno']
                    . "\" rel=\"external\"><img src=\"img/index/number1.svg\" class=\"list-icon\"><h2>勤務可能日を記入</h2><p>事前に勤務可能日を記入します</p></a></li>";
                echo "<li data-theme=\"c\"><a href=\"jobofferlist.php?groupno="
                    . $eachgroup['groupno']
                    . "\" rel=\"external\"><img src=\"img/index/number2.svg\" class=\"list-icon\"><h2>オファーを受ける</h2><p>作業のオファーを承諾/棄却します</p><span class=\"ui-li-count\">"
                    . $numunansweredoffer[$eachgroup['groupno']]
                    . "</span></a></li>";
                echo "<li data-theme=\"c\"><a href=\"jobreportlist.php?groupno="
                    . $eachgroup['groupno']
                    . "\" rel=\"external\"><img src=\"img/index/number3.svg\" class=\"list-icon\"><h2>日報を記入</h2><p>勤務後、日報を記入します</p><span class=\"ui-li-count\">"
                    . $numunwrittenreport[$eachgroup['groupno']]
                    . "</span></a></li>";
                echo "<li data-theme=\"c\"><a href=\"groupbbs.php?groupno="
                    . $eachgroup['groupno']
                    . "\" rel=\"external\"><img src=\"img/index/bulletin.svg\" class=\"list-icon\"><h2>掲示板</h2><p>グループの情報共有</p><span class=\"ui-li-count\">"
                    . $numnewbbspost[$eachgroup['groupno']] . "</span></a></li>";
                echo "<li data-theme=\"c\"><a href=\"groupmemberworklist.php?groupno="
                    . $eachgroup['groupno']
                    . "\" rel=\"external\"><img src=\"img/index/introjobs.svg\" class=\"list-icon\"><h2>仕事一覧</h2><p>グループで進行中の仕事の一覧です</p></a></li>";
                echo "<li data-theme=\"c\"><a href=\"groupmemberfunc.php?groupno="
                    . $eachgroup['groupno']
                    . "\" rel=\"external\"><img src=\"img/index/briefcase.svg\" class=\"list-icon\"><h2>その他機能</h2><p>勤務記録、メンバー一覧</p></a></li>";
                echo "</ul></span>";


                echo "</ul>";

            }
        }
        ?>


        <ul data-role="listview" data-inset="true">


            <li data-role="list-divider">仕事/イベント</li>
            <li data-theme="c"><a href="uploadjob.php" rel="external"><img
                            src="img/index/crowd.svg" class="list-icon">
                    <h2>募集</h2>
                    <p>仕事/イベントを掲示できます</p></a></li>
            <li data-theme="c"><a href="searchjobmapcushion.php" rel="external"><img
                            src="img/index/map.svg" class="list-icon">
                    <h2>応募</h2>
                    <p>日付を指定し地図上で探します</p></a></li>
            <li data-theme="c"><a href="groupbbs.php?groupno=0"
                                  rel="external"><img
                            src="img/index/bulletin.svg" class="list-icon">
                    <h2>全体掲示板</h2>
                    <p>情報共有ができます</p><span
                            class="ui-li-count"><?php echo $numnewbbspost[0]; ?></span></a>
            </li>


        </ul>
        <ul data-role="listview" data-inset="true">


            <li data-role="list-divider">予定</li>
            <li data-theme="c"><a
                        href="calendar.php?userno=<?php echo $_SESSION['userno']; ?>"
                        rel="external"><img src="img/index/cal.svg" class="list-icon">
                    <h2>勤務可能日を記入</h2>
                    <p>オファーの受託用に予定を記入</p></a></li>
            <li data-theme="c"><a href="schedule.php" rel="external"><img
                            src="img/index/todo.svg" class="list-icon">
                    <h2>予定を確認</h2>
                    <p>今後の募集，応募の一覧を確認</p></a></li>


        </ul>
        <ul data-role="listview" data-inset="true">


            <li data-role="list-divider">その他</li>
            <li data-theme="c"><a href="messagelist.php" rel="external"><img
                            src="img/index/chat.svg" class="list-icon">
                    <h2>メッセージボード</h2>
                    <p>個人間、グループメッセージ</p><span
                            class="ui-li-count"><?php echo $numnewmessage; ?></span></a>
            </li>
            <li data-theme="c"><a
                        href="mypage.php?userno=<?php echo $_SESSION['userno']; ?>"
                        rel="external"><img src="./img/index/indexuser.svg"
                                            onerror="this.src='img/noimage.svg';"
                                            class="list-icon"/>
                    <h2>マイページ</h2>
                    <p>プロフィールやスキルを閲覧</p></a></li>
            <li data-theme="c"><a href="help.php?register=false" rel="external"><img
                            src="img/index/question.svg" class="list-icon">
                    <h2>使い方を確認</h2>
                    <p>マニュアルをダウンロード(PDF)</p></a></li>
            <?php
            if ($masterflag) {
                echo "<li data-theme=\"c\"><a href=\"masterpage.php\" rel=\"external\"><img src=\"img/index/database.svg\" class=\"list-icon\"><h2>マスターページ</h2><p>データベースの管理</p></a></li>";
            }
            ?>

        </ul>
    </div><!--end of content-->

    <?php include("./common/commonFooter.php"); ?>
</div>
</body>
</html>
