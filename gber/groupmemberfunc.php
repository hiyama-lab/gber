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
        . $_SESSION['userno'] . "', 'groupadmin.php?groupno=" . $groupno . "', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    if ($groupno == 0 || $groupno > count($groupnamelist) - 1) {
        echo "グループ名が無効です";
        exit;
    }

    //****** メンバーのSESSION_IDかどうか確認 ******//
    $membercheck = mysql_query("SELECT admin FROM grouplist WHERE groupno = '"
        . $groupno . "' and userno = '" . $_SESSION['userno'] . "'")
    or die ("Query error: " . mysql_error());
    if (mysql_num_rows($membercheck) == 0) {
        echo "グループメンバーではありません";
        exit;
    }

    mysql_close($con);

    ?>


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content"><!-- START OF CONTENT -->
        <h1><?php echo $groupnamelist[$groupno] ?>グループメンバー画面</h1>
        </br>

        <ul>
            <li><p>
                    <a href="groupmemberrecord.php?groupno=<?php echo $groupno; ?>"
                       rel="external">勤務記録</a></p></li>
            <li><p><a href="groupmemberlist.php?groupno=<?php echo $groupno; ?>"
                      rel="external">メンバー一覧</a></p></li>
        </ul>
        </br>


    </div><!-- end of content -->

    <?php include("./common/commonFooter.php"); ?>
</div><!-- end of wrapper -->
</body>
</html>