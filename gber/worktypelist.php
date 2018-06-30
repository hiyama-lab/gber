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
    require_once __DIR__ . '/lib/db.php';

    $groupno = $_GET['groupno'];

    if($groupno == 0 && !authorize($_SESSION['userno'], ROLE['GLOBAL_MASTER'], ['isapi' => false])){
        echo "閲覧権限がありません";
        exit;
    }
    if($groupno != 0 && !authorize($_SESSION['userno'], ROLE['GROUP_ADMIN'], ['groupno' => $groupno, 'isapi' => false])){
        echo "閲覧権限がありません";
        exit;
    }

    // 仕事タイプ一覧
    $db = DB::getInstance();
    $worktypes = $db->getAllWorktypes($groupno);

    ?>


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content"><!-- START OF CONTENT -->


        <h2>仕事タイプ一覧</h2>
        <h3><?php echo h($groupnamerecords[$groupno]['groupname']);?>グループ</h3>

        <?php
        echo "<table>";
        foreach($worktypes as $worktype){
            echo "<tr>";
            echo "<td style=\"padding: 8px;\"><a href=\"worktype.php?groupno=$groupno&worktypeid={$worktype['id']}\" rel=\"external\">" . h($worktype['name']) . "</a></td>";
            echo "<td style=\"text-align: center; \"><input type=\"button\" class=\"delete-worktype\" value=\"削除\" onClick=\"deleteWorktype({$worktype['id']},$groupno);\"/></td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>
        <form>
            <input type="button" value="仕事タイプ新規追加" onClick="location.href='worktype.php?groupid=<?php echo $groupno;?>'"/>
        </form>

    </div>
    <script>
        $(function () {
            $("div:has('> .delete-worktype')").css({padding: ".1em .5em", margin: ".2em .3em"});
        });
    </script>


</div><!-- end of content -->


<?php include("./common/commonFooter.php"); ?>
<script type="text/javascript" src="js/questionnaire.js"></script>
</div><!-- end of wrapper -->
</body>
</html>