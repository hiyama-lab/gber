<?php
include __DIR__ . '/lib/sessioncheck.php';
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
    $sql5 = "SELECT * FROM worklist WHERE groupno = $groupno AND status = 3 LIMIT 100";
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
        <h1>④ メンバーフィードバック待ち案件一覧</h1>
        <p><a href="groupwarifurilist.php?groupno=<?php echo $groupno; ?>"
              rel="external">③作業割り振り・日報記入一覧に戻る</a></p>
        </br>
        <p>※ 下にメンバーフィードバック待ちの案件が表示されています。</p>
        <p>※ 各仕事をクリックし、メンバーへのフィードバックを記入してください。</p>
        </br>

        <div id="jobs">
            <?php
            if (count($records5) == 0) {
                echo "<p>現在フィードバック待ちの案件はありません</p>";
            } else {
                echo "<ul data-role=\"listview\" data-inset=\"true\">\n";
                foreach ($records5 as $eachrequest) {
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