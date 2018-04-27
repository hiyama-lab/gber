<?php
include __DIR__ . '/lib/sessioncheck.php';
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css"/>
    <script type="text/javascript" src="js/calendar/jquery-ui.min.js"></script>
    <script type="text/javascript"
            src="js/calendar/jquery-ui-i18n.min.js"></script>
    <!-- インライン編集用 -->
    <script type="text/javascript"
            src="js/editable/jquery.editable.min.js"></script>
    <!-- 地図スタイル -->
    <script src="js/googlemap_style.json"></script>
</head>
<body>
<div data-role="page" data-url="map-page">

    <!-- このファイルは全体募集のものにのみ使用する -->

    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    $workid = $_GET['workid'];
    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'jobdetail.php?workid=" . $workid . "', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    $today = date("Y-m-d");

    //募集情報の詳細
    $result = mysql_query("SELECT DISTINCT * FROM helplist WHERE id='" . $workid
        . "'") or die ("Query error: " . mysql_error());
    if (mysql_num_rows($result) == 0) {
        echo "無効な募集IDです";
        exit;
    }
    $record = mysql_fetch_assoc($result);

    $resultworkdate
        = mysql_query("SELECT helpdateid,workdate,comment FROM helpdate WHERE workid='"
        . $workid . "' ORDER BY workdate") or die ("Query error: " . mysql_error());
    $recordsworkdate = array();
    while ($rowworkdate = mysql_fetch_assoc($resultworkdate)) {
        $recordsworkdate[] = $rowworkdate;
    }

    //募集ステータス
    // 0: 募集中
    // 1: 締切済（興味有無ボタンは非表示）
    // 4: 非公開
    if ($record['status'] < 4) {

//閲覧者が興味有無を回答したか調査
        $result2
            = mysql_query("SELECT DISTINCT * FROM helpmatching WHERE workid='"
            . $workid . "' and applyuserno='" . $_SESSION['userno'] . "'")
        or die ("Query error: " . mysql_error());
        $evaluatedflag = 0;
        $interestedflag = false;
        if (mysql_num_rows($result2) != 0) {
            $evaluatedflag = 1;
            $records2 = mysql_fetch_assoc($result2);
            if ($records2['interest'] == 1) {
                $interestedflag = true;
            }
        }

//興味あり一覧を取得
        $result3
            = mysql_query("SELECT userno,nickname FROM db_user WHERE userno IN (SELECT applyuserno FROM helpmatching WHERE workid='"
            . $workid . "' and interest='1')") or die ("Query error: "
            . mysql_error());
        $records3 = array();
        while ($row3 = mysql_fetch_assoc($result3)) {
            $records3[] = $row3;
        }

//メッセージIDを取得
        $messageid = 0;
        $result4 = mysql_query("SELECT messageid FROM message WHERE workid='"
            . $workid . "'") or die ("Query error: " . mysql_error());
        if (mysql_num_rows($result4) > 0) {
            $messageid = mysql_fetch_assoc($result4)['messageid'];
        }

//過去案件かどうかに関係なく、興味ありなしボタンは残す
        $pastflag = false;
        $nointerestflag = false;
        if ($record['status'] == 1) {
            $status = "締切済";
            $pastflag = true;
        } else {
            if (count($recordsworkdate) == 0) {
                $status = "日程なし";
                $pastflag = true;
            } else {
                if (strtotime($recordsworkdate[count($recordsworkdate)
                    - 1]['workdate']) < strtotime($today)
                ) {
                    $status = "開催済";
                    $pastflag = true;
                } else {
                    $status = "<font color=\"red\">募集中</font>";
                }
            }
        }
        $status .= "・";
        if ($evaluatedflag) {
            $interestarray = ["興味なし", "興味あり"];
            $status .= $interestarray[$records2['interest']];
            if ($records2['interest'] == 0) {
                $nointerestflag = true;
            }
        } else {
            $status .= "<font color=\"red\">未評価</font>";
        }

    } else {
        $status = "【非公開】";
    }

    mysql_close($con);

    ?>

    <script type="text/javascript">
        var work_detail = <?php echo json_encode($record); ?>;
    </script>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <h2 id="worktitle"><?php if ($record['worktitle'] == "") {
                echo "未入力";
            } else {
                echo $record['worktitle'];
            } ?></h2>
        <p><?php echo $status; ?></p>
        <?php
        if ($nointerestflag) {
            echo "<p>【参加希望なし】→　<input type=\"button\" data-role=\"none\" data-inline=\"true\" value=\"希望ありに変更\" onClick=\"changeippaninterest("
                . $workid . "," . $_SESSION['userno'] . ",1);\" /></p>";
        }

        if ($record['status'] == 4) {
            echo "この募集は非表示になりました。";
            if ($_SESSION['userno'] == $record['userno']) {
                echo "<input type=\"button\" onclick=\"showJobDetail()\" value=\"再公開する\" />";
            }
            echo "</div>";
            include("./common/commonFooter.php");
            echo "<script type=\"text/javascript\" src=\"js/jobDetail.js\"></script>";
            echo "</div></body></html>";
            exit;
        }
        ?>
        <p id="content"><?php if ($record['content'] == "") {
                echo "未入力";
            } else {
                echo nl2br($record['content']);
            } ?></p>
        <p>開催場所: <span id="address"><?php if ($record['address'] != "") {
                    echo $record['address'];
                } else {
                    echo "未入力";
                } ?></span></p>
        </br>
        <h3>募集詳細</h3>
        <p>【募集日付】</br><span id="workdate"><?php
                if (count($recordsworkdate) == 0) {
                    echo "日程なし";
                } else {
                    foreach ($recordsworkdate as $eachworkdate) {
                        if ($_SESSION['userno'] == $record['userno']
                            || $_SESSION['userno'] == 1
                        ) {
                            echo "<span id=\"eachworkdate_"
                                . $eachworkdate['workdate'] . "\">"
                                . $eachworkdate['workdate'] . "　"
                                . $eachworkdate['comment']
                                . "</span>　<a onclick=\"deleteeachdate('"
                                . $eachworkdate['helpdateid'] . "')\">削除</a></br>";
                        } else {
                            echo "<span>" . $eachworkdate['workdate'] . "　"
                                . $eachworkdate['comment'] . "</span></br>";
                        }
                    }
                }
                ?></span></p>
        <?php
        if ($_SESSION['userno'] == $record['userno']
            || $_SESSION['userno'] == 1
        ) {
            echo "<button data-role=\"none\" id=\"addworkdatebutton\" onclick=\"$('#visibleaddworkdate').show();$('#addworkdatebutton').hide();\">日程を追加</button></br>";
        }
        ?>
        <div id="visibleaddworkdate" style="display:none;">
            <input data-role="date" type="text" name="addworkdate"
                   id="addworkdate" placeholder="追加日程" required/>
            <input type="button" data-theme="c" value="日程を追加する"
                   onClick="addworkdate(0);"/>
            </br>
        </div>
        <p>【対象、人数】<span id="workernum"><?php if ($record['workernum'] == "") {
                    echo "未入力";
                } else {
                    echo $record['workernum'];
                } ?></span></p>
        <p>【費用/日給】<span id="price"><?php if ($record['price'] == "") {
                    echo "未入力";
                } else {
                    echo $record['price'];
                } ?></span></p>
        <p>【募集種類】<span id="workgenre"><?php if ($record['workgenre'] == "") {
                    echo "未入力";
                } else {
                    echo $record['workgenre'];
                } ?></span></p>
        <p>【募集団体】<span id="groupgenre"><?php if ($record['groupgenre'] == "") {
                    echo "未入力";
                } else {
                    echo $record['groupgenre'];
                } ?></span></p>
        <p>【掲示責任者】<span id="nickname"><?php if ($record['userno'] == "") {
                    echo "未入力";
                } else {
                    echo "<a href=\"mypage.php?userno=" . $record['userno']
                        . "\" rel=\"external\">ID: " . $record['userno'] . "</a>";
                } ?></span></p>
        <p>【申込方法、連絡先】<span id="contact"><?php if ($record['contact'] == "") {
                    echo "未入力";
                } else {
                    echo $record['contact'];
                } ?></span></p>
        </br>

        <?php

        // GBER上での応募機能はOFFにする。
        // GBERは案件を表示するだけの掲示板のような役割にする。
        // ただし、興味の有無を調査し、そのログから、個人の興味対象の推定を行う。

        // 興味有無が未回答なら、そのボタンを表示。ただし締め切り時は非表示。
        if (!$evaluatedflag && $record['status'] == 0) {
            echo "<div class=\"ui-grid-a\">";
            echo "<div class=\"ui-block-a\"><input type=\"button\" onclick=\"answerinterest("
                . $_SESSION['userno'] . ", 1)\" value=\"興味あり\"></div>";
            echo "<div class=\"ui-block-b\"><input type=\"button\" data-theme=\"c\" onclick=\"answerinterest("
                . $_SESSION['userno'] . ", 0)\" value=\"興味なし\"></div>";
            echo "</div></br>";
        }


        ?>

        <h3>興味あり</h3>
        <?php
        if (count($records3) == 0) {
            echo "<p>まだ興味ありと回答した人はいません</p></br>";
        } else {
            if ($messageid != 0 && $interestedflag) {
                echo "<p><a href=\"messageboard.php?messageid=" . $messageid
                    . "\" rel=\"external\">メッセージボード</a></p>";
                echo "<p><a href=\"chouseikun.php?workid=" . $workid
                    . "\" rel=\"external\">日程調整</a></p>";
            }
            echo "<table style=\"text-align: center;\"><tr>";
            foreach ($records3 as $eachmember) {
                echo "<td><a href=\"mypage.php?userno=" . $eachmember['userno']
                    . "\" rel=\"external\"><img src=\"./model/showuserimage.php?userno="
                    . $eachmember['userno']
                    . "\" onerror=\"this.src='img/noimage.svg';\" class=\"bbsphoto\" width=\"50px\"></a></td>";
            }
            echo "</tr><tr>";
            foreach ($records3 as $eachmember) {
                echo "<td><a href=\"mypage.php?userno=" . $eachmember['userno']
                    . "\" rel=\"external\">" . $eachmember['nickname'] . "</a></td>";
            }
            echo "</tr></table>";
        }
        ?>


        <?php

        // 閲覧者が募集者、"もしくはマスターアカウントのとき"、編集スクリプトと削除ボタンを表示
        if ($_SESSION['userno'] == $record['userno']
            || $_SESSION['userno'] == 1
        ) {
            echo "<script>";
            echo "$(\"h2#worktitle\").editable({action:\"dblclick\"}, function(e){editcontent("
                . $workid . ",e.value,'worktitle');});";
            echo "$(\"p#content\").editable({type:\"textarea\", action:\"dblclick\"}, function(e){editcontent("
                . $workid . ",e.value,'content');});";
            echo "$(\"span#address\").editable({action:\"dblclick\"}, function(e){editcontent("
                . $workid . ",e.value,'address');});";
            echo "$(\"span#workernum\").editable({action:\"dblclick\"}, function(e){editcontent("
                . $workid . ",e.value,'workernum');});";
            echo "$(\"span#price\").editable({action:\"dblclick\"}, function(e){editcontent("
                . $workid . ",e.value,'price');});";
            echo "$(\"span#deadline\").editable({action:\"dblclick\"}, function(e){editcontent("
                . $workid . ",e.value,'deadline');});";
            echo "$(\"span#contact\").editable({action:\"dblclick\"}, function(e){editcontent("
                . $workid . ",e.value,'contact');});";
            echo "</script>";
            if ($record['status'] != 1) {
                echo "<input type=\"button\" onclick=\"shutJobDetail()\" value=\"締め切る\" />";
            }
        }
        ?>

        </br>
        <div id="detail-map"></div><!-- jsでappend -->
        <p id="place">住所(自動生成) </p><!-- jsでappend -->
        <?php
        if ($_SESSION['userno'] == $record['userno']
            || $_SESSION['userno'] == 1
        ) {
            echo "<button data-role=\"none\" id=\"changeaddressbutton\" onclick=\"editPin();\">住所変更</button></br>";
        }
        ?>
        <div id="visiblelatlng" style="display:none;">
            <form id="changelatlng">
                <p><font color="red">住所変更中</font></p>
                <div style="display: none;">
                    <input type="number" name="workid" readonly="readonly"
                           value="<?php echo $record['id']; ?>" required/></br>
                    <input type="text" id="changelat" size="20" name="changelat"
                           readonly="readonly"
                           value="<?php echo $record['lat']; ?>" required/></br>
                    <input type="text" id="changelng" size="20" name="changelng"
                           readonly="readonly"
                           value="<?php echo $record['lng']; ?>" required/></br>
                </div>
                <input type="text" name="changeaddress" id="mapsearch"
                       placeholder="住所入力欄">
                <p>緯度：<span id="visiblelat"><?php echo $record['lat']; ?></span>　経度：<span
                            id="visiblelng"><?php echo $record['lng']; ?></span>
                </p>
                <input type="button" data-theme="c" value="住所変更を確定する"
                       onClick="confirmAddressChange();"/>
            </form>
        </div>
        </br>
        <?php
        if ($_SESSION['userno'] == $record['userno']
            || $_SESSION['userno'] == 1
        ) {
            echo "</br></br><input type=\"button\" onclick=\"hideJobDetail("
                . $_SESSION['userno'] . ")\" value=\"非公開にする\" />";
        }
        ?>
    </div>
    <?php include("./common/commonFooter.php"); ?>
    <script>$(function () {
            $.datepicker.setDefaults($.datepicker.regional['ja']);
            $('#addworkdate').datepicker({dateFormat: 'yy-mm-dd'});
        });</script>
    <script>var isDemo= <?php echo $_ENV["IS_DEMO"] ?>;</script>
    <script type="text/javascript" src="js/jobDetail.js"></script>
    <?php $googleMap_callback = "initMap";
    include("./common/googleMapApi.php"); ?>
</div>
</body>
</html>
