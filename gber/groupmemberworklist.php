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
    include __DIR__ . '/model/calcMatch.php';

    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'joboffer.php', '" . date('Y-m-d G:i:s') . "')",
        $con) or die('Error: ' . mysql_error());

    $userno = $_SESSION['userno'];
    $groupno = $_GET['groupno'];

    // 本当に所属しているのかは調べないことにする

    // 自分にオファーの来ている仕事をグループごとに取得し，その仕事詳細データをさらに統合する．最後に，グループを混合して日付順で並び替え
    $records = array();
    //職能集団の依頼リストからstatus=2の案件を取得する
    $result = mysql_query("SELECT worklist.worktitle,worklist.content,worklist.id,workinterest.interest FROM worklist LEFT OUTER JOIN workinterest ON worklist.id = workinterest.workid AND workinterest.userno = $userno WHERE worklist.groupno=$groupno AND worklist.status='2' ORDER BY workinterest.interest LIMIT 100") or die ("Query error: " . mysql_error());
    while ($row = mysql_fetch_assoc($result)) {
        $records[] = $row;
    }

    mysql_close($con);

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <h2>仕事一覧</h2>
        <p><?php echo $groupnamelist[$groupno]; ?>グループ</p>
        <p>※ 現在グループで進行中の仕事一覧です</p>
        </br>
        <ul data-role="listview" data-inset="true">
            <?php
            if (count($records) == 0) {
                echo "<li>現在仕事はありません</li>";
            } else {
                // それぞれの仕事に対してマッチング係数Mを計算
                foreach($records as &$work){
                    $match = calcMatch($userno, $work['id']);
                    $work["match"] = $match;
                }
                unset($work);

                $undefined = array_filter($records, function($v, $k) {
                    return is_null($v['interest']);
                }, ARRAY_FILTER_USE_BOTH);
                $negative = array_filter($records, function($v, $k) {
                    return !is_null($v['interest']) && $v['interest'] == 0;
                }, ARRAY_FILTER_USE_BOTH);
                $positive = array_filter($records, function($v, $k) {
                    return $v['interest'] == 1;
                }, ARRAY_FILTER_USE_BOTH);

                // TODO それぞれの配列をマッチング係数のの降順に並べ替える

                // 参加希望ごとにマッチング係数の高いものから順に表示
                if(count($undefined) > 0){
                    echo "<li data-role=\"list-divider\">参加希望未回答</li>\n";
                    foreach($undefined as $eachwork){
                        echo "<li data-theme=\"c\"><a href=\"quotation.php?workid={$eachwork['id']}&groupno=$groupno\" rel=\"external\">
                                <h2>" . h($eachwork['worktitle']) . " (M={$eachwork['match']})</h2><p>" . h($eachwork['content']) . "</p></a></li>\n";
                    }
                }

                if(count($negative) > 0){
                    echo "<li data-role=\"list-divider\">参加希望なし</li>\n";
                    foreach($negative as $eachwork){
                        echo "<li data-theme=\"c\"><a href=\"quotation.php?workid={$eachwork['id']}&groupno=$groupno\" rel=\"external\">
                                <h2>" . h($eachwork['worktitle']) . " (M={$eachwork['match']})</h2><p>" . h($eachwork['content']) . "</p></a></li>\n";
                    }
                }

                if(count($positive) > 0){
                    echo "<li data-role=\"list-divider\">参加希望あり</li>\n";
                    foreach($positive as $eachwork){
                        echo "<li data-theme=\"c\"><a href=\"quotation.php?workid={$eachwork['id']}&groupno=$groupno\" rel=\"external\">
                                <h2>" . h($eachwork['worktitle']) . " (M={$eachwork['match']})</h2><p>" . h($eachwork['content']) . "</p></a></li>\n";
                    }
                }
            }
            ?>
        </ul>
    </div>


    <?php include("./common/commonFooter.php"); ?>
</div>
</body>
</html>
