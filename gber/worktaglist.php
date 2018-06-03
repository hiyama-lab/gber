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

    //****** IDを確認 ******//
    /*if($_SESSION['userno']!=1){
        echo "閲覧権限がありません";
        exit;
    }*/

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
            $result2
                = mysql_query("SELECT workid,userno FROM matchingparam_worktemp WHERE workid NOT IN (SELECT DISTINCT workid FROM matchingparam_work WHERE groupno='0') and groupno=0")
            or die ("Query error: " . mysql_error());
            $records2 = array();
            while ($row2 = mysql_fetch_assoc($result2)) {
                $records2[] = $row2;
            }
            $j = 0;
            foreach ($groupnamerecords[$i]['worklist'] as $eachrecord) {
                $groupnamerecords[$i]['worklist'][$j]['ok'] = true;
                $groupnamerecords[$i]['worklist'][$j]['userno'][0] = "";
                $groupnamerecords[$i]['worklist'][$j]['userno'][1] = "";
                $groupnamerecords[$i]['worklist'][$j]['userno'][2] = "";
                $k = 0;
                foreach ($records2 as $eachuser) {
                    if ($eachrecord['id'] == $eachuser['workid']) {
                        $groupnamerecords[$i]['worklist'][$j]['userno'][$k]
                            = $eachuser['userno'];
                        if ($eachuser['userno'] == $_SESSION['userno']) {
                            $groupnamerecords[$i]['worklist'][$j]['ok'] = false;
                        }
                        $k++;
                    }
                }
                $j++;
            }
        } else {
            $result = mysql_query("SELECT worktitle,id FROM worklist WHERE groupno = " . $eachgroup['groupno'] . " AND id NOT IN (SELECT DISTINCT workid FROM matchingparam_work WHERE groupno='"
                . $eachgroup['groupno'] . "') and status<5")
            or die ("Query error: " . mysql_error());
            $groupnamerecords[$i]['worklist'] = array();
            while ($row = mysql_fetch_assoc($result)) {
                $groupnamerecords[$i]['worklist'][] = $row;
            }
            $result2
                = mysql_query("SELECT workid,userno FROM matchingparam_worktemp WHERE groupno = '"
                . $eachgroup['groupno']
                . "' and workid NOT IN (SELECT DISTINCT workid FROM matchingparam_work WHERE groupno='"
                . $eachgroup['groupno'] . "')") or die ("Query error: "
                . mysql_error());
            $records2 = array();
            while ($row2 = mysql_fetch_assoc($result2)) {
                $records2[] = $row2;
            }
            $j = 0;
            foreach ($groupnamerecords[$i]['worklist'] as $eachrecord) {
                $groupnamerecords[$i]['worklist'][$j]['ok'] = true;
                $groupnamerecords[$i]['worklist'][$j]['userno'][0] = "";
                $groupnamerecords[$i]['worklist'][$j]['userno'][1] = "";
                $groupnamerecords[$i]['worklist'][$j]['userno'][2] = "";
                $k = 0;
                foreach ($records2 as $eachuser) {
                    if ($eachrecord['id'] == $eachuser['workid']) {
                        $groupnamerecords[$i]['worklist'][$j]['userno'][$k]
                            = $eachuser['userno'];
                        if ($eachuser['userno'] == $_SESSION['userno']) {
                            $groupnamerecords[$i]['worklist'][$j]['ok']
                                = false;
                        }
                        $k++;
                    }
                }
                $j++;
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

            echo "</br><h3>" . $eachgroup['groupname'] . "</h3>";
            echo "<table><tr><th>タイトル</th><th style=\"text-align:center; padding: 8px;\">1人目</th><th style=\"text-align:center; padding: 8px;\">2人目</th><th style=\"text-align:center; padding: 8px;\">3人目</th></tr>";
            foreach ($eachgroup['worklist'] as $eachwork) {
                echo "<tr><td style=\"padding: 8px;\">";
                if ($eachwork['ok']) {
                    echo "<a href=\"worktag.php?groupno=" . $eachgroup['groupno']
                        . "&workid=" . $eachwork['id'] . "\" rel=\"external\">"
                        . substr($eachwork['worktitle'], 0, 100) . "</a>";
                } else {
                    echo "【記入済】" . substr($eachwork['worktitle'], 0, 100);
                }
                echo "<br></td><td style=\"text-align:center; padding: 8px;\"><a href=\"mypage.php?userno="
                    . $eachwork['userno'][0] . "\" rel=\"external\">"
                    . $nicknamelist[$eachwork['userno'][0]]
                    . " </a></td><td style=\"text-align:center; padding: 8px;\"><a href=\"mypage.php?userno="
                    . $eachwork['userno'][1] . "\" rel=\"external\">"
                    . $nicknamelist[$eachwork['userno'][1]]
                    . "</a></td><td style=\"text-align:center; padding: 8px;\"><a href=\"mypage.php?userno="
                    . $eachwork['userno'][2] . "\" rel=\"external\">"
                    . $nicknamelist[$eachwork['userno'][2]] . "</a></td></tr>";
            }
            echo "</table>";


        }


        ?>


    </div>


</div><!-- end of content -->


<?php include("./common/commonFooter.php"); ?>
</div><!-- end of wrapper -->
</body>
</html>