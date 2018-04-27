<?php
include __DIR__ . '/lib/sessioncheck.php';
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
        . $_SESSION['userno'] . "', 'groupmemberlist.php?groupno=" . $groupno . "', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    if ($groupno > count($groupnamelist) - 1) {
        echo "グループ名が無効です";
        exit;
    }

    $userno = $_SESSION['userno'];
    if ($_GET['userno'] != "") {
        $userno = $_GET['userno'];
    }

    if ($groupno == 0) {

//****** 自分の興味あり一覧 ******//
        $result
            = mysql_query("SELECT worktitle,id FROM helplist WHERE id IN (SELECT DISTINCT workid FROM helpmatching WHERE applyuserno = "
            . $userno . " and interest=1) ORDER BY id DESC")
        or die ("Query error: " . mysql_error());
        $records = array();
        while ($row = mysql_fetch_assoc($result)) {
            $records[] = $row;
        }

    } else {

//****** グループメンバーのSESSION_IDかどうか確認 ******//
        $admincheck
            = mysql_query("SELECT admin FROM grouplist WHERE groupno = '"
            . $groupno . "' and userno = '" . $_SESSION['userno'] . "'")
        or die ("Query error: " . mysql_error());
        if (mysql_num_rows($admincheck) == 0) {
            echo "閲覧権限がありません";
            exit;
        }

//****** 自分の仕事履歴一覧 ******//
        $result = mysql_query("SELECT worktitle,id FROM worklist WHERE groupno = $groupno AND id IN (SELECT DISTINCT workid FROM workdate WHERE workerno = " . $userno . ") ORDER BY id DESC")
        or die ("Query error: " . mysql_error());
        $records = array();
        while ($row = mysql_fetch_assoc($result)) {
            $records[] = $row;
        }

//****** 自分の勤務回数 ******//
        $result2 = mysql_query("SELECT DISTINCT workday FROM workdate LEFT JOIN worklist ON workdate.workid=worklist.id WHERE worklist.groupno = $groupno AND workdate.workerno = " . $userno . "") or die ("Query error: "
            . mysql_error());
        $records2 = array();
        while ($row2 = mysql_fetch_assoc($result2)) {
            $records2[] = $row2;
        }

    }

    mysql_close($con);

    ?>


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content"><!-- START OF CONTENT -->
        <?php
        if ($groupno == 0) {
            echo "<h2>興味あり " . count($records) . "件</h2>";
            echo "<h3>興味あり一覧</h3>";
            foreach ($records as $eachrecord) {
                echo "<p><a href=\"jobdetail.php?workid=" . $eachrecord['id']
                    . "\" rel=\"external\">" . $eachrecord['worktitle'] . "</a></p>";
            }
        } else {
            echo "<h2>" . $groupnamelist[$groupno] . "グループ</h2>";
            echo "<h2>勤務回数" . count($records) . "件(" . count($records2) . "回）</h2>";
            echo "<h3>勤務記録一覧</h3>";
            foreach ($records as $eachrecord) {
                echo "<p><a href=\"quotation.php?groupno=" . $groupno . "&workid="
                    . $eachrecord['id'] . "\" rel=\"external\">"
                    . $eachrecord['worktitle'] . "</a></p>";
            }
        }
        ?>


    </div>


</div><!-- end of content -->


<?php include("./common/commonFooter.php"); ?>
<script>
    setTimeout(function () {
        $("#sorttable").tablesorter({
            sortMultiSortKey: 'altKey',
            headers: {
                0: {sorter: false}
            }
        });
    }, 100);
</script>
</div><!-- end of wrapper -->
</body>
</html>