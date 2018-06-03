<?php
require_once __DIR__ . '/lib/auth.php';
require_logined_session();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <script type="text/javascript"
            src="js/editable/jquery.editable.min.js"></script>
</head>
<body>


<div data-role="page" id="mypage">
    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    $userno = $_SESSION['userno'];

    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'messagelist.php', '" . date('Y-m-d G:i:s') . "')",
        $con) or die('Error: ' . mysql_error());

    //すでにあるメッセージリストを表示する
    $result2
        = mysql_query("SELECT messageid,messagename,lastupdate FROM message WHERE messageid IN (SELECT messageid FROM messagemember WHERE memberid = '"
        . $userno . "') ORDER BY lastupdate DESC LIMIT 100")
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
        <input type="button" value="新規メッセージボード作成"
               onClick="createNewMessageBoard(<?php echo $userno; ?>);"/>
        <div id="messagelist">
            <?php
            if (count($records2) == 0) {
                echo "まだメッセージボードはありません。";
            } else {
                echo "<ul data-role=\"listview\" data-inset=\"true\">";
                echo "<li data-role=\"list-divider\">メッセージボード一覧</li>";
                $workdate = "2099-12-31";
                foreach ($records2 as $eachrecord) {
                    echo "<li data-theme=\"c\"><a href=\"messageboard.php?messageid="
                        . $eachrecord['messageid'] . "\" rel=\"external\"><h2>"
                        . $eachrecord['messagename'] . "</h2><p>"
                        . $eachrecord['lastupdate'] . "</p></a></li>\n";
                }
                echo "</ul>";
            }
            ?>
        </div>
    </div>

    <script type="text/javascript">
        var userno = <?php echo $userno; ?>;
    </script>
    <?php include("./common/commonFooter.php"); ?>
    <script>
        function createNewMessageBoard(userno) {
            var JSONdata = {
                userno: userno
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(JSONdata),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/createNewMessageBoard.php',
                timeout: 10000,
                success: function (data) {
                    swal({
                            title: "成功",
                            text: "新規メッセージボードを作成しました。クリックするとメッセージページに移動します。",
                            type: "success"
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                window.location.href = "messageboard.php?messageid=" + data.messageid;
                            }
                        });
                },
                error: function () {
                    sweetAlert("エラー", "エラーのため作成できませんでした", "error");
                }
            });
        }
    </script>
</div>
</body>
</html>