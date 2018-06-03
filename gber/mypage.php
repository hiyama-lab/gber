<?php
include __DIR__ . '/lib/sessionUtil.php';
require_logined_session();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <link rel="stylesheet" type="text/css" href="js/rateit/rateit.css"/>
    <script type="text/javascript"
            src="js/rateit/jquery.rateit.min.js"></script>
    <script type="text/javascript" src="js/logout.js"></script>
</head>
<body>
<div data-role="page" id="mypage">

    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    $userno = $_GET['userno'];
    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'mypage.php?userno=" . $userno . "', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    // ユーザのプロフィール
    $sql = "SELECT * FROM db_user WHERE userno='" . $userno . "'";
    $result = mysql_query($sql) or die ("Query error: " . mysql_error());
    if (mysql_num_rows($result) == 0) {
        echo "ユーザIDが存在しません";
        exit;
    }
    $records = array();
    while ($row = mysql_fetch_assoc($result)) {
        $records[] = $row;
    }
    $records[0]['intro'] = nl2br($records[0]['intro']);

    // 参加しているグループのリスト
    $sql2 = "SELECT * FROM grouplist WHERE userno='" . $userno . "'";
    $result2 = mysql_query($sql2) or die ("Query error: " . mysql_error());
    $records2 = array();
    while ($row2 = mysql_fetch_assoc($result2)) {
        $records2[] = $row2;
    }

    // ユーザを世話する人を取得
    $result4 = mysql_query("SELECT giver FROM caretakerlist WHERE taker='"
        . $userno . "'") or die ("Query error: " . mysql_error());
    $caretakerflag = false;
    while ($row4 = mysql_fetch_assoc($result4)) {
        if ($row4['giver'] == $_SESSION['userno']) {
            $caretakerflag = true;
        }
    }

    // ユーザが世話する人を取得。自分が閲覧している時のみ表示。
    $amcaretaker = false;
    if ($_SESSION['userno'] == $userno) {
        $result5
            = mysql_query("SELECT nickname, userno, mail, phone, address_string FROM db_user WHERE userno IN (SELECT taker FROM caretakerlist WHERE giver='"
            . $userno . "')") or die ("Query error: " . mysql_error());
        $caretakerlist = array();
        if (mysql_num_rows($result5) > 0) {
            $amcaretaker = true;
            while ($row5 = mysql_fetch_assoc($result5)) {
                $caretakerlist[] = $row5;
            }
        }
    }

    // 閲覧者が所属グループの管理者かどうか判定
    $groupmemberflag = false;
    foreach ($records2 as $eachgroup) {
        if ($eachgroup['groupno'] > 0) {
            $result6
                = mysql_query("SELECT userno FROM grouplist WHERE groupno = '"
                . $eachgroup['groupno'] . "' and admin='1'")
            or die ("Query error: " . mysql_error());
            while ($row6 = mysql_fetch_assoc($result6)) {
                if ($row6['userno'] == $_SESSION['userno']) {
                    $groupmemberflag = true;
                }
            }
        }
    }

    if ($_SESSION['userno'] == $userno) {
        $answerdemographic = false;
        $demographic
            = mysql_query("SELECT * FROM questionnaire_demographic WHERE userno = '"
            . $userno . "'") or die ("Query error: " . mysql_error());
        if (mysql_num_rows($demographic) > 0) {
            $answerdemographic = true;
            $demographicresult = mysql_fetch_assoc($demographic);
        }
        $answerworkstyle = false;
        $workstyle
            = mysql_query("SELECT * FROM questionnaire_workstyle WHERE userno = '"
            . $userno . "'") or die ("Query error: " . mysql_error());
        if (mysql_num_rows($workstyle) > 0) {
            $answerworkstyle = true;
            $workstyleresult = mysql_fetch_assoc($workstyle);
        }
        /*
        $answersocialactivity = false;
        $socialactivity = mysql_query("SELECT * FROM questionnaire_socialactivity WHERE userno = '".$userno."'") or die ("Query error: " . mysql_error());
        if(mysql_num_rows($socialactivity)>0){
            $answersocialactivity = true;
            $socialactivityresult = mysql_fetch_assoc($socialactivity);
        }
        */
    }

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>
    <!-- CONTENT -->
    <div data-role="content">

        <div data-role="tabs" id="tabs"><!-- START OF TABS -->


            <div id="profile-container">
                <div>
                    <img src="./model/showuserimage.php?userno=<?php echo $userno; ?>"
                         onerror="this.src='img/noimage.svg';"/></div>
                <h2>
                    <?php
                    echo $records[0]['nickname'];
                    if ($records[0]['certification'] == 1) {
                        echo " <img src=\"img/verified.png\" />";
                    }
                    ?>
                </h2>
                <?php
                if ($userno != $_SESSION['userno']) {
                    echo "<button data-role=\"none\" onclick=\"createnewmessage("
                        . $_SESSION['userno'] . "," . $userno
                        . ")\">新規メッセージボードを作成する</button>";
                }
                ?>
            </div>


            <?php
            if ($userno == $_SESSION['userno']) {
                echo "<div data-role=\"navbar\" id=\"navbar\"><ul><li><a href=\"#profiletab\" data-ajax=\"false\">プロフィール</a></li><li><a href=\"#workstab\" data-ajax=\"false\">スキル</a></li></ul></div>";
            }
            ?>


            <div id="profiletab">
                <h3>プロフィール<?php if ($userno == $_SESSION['userno']
                        || $caretakerflag
                    ) {
                        echo "　<a href=\"editprofile.php?userno=" . $userno
                            . "\" rel=\"external\" data-role=\"none\">編集</a>";
                    } ?></h3>
                <?php
                if ($records[0]['birthyear'] == 0) {
                    echo "<p>プロフィールが未設定です</p>";
                } else {
                    if ($records[0]['certification'] == 1) {
                        echo "<p>本人認証済</p>";
                    }
                    echo "<p>【生年】" . $records[0]['birthyear'] . "年</p>\n";
                    echo "<p>【性別】" . $records[0]['gender'] . "</p>\n";
                    echo "<p>【紹介文】</br>" . $records[0]['intro'] . "</p>\n";
                }
                ?>
                <?php
                if ($userno == $_SESSION['userno'] || $groupmemberflag
                    || $caretakerflag
                ) { //自分もしくはグループ管理者なら表示
                    echo "</br><h3>連絡先";
                    if ($userno == $_SESSION['userno'] || $caretakerflag) {
                        echo "　<a href=\"editprofile.php?userno=" . $userno
                            . "\" rel=\"external\" data-role=\"none\">編集</a>";
                    }
                    echo "</h3>";
                    echo "<p>【メール】" . $records[0]['mail'] . "</p>\n";
                    echo "<p>【電話】" . $records[0]['phone'] . "</p>\n";
                    echo "<p>【住所】" . $records[0]['address_string'] . "</p>\n";
                }

                //代理人であれば表示
                if ($amcaretaker) {
                    echo "</br><h3>代理人一覧</h3>";
                    echo "<table id=\"caretaker\">";
                    echo "<tr>";
                    echo "<th class=\"memberprof\"></th>";
                    echo "<th class=\"memberprof\">ID</th>";
                    echo "<th class=\"memberprof\">ニックネーム</th>";
                    echo "<th class=\"memberprof\">メールアドレス</th>";
                    echo "<th class=\"memberprof\">電話番号</th>";
                    echo "<th class=\"memberprof\">住所</th>";
                    echo "</tr>";
                    foreach ($caretakerlist as $eachmember) {
                        $str = "<tr>";
                        $str .= "<td><a href=\"mypage.php?userno="
                            . $eachmember['userno']
                            . "\" rel=\"external\"><img src=\"./model/showuserimage.php?userno="
                            . $eachmember['userno']
                            . "\" onerror=\"this.src='img/noimage.svg';\" width=\"50px\" /></a></td>";
                        $str .= "<td class=\"memberprof\">"
                            . $eachmember['userno'] . "</td>";
                        $str .= "<td class=\"memberprof\">"
                            . $eachmember['nickname'] . "</td>";
                        $str .= "<td class=\"memberprof\">" . $eachmember['mail']
                            . "</td>";
                        $str .= "<td class=\"memberprof\">" . $eachmember['phone']
                            . "</td>";
                        $str .= "<td class=\"memberprof\">"
                            . $eachmember['address_string'] . "</td>";
                        $str .= "</tr>\n";
                        echo $str;
                    }
                    echo "</table>";
                }

                if ($userno == $_SESSION['userno']) {
                    $token = h(generate_token());
                    echo "</br><input type=\"button\" value=\"ログアウト\" onClick=\"logout('$token');\" />";
                }

                ?>
            </div><!-- end of profiletab -->


            <?php
            if ($userno == $_SESSION['userno']) {
                echo "<div id=\"workstab\">";
                echo "<h3>スキル</h3>";
                echo "<p>※ここで入手した情報は、マッチング研究のためにのみ使用します。</br>※他のユーザに公開されることはありません。</br>※研究の成果報告の際は、個人が特定されない形に匿名化して発表を行います。</p></br>";

                echo "<p><a href=\"skillprofile.php?userno=" . $userno
                    . "\" rel=\"external\">自分の回答状況を確認</a></p><br>";

                if (!$answerdemographic) {
                    echo "<p><a href=\"questionnaire_demographic.php?userno="
                        . $userno
                        . "\" rel=\"external\">質問① 基本情報</a></br>職歴、資格、生活状況について</p></br>";
                } else {
                    echo "<p><u><b>質問① 基本情報(回答済)</b></u></br>職歴、資格、生活状況について</br>再編集は<a href=\"questionnaire_demographic.php?userno="
                        . $userno
                        . "\" rel=\"external\">こちら</a></br>※再編集すると以前の回答は上書きされます</p></br>";
                    //echo "<p><u><b>質問① 基本情報(回答済)</b></u></p>";
                }
                if (!$answerworkstyle) {
                    echo "<p><a href=\"questionnaire_workstyle.php?userno="
                        . $userno
                        . "\" rel=\"external\">質問② 就労希望形態</a></br>希望就労頻度、場所、種類、目的について</p></br>";
                } else {
                    echo "<p><u><b>質問② 就労希望形態(回答済)</b></u></br>希望就労頻度、場所、種類、目的について</br>再編集は<a href=\"questionnaire_workstyle.php?userno="
                        . $userno
                        . "\" rel=\"external\">こちら</a></br>※再編集すると以前の回答は上書きされます</p></br>";
                    //echo "<p><u><b>質問② 就労希望形態(回答済)</b></u></p>";
                }
                /*
                        if(!$answersocialactivity){
                            echo "<p><a href=\"questionnaire_socialactivity.php?userno=".$userno."\" rel=\"external\">質問③ 趣味、社会参加状況調査</a></br>趣味、ボランティア活動、コミュニティ活動について</p></br>";
                        } else {
                            echo "<p><u><b>質問③ 趣味、社会参加状況調査(回答済)</b></u></br>希望就労頻度、場所、種類、目的について</br>再編集は<a href=\"questionnaire_socialactivity.php?userno=".$userno."\" rel=\"external\">こちら</a></br>※再編集すると以前の回答は上書きされます</p></br>";
                            //echo "<p><u><b>質問③ 趣味、社会参加状況調査(回答済)</b></u></p>";
                        }
                    echo "</div>";
                */
            }
            ?>


        </div>
        </br><!--end of tabs -->
    </div>

    <?php include("./common/commonFooter.php"); ?>
    <script>
        function createnewmessage(persona, personb) {
            var JSONdata = {
                persona: persona,
                personb: personb
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(JSONdata),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/createNewMessageBoard2.php',
                timeout: 10000,
                success: function (data) {
                    swal({
                            title: "成功",
                            text: "新規メッセージボードを作成しました。クリックするとメッセージページに移動します。",
                            type: "success"
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                window.location.href = "messageboard.php?messageid=" + data.messageid;
                            }
                        });
                },
                error: function () {
                    sweetAlert("エラー", "エラーのため作成できませんでした", "error");
                }
            });
        }

        setTimeout(function () {
            $("#navbar li:nth-child(1) a").click();
            autosize.update(autosizebox);
        }, 100);
    </script>
</div>
</body>
</html>
