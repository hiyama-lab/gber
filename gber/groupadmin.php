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

<div data-role="page"><!-- WRAPPER -->
    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    $groupno = $_GET['groupno'];
    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'groupadmin.php?groupno=" . $groupno . "', '"
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

    mysql_close($con);

    ?>


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content"><!-- START OF CONTENT -->
        <h1><?php echo $groupnamelist[$groupno] ?>グループ管理画面</h1>
        </br>

        <h2>メンバー管理</h2>
        <ul>
            <li><p><a href="groupcalendar.php?groupno=<?php echo $groupno; ?>"
                      rel="external">カレンダー</a></p></li>
            <li><p><a href="groupmember.php?groupno=<?php echo $groupno; ?>"
                      rel="external">メンバー</a></p></li>
            <!--li><p><a href="">連絡網</a></p></li-->
        </ul>
        </br>

        <h2>仕事管理</h2>
        <ul>
            <!--li><p><a href="groupschedule.php?groupno=<?php echo $groupno; ?>" rel="external">仕事（進行中）</a></p></li-->
            <li><p>
                    <a href="groupscheduleold.php?groupno=<?php echo $groupno; ?>"
                       rel="external">仕事（過去）</a></p></li>
            <!--li><p><a href="" rel="external">顧客管理画面</a></p></li-->
            <!--li><p><a href="" rel="external">実績表示</a></p></li-->
        </ul>
        </br>

        <h2>ヘルプ</h2>
        <ul>
            <li><p><a href="manual/howto_groupadmin.php" rel="external">グループ管理者の作業を行う</a>
                </p></li>
            <li><p><a href="manual/howto_caretaker.php" rel="external">代理人として代理入力する</a>
                </p></li>
        </ul>
        </br>


    </div><!-- end of content -->

    <?php include("./common/commonFooter.php"); ?>
</div><!-- end of wrapper -->
</body>
</html>