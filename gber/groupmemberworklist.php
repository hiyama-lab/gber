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
    require_once __DIR__ . '/lib/mysql_credentials.php';
    require_once __DIR__ . '/lib/db.php';
    require_once __DIR__ . '/model/calcMatch.php';
    require_once __DIR__ . '/common/workCell.php';

    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'joboffer.php', '" . date('Y-m-d G:i:s') . "')",
        $con) or die('Error: ' . mysql_error());

    $userno = $_SESSION['userno'];
    $groupno = $_GET['groupno'];

    $db = DB::getInstance();
    // 自分にオファーの来ている仕事をグループごとに取得し，その仕事詳細データをさらに統合する．最後に，グループを混合して日付順で並び替え
    // 職能集団の依頼リストからstatus=2の案件を取得する
    $records = $db->getOngoingWork($groupno, $userno);
    $userp = $db->getMatchingParamByUserno($userno);

    // 興味ベクトルの大きさが0のユーザはマッチングしない
    $matching_enabled = calcSize($userp) ? true : false;
    if($matching_enabled){
        // それぞれの仕事に対してマッチング係数Mを計算
        foreach($records as &$work){
            $workp = $db->getMatchingParamByWorkid($work['id'], $groupno);
            $match = calcMatch($userp, $workp);
            $work["match"] = $match;
        }
        unset($work);
    }

    $undefined = array_filter($records, function($v) {
        return is_null($v['interest']);
    }, ARRAY_FILTER_USE_BOTH);
    $negative = array_filter($records, function($v) {
        return !is_null($v['interest']) && $v['interest'] == 0;
    }, ARRAY_FILTER_USE_BOTH);
    $positive = array_filter($records, function($v) {
        return $v['interest'] == 1;
    }, ARRAY_FILTER_USE_BOTH);

    // それぞれの配列をマッチング係数の降順に並べ替える
    if($matching_enabled){
        function cmp($a, $b){
            if ($a['match'] == $b['match']){
                return 0;
            }
            return ($a['match'] > $b['match']) ? -1 : 1;
        }
        uasort($undefined, 'cmp');
        uasort($negative, 'cmp');
        uasort($positive, 'cmp');
    }
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
                // 参加希望ごとにマッチング係数の高いものから順に表示
                if(count($undefined) > 0){
                    echo "<li data-role=\"list-divider\">参加希望未回答</li>\n";
                    foreach($undefined as $eachwork){
                        $score = $matching_enabled ? $eachwork['match'] : UNDEFINED_SCORE;
                        echoWorkCell($eachwork['id'], $groupno, $eachwork['worktitle'], $eachwork['workdatetime'], $eachwork['content'], $eachwork['match']);
                    }
                }

                if(count($negative) > 0){
                    echo "<li data-role=\"list-divider\">参加したくない</li>\n";
                    foreach($negative as $eachwork){
                        $score = $matching_enabled ? $eachwork['match'] : UNDEFINED_SCORE;
                        echoWorkCell($eachwork['id'], $groupno, $eachwork['worktitle'], $eachwork['workdatetime'], $eachwork['content'], $eachwork['match']);
                    }
                }

                if(count($positive) > 0){
                    echo "<li data-role=\"list-divider\">参加したい</li>\n";
                    foreach($positive as $eachwork){
                        $score = $matching_enabled ? $eachwork['match'] : UNDEFINED_SCORE;
                        echoWorkCell($eachwork['id'], $groupno, $eachwork['worktitle'], $eachwork['workdatetime'], $eachwork['content'], $eachwork['match']);
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
