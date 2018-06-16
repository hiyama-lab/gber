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


<div data-role="page" id="mypage">
    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'joboffer.php', '" . date('Y-m-d G:i:s') . "')",
        $con) or die('Error: ' . mysql_error());

    $userno = $_SESSION['userno'];
    $groupno = $_GET['groupno'];

    // 本当に所属しているのかは調べないことにする

    // 自分にオファーの来ている仕事をグループごとに取得し，その仕事詳細データをさらに統合する．最後に，グループを混合して日付順で並び替え
    $records = array();
    //職能集団の依頼リストから、status=1かつreportflag=0の案件を取得する。勤務日とかは考慮しない。
    $result = mysql_query("SELECT DISTINCT workdate.workid,worklist.worktitle,worklist.content
 FROM workdate LEFT JOIN worklist ON workdate.workid=worklist.id
  WHERE (workerno IN (SELECT taker FROM caretakerlist WHERE giver='"
        . $_SESSION['userno'] . "') or workerno='" . $_SESSION['userno']
        . "') and worklist.groupno=$groupno and workdate.status='1' and workdate.reportflag='0' ORDER BY workdate.workid")
    or die ("Query error: " . mysql_error());
    while ($row = mysql_fetch_assoc($result)) {
        $records[] = $row;
    }

    mysql_close($con);

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <h2>未記入日報一覧</h2>
        <p><?php echo $groupnamelist[$groupno]; ?>グループ</p>
        <p>※ 勤務後、仕事をクリックして日報を記入してください</p>
        </br>
        <ul data-role="listview" data-inset="true">
            <?php
            if (count($records) == 0) {
                echo "<li>日報未記入の案件はありません</li>";
            } else {
                foreach ($records as $eachwork) {
                    echo "<li data-role=\"list-divider\">一覧</li>\n";
                    echo "<li data-theme=\"c\"><a href=\"quotation.php?workid="
                        . $eachwork['workid'] . "&groupno=" . $groupno
                        . "\" rel=\"external\"><h2>" . h($eachwork['worktitle'])
                        . "</h2><p><strong>" . $groupnamelist[$groupno]
                        . "グループ</strong></p><p>" . h($eachwork['content'])
                        . "</p></a></li>\n";
                }
            }
            ?>
        </ul>
    </div>


    <?php include("./common/commonFooter.php"); ?>
</div>
</body>
</html>
