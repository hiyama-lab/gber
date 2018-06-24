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

    if(!authorize($_SESSION['userno'], ROLE['MASTER_OR_SOMEADMIN'], ['isapi' => false])){
        echo "閲覧権限がありません";
        exit;
    }

    //****** タグ付けされていない仕事一覧 ******//
    $i = 0;

    foreach ($groupnamerecords as $eachgroup) {
        if ($eachgroup['groupno'] == 0) {
            $result
                = mysql_query("SELECT worktitle,id FROM helplist WHERE id NOT IN (SELECT DISTINCT workid FROM matchingparam_work WHERE groupno='0')")
            or die ("Query error: " . mysql_error());
            $groupnamerecords[$i]['worklist'] = array();
            while ($row = mysql_fetch_assoc($result)) {
                $groupnamerecords[$i]['worklist'][] = $row;
            }
        } else {
            $result = mysql_query("SELECT worktitle,id FROM worklist WHERE groupno = " . $eachgroup['groupno'] . " AND id NOT IN (SELECT DISTINCT workid FROM matchingparam_work WHERE groupno='"
                . $eachgroup['groupno'] . "') and status<5")
            or die ("Query error: " . mysql_error());
            $groupnamerecords[$i]['worklist'] = array();
            while ($row = mysql_fetch_assoc($result)) {
                $groupnamerecords[$i]['worklist'][] = $row;
            }
        }
        $i++;
    }

    //****** ニックネームリスト ******//
    $result
        = mysql_query("SELECT userno,nickname FROM db_user WHERE mail<>\"\"")
    or die ("Query error: " . mysql_error());
    $records = array();
    while ($row = mysql_fetch_assoc($result)) {
        $records[] = $row;
    }
    $nicknamelist = array();
    foreach ($records as $eachrecord) {
        $nicknamelist[$eachrecord['userno']] = $eachrecord['nickname'];
    }

    mysql_close($con);

    ?>


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content"><!-- START OF CONTENT -->


        <h2>未タグ付け仕事一覧</h2>

        <?php

        foreach ($groupnamerecords as $eachgroup) {
            $groupno = $eachgroup['groupno'];
            if($groupno == 0 || authorize($_SESSION['userno'], ROLE['MASTER_OR_ADMIN'], ['groupno' => $groupno, 'isapi' => false])){
                echo "</br><h3>" . h($eachgroup['groupname']) . "</h3>";
                if(count($eachgroup['worklist'])){
                    echo "<table><tr><th>タイトル</th></tr>";
                    foreach ($eachgroup['worklist'] as $eachwork) {
                        echo "<tr><td style=\"padding: 8px;\">";
                        echo "<a href=\"worktag.php?groupno=" . $groupno
                            . "&workid=" . $eachwork['id'] . "\" rel=\"external\">"
                            . substr(h($eachwork['worktitle']), 0, 100) . "</a>";
                        echo "<br></td></tr>";
                    }
                    echo "</table>";
                }else{
                    echo "タグ付けされていない仕事はありません";
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