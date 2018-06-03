<?php
require_once __DIR__ . '/lib/auth.php';
require_logined_session();
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
require_once __DIR__ . '/lib/db.php';

$userno = $_SESSION['userno'];
$db = DB::getInstance();
$db->addToActivityLog($userno, 'index.php');
$masterflag = $db->isMaster($userno);
//Q&Aカード_ソーシャルアクティビティ用に、未回答質問一覧をダウンロードする
$qanda_social = $db->getQandaSocial($userno);
//Q&Aカード_ワーク用に、未興味一覧をダウンロードする。本来なら、未来の案件だけ表示するようにしたいが案件数が足りない...グループ仕事はとりあえず除外で、サマリー入力済みのもの。
$qanda_work = $db->getQandaWorks($userno);
$groupresult = $db->getGroupInfo($userno);
$groupresultflag = count($groupresult) > 0 ? true : false;

//*** 通知用 ***//
$noticestr = "";

// 24時間以内に全体掲示板に新規投稿があれば表示
$numnewbbspost = array();
$numnewbbspost[0] = 0;
$numnewbbspost[0] = $db->getBbsNewPostNum(0);
if ($numnewbbspost[0] > 0) {
    $noticestr .= "<p><a href=\"groupbbs.php?groupno=0\" rel=\"external\">全体掲示板に新規投稿があります</a></p>";
}

$numunansweredoffer = array();
$numunwrittenreport = array();
if ($groupresultflag) {
    foreach ($groupresult as $group) {
        $groupno = $group['groupno'];
        // グループメンバーで，今日以降の未回答のオファーがあったとき通知(workdate_"groupno"のstatus==0)
        $numunansweredoffer[$groupno] = $db->getUnansweredOfferNum($userno, $groupno);
        if ($numunansweredoffer[$groupno] > 0) {
            $noticestr .= "<p><a href=\"jobofferlist.php?groupno="
                . $groupno . "\" rel=\"external\">オファーが未回答です</a></p>";
        }
        // グループメンバーで，今日までで未入力の日報があったとき通知(workdateの，今日>workday && status=1 && reportflag=0)
        $numunwrittenreport[$groupno] = $db->getUnwrittenReportNum($userno, $groupno);
        if ($numunwrittenreport[$groupno] > 0) {
            $noticestr .= "<p><a href=\"jobreportlist.php?groupno="
                . $groupno . "\" rel=\"external\">日報が未記入です</a></p>";
        }
        // 24時間以内にグループ掲示板に新規投稿があれば表示
        $numnewbbspost[$groupno] = $db->getBbsNewPostNum($groupno);
        if ($numnewbbspost[$groupno] > 0) {
            $noticestr .= "<p><a href=\"groupbbs.php?groupno=" . $groupno
                . "\" rel=\"external\">" . $groupnamelist[$groupno]
                . "グループ掲示板に新規投稿があります</a></p>";
        }
    }
}

// 24時間以内に新規メッセージがあれば表示
$numnewmessage = $db->getNewMessageNum($userno);
if ($numnewmessage > 0) {
    $noticestr .= "<p><a href=\"messagelist.php\" rel=\"external\">新規メッセージがあります</a></p>";
}
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
