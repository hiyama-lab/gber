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

<div data-role="page"><!-- WRAPPER -->
    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    $groupno = $_GET['groupno'];
    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'groupschedule.php?groupno=" . $groupno . "', '"
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

    //****** 依頼を一覧で取得する．ステータス順でソート ******//
    $sql5 = "SELECT * FROM worklist WHERE groupno = $groupno AND status < 4 ORDER BY status LIMIT 100";
    $result5 = mysql_query($sql5) or die ("Query error: " . mysql_error());
    $records5 = array();
    while ($row5 = mysql_fetch_assoc($result5)) {
        $records5[] = $row5;
    }

    mysql_close($con);

    ?>


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content"><!-- START OF CONTENT -->
        <h1>グループ仕事管理(進行中)</h1>

        <div id="jobs">
            <?php
            if (count($records5) == 0) {
                echo "<p>現在依頼はありません</p>";
            } else {
                $i = 0;
                $searchstatus = [
                    "<font color=\"red\">要見積書作成</font>",
                    "見積書承認待ち",
                    "<font color=\"red\">成約済。要ワーカーの選択</font>",
                    "<font color=\"red\">要評価</font>",
                ];
                echo "<ul data-role=\"listview\" data-inset=\"true\">\n";
                if ($records5[0]['status'] == 0) {
                    echo "<li data-role=\"list-divider\">" . $searchstatus[0]
                        . "</li>\n";
                }
                foreach ($records5 as $eachrequest) {
                    if ($eachrequest['status'] > $i) {
                        echo "<li data-role=\"list-divider\">"
                            . $searchstatus[$eachrequest['status']] . "</li>\n";
                        $i = $eachrequest['status'];
                    }
                    echo "<li data-theme=\"c\"><a href=\"quotation.php?workid="
                        . $eachrequest['id'] . "&groupno=" . $groupno
                        . "\" rel=\"external\"><h2>" . $eachrequest['worktitle']
                        . "</h2><p>" . $eachrequest['content'] . "</p></a></li>\n";
                }
                echo "</ul>\n";
            }
            ?>
        </div>


    </div><!-- end of content -->


    <?php include("./common/commonFooter.php"); ?>
</div><!-- end of wrapper -->
</body>
</html>