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
    require_once __DIR__ . '/lib/db.php';

    if(!authorize($_SESSION['userno'], ROLE['MASTER_OR_SOMEADMIN'], ['isapi' => false])){
        echo "閲覧権限がありません";
        exit;
    }

    //****** タグ付けされていない仕事一覧 ******//
    $i = 0;

    $db = DB::getInstance();

    foreach ($groupnamerecords as $eachgroup) {
        if ($eachgroup['groupno'] == 0) {
            $groupnamerecords[$i]['untaggedwork'] = $db->getUntaggedWorksAll();
            $groupnamerecords[$i]['taggedwork'] = $db->getTaggedWorksAll();
        } else {
            $groupnamerecords[$i]['untaggedwork'] = $db->getUntaggedWorksGroup($eachgroup['groupno']);
            $groupnamerecords[$i]['taggedwork'] = $db->getTaggedWorksGroup($eachgroup['groupno']);
        }
        $i++;
    }
    ?>


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content"><!-- START OF CONTENT -->


        <h2>タグ付け対象仕事一覧</h2>

        <?php

        foreach ($groupnamerecords as $eachgroup) {
            $groupno = $eachgroup['groupno'];
            if($groupno == 0 || authorize($_SESSION['userno'], ROLE['MASTER_OR_ADMIN'], ['groupno' => $groupno, 'isapi' => false])){
                echo "</br><h3>" . h($eachgroup['groupname']) . "</h3>";
                if(count($eachgroup['untaggedwork']) || count($eachgroup['taggedwork'])){
                    echo "<table><tr><th>タグ付け</th><th>タイトル</th></tr>";
                    foreach ($eachgroup['untaggedwork'] as $eachwork) {
                        echo "<tr>";
                        echo "<td style=\"text-align: center\">未記入</td>";
                        echo "<td style=\"padding: 8px;\"><a href=\"worktag.php?groupno=$groupno&workid={$eachwork['id']}\" rel=\"external\">" . substr(h($eachwork['worktitle']), 0, 100) . "</a></td>";
                        echo "</tr>";
                    }
                    foreach ($eachgroup['taggedwork'] as $eachwork) {
                        echo "<tr>";
                        echo "<td style=\"text-align: center\">記入済</td>";
                        echo "<td style=\"padding: 8px;\"><a href=\"worktag.php?groupno=$groupno&workid={$eachwork['id']}\" rel=\"external\">" . substr(h($eachwork['worktitle']), 0, 100) . "</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }else{
                    echo "仕事はありません";
                }
            }
        }


        ?>


    </div>


</div><!-- end of content -->


<?php include("./common/commonFooter.php"); ?>
</div><!-- end of wrapper -->
</body>
</html>