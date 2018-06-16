<?php
require_once __DIR__ . '/lib/auth.php';
require_logined_session();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <!-- インライン編集用 -->
    <script type="text/javascript"
            src="js/editable/jquery.editable.min.js"></script>
</head>
<body>

<div data-role="page"><!-- WRAPPER -->
    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    //****** IDを確認 ******//
    if (!authorize($_SESSION['userno'], ROLE['GLOBAL_MASTER'], ['isapi' => false])){
        echo "閲覧権限がありません";
        exit;
    }

    //****** サマリーが記入されていない仕事一覧 ******//
    $result
        = mysql_query("SELECT worktitle,id,content,summary FROM helplist ORDER BY id DESC")
    or die ("Query error: " . mysql_error());
    $worklist = array();
    while ($row = mysql_fetch_assoc($result)) {
        $worklist[] = $row;
    }

    mysql_close($con);

    ?>


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content"><!-- START OF CONTENT -->


        <h2>未サマリー仕事一覧</h2>

        <h3>全体</h3>
        <div style="width:100%; overflow-x:scroll;" data-role="none"
             data-enhance="false">
            <table class="table table-bordered"
                   data-resizable-columns-id="demo-table" data-role="table"
                   data-enhance="false">
                <thead>
                <tr>
                    <th data-resizable-column-id="summary"
                        style="text-align:center; padding: 8px;">サマリー(50文字以内)
                    </th>
                    <th data-resizable-column-id="count" style="padding: 8px;">
                        文字数
                    </th>
                    <th data-resizable-column-id="title" style="padding: 8px;">
                        タイトル
                    </th>
                    <th data-resizable-column-id="content"
                        style="text-align:center; padding: 8px;">詳細
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($worklist as $eachwork) {
                    echo "<tr>";
                    if ($eachwork['summary'] == "") {
                        $eachwork['summary'] = "未入力";
                    }
                    echo "<td style=\"padding: 8px;\"><span id=\""
                        . $eachwork['id'] . "\">" . h($eachwork['summary'])
                        . "</span></td><script>$(\"span#" . $eachwork['id']
                        . "\").editable({action:\"dblclick\"}, function(e){editsummary("
                        . $eachwork['id'] . ",e.value);});</script>";
                    echo "<td style=\"padding: 8px;\">"
                        . mb_strlen(h($eachwork['summary'])) . "</td>";
                    echo "<td style=\"padding: 8px;\"><a href=\"jobdetail.php?workid="
                        . $eachwork['id'] . "\" rel=\"external\">"
                        . h($eachwork['worktitle']) . "</a></td>";
                    echo "<td style=\"padding: 8px;\">"
                        . nl2br(h($eachwork['content'])) . "</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

    </div>


</div><!-- end of content -->

<script>
    function editsummary(workid, newcontent) {
        var JSONdata = {
            workid: workid,
            newcontent: newcontent
        };
        if (newcontent != "" && newcontent != "未入力") {
            $.ajax({
                type: 'POST',
                data: JSON.stringify(JSONdata),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/editSummary.php',
                timeout: 10000,
                error: function () {
                    sweetAlert("エラー", "エラーのため更新できませんでした", "error");
                }
            });
        }
    }

    $(function () {
        $("table").resizableColumns({
            store: window.store
        });
    });
    $.mobile.ignoreContentEnabled = true;
</script>

<?php include("./common/commonFooter.php"); ?>
</div><!-- end of wrapper -->
</body>
</html>