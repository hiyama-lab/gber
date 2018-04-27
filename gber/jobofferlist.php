<?php
include __DIR__ . '/lib/sessioncheck.php';
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
    //職能集団の依頼リストから"今日以降"かつ"未承認"の案件を取得する
    $result = mysql_query("SELECT DISTINCT workdate.workid,workdate.workday,worklist.worktitle,worklist.content FROM workdate LEFT JOIN worklist ON workdate.workid=worklist.id WHERE (workerno IN (SELECT taker FROM caretakerlist WHERE giver='"
        . $_SESSION['userno'] . "') or workerno='" . $_SESSION['userno']
        . "') and worklist.groupno=$groupno and workdate.status='0' and workdate.workday > DATE_SUB(CURRENT_DATE(),interval 1 day) ORDER BY workdate.workday") or die ("Query error: " . mysql_error());
    while ($row = mysql_fetch_assoc($result)) {
        $records[] = $row;
    }

    mysql_close($con);

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <h2>未回答オファー一覧</h2>
        <p><?php echo $groupnamelist[$groupno]; ?>グループ</p>
        <p>※ 仕事をクリックして参加可否を回答してください</p>
        </br>
        <ul data-role="listview" data-inset="true">
            <?php
            if (count($records) == 0) {
                echo "<li>現在オファーはありません</li>";
            } else {
                $workday = "2000-01-01";
                foreach ($records as $eachwork) {
                    if ($eachwork['workday'] > $workday) {
                        // 日付で区切り線を入れる
                        echo "<li data-role=\"list-divider\">"
                            . $eachwork['workday'] . "</li>\n";
                        $workday = $eachwork['workday'];
                    }
                    echo "<li data-theme=\"c\"><a href=\"quotation.php?workid="
                        . $eachwork['workid'] . "&groupno=" . $groupno
                        . "\" rel=\"external\"><h2>" . $eachwork['worktitle']
                        . "</h2><p><strong>" . $groupnamelist[$groupno]
                        . "グループ</strong></p><p>" . $eachwork['content']
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
