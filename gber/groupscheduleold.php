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

    //****** 過去の依頼を一覧で取得する．ステータス順でソート ******//
    $sql5 = "SELECT * FROM worklist WHERE groupno = $groupno AND status = 4 ORDER BY id DESC LIMIT 100";
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
        <h1>グループ仕事管理(終了)</h1>

        <div id="jobs">
            <input type="search" name="search" id="search"
                   placeholder="検索用キーワードを入力してください"/>
            <?php
            if (count($records5) == 0) {
                echo "<p>終了した仕事はありません</p>";
            } else {
                echo "<ul data-role=\"listview\" id=\"jobul\" data-inset=\"true\">\n";
                echo "<li data-role=\"list-divider\">終了案件</li>\n";
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
    <script>
        $(document).ready(function () {
            $("#search").keyup(function (e) {
                e.preventDefault();
                ajax_search();
            });
        });

        function ajax_search() {
            var search_val = $("#search").val();
            var groupno =<?php echo $groupno; ?>;
            $.post(baseurl + 'model/findOldJob.php', {
                search: search_val,
                groupno: groupno
            }, function (data) {
                if (data.length > 0) {
                    $("#jobul").html(data);
                }
            });
        }
    </script>
</div><!-- end of wrapper -->
</body>
</html>