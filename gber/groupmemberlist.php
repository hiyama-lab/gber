<?php
require_once __DIR__ . '/lib/auth.php';
require_logined_session();
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

    if ($groupno == 0 || $groupno > count($groupnamelist) - 1) {
        echo "グループ名が無効です";
        exit;
    }

    //****** グループメンバーのSESSION_IDかどうか確認 ******//
    $admincheck = mysql_query("SELECT admin FROM grouplist WHERE groupno = '"
        . $groupno . "' and userno = '" . $_SESSION['userno'] . "'")
    or die ("Query error: " . mysql_error());
    if (mysql_num_rows($admincheck) == 0) {
        echo "閲覧権限がありません";
        exit;
    }

    //****** グループ構成員のプロフィール一覧 ******//
    $result
        = mysql_query("SELECT userno,nickname,gender,birthyear FROM db_user WHERE userno IN (SELECT userno FROM grouplist WHERE groupno = '"
        . $groupno . "')") or die ("Query error: " . mysql_error());
    $records = array();
    while ($row = mysql_fetch_assoc($result)) {
        $records[] = $row;
    }

    mysql_close($con);

    ?>


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content"><!-- START OF CONTENT -->
        <h1>メンバー一覧</h1>


        <table id="sorttable">
            <thead>
            <tr>
                <th class="memberprof"></th>
                <th class="memberprof">ID</th>
                <th class="memberprof">ニックネーム</th>
                <th class="memberprof">生年</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($records as $eachmember) {
                $str = "<tr>";
                $str .= "<td><a href=\"mypage.php?userno=" . $eachmember['userno']
                    . "\" rel=\"external\"><img src=\"./model/showuserimage.php?userno="
                    . $eachmember['userno']
                    . "\" onerror=\"this.src='img/noimage.svg';\" width=\"50px\" /></a></td>";
                $str .= "<td class=\"memberprof\">" . $eachmember['userno']
                    . "</td>";
                $str .= "<td class=\"memberprof\">" . $eachmember['nickname']
                    . "</td>";
                $str .= "<td class=\"memberprof\">" . $eachmember['birthyear']
                    . "</td>";
                $str .= "</tr>\n";
                echo $str;
            }
            ?>
            </tbody>
        </table>
        </br>


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