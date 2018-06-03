<?php
require_once __DIR__ . '/lib/sessionUtil.php';
require_logined_session();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <!-- テーブルソート用 -->
    <link rel="stylesheet" type="text/css" href="js/tablesorter/style.css"/>
    <script type="text/javascript"
            src="js/tablesorter/jquery.tablesorter.min.js"></script>
    <!-- インライン編集用 -->
    <script type="text/javascript"
            src="js/editable/jquery.editable.min.js"></script>
</head>
<body>

<div data-role="page"><!-- WRAPPER -->
    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    $groupno = $_GET['groupno'];
    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'groupmember.php?groupno=" . $groupno . "', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    if ($groupno == 0 || $groupno > count($groupnamelist) - 1) {
        echo "グループ名が無効です";
        exit;
    }

    //****** 管理者権限のあるSESSION_IDかどうか確認 ******//
    $admincheck = mysql_query("SELECT admin FROM grouplist WHERE groupno = '"
        . $groupno . "' and userno = '" . $_SESSION['userno'] . "'")
    or die ("Query error: " . mysql_error());
    if (mysql_fetch_assoc($admincheck)['admin'] == 0) {
        echo "管理者権限がありません";
        exit;
    }

    //****** グループ構成員のプロフィール一覧 ******//
    $sql
        = "SELECT * FROM db_user WHERE userno IN (SELECT userno FROM grouplist WHERE groupno = '"
        . $groupno . "')";
    $result = mysql_query($sql) or die ("Query error: " . mysql_error());
    $records = array();
    while ($row = mysql_fetch_assoc($result)) {
        $records[] = $row;
    }


    $result12
        = mysql_query("SELECT admin,eval,memo,userno FROM grouplist WHERE groupno = '"
        . $groupno . "'") or die ("Query error: " . mysql_error());
    while ($row12 = mysql_fetch_assoc($result12)) {
        $i = 0;
        foreach ($records as $eachrecord) {
            if ($eachrecord['userno'] == $row12['userno']) {
                $records[$i]['admin'] = $row12['admin'];
                $records[$i]['eval'] = $row12['eval'];
                $records[$i]['memo'] = $row12['memo'];
                break;
            }
            $i++;
        }
    }

    $i = 0;
    foreach ($records as $eachmember) {
        $result6 = mysql_query("SELECT giver FROM caretakerlist WHERE taker = '"
            . $eachmember['userno'] . "'") or die ("Query error: " . mysql_error());
        $giverlist = array();
        while ($row6 = mysql_fetch_assoc($result6)) {
            $giverlist[] = $row6['giver'];
        }
        $records[$i]['giver'] = $giverlist;
        $i++;
    }

    mysql_close($con);

    $evalarray = array('未評価', '初心者', '中級者', '上級者');

    ?>


    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content"><!-- START OF CONTENT -->
        <h1>グループメンバー</h1>


        <table id="sorttable">
            <thead>
            <tr>
                <th class="memberprof"></th>
                <th class="memberprof">ID</th>
                <th class="memberprof">管理者</th>
                <th class="memberprof">ニックネーム</th>
                <th class="memberprof">生年</th>
                <th class="memberprof">メールアドレス</th>
                <th class="memberprof">電話番号</th>
                <th class="memberprof">評価</th>
                <th class="memberprof">除名</th>
                <th class="memberprof">代理人</th>
                <th class="memberprof">メモ</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $adminarray = ["×", "◯"];
            $i = 0;
            foreach ($records as $eachmember) {
                $giverstr = "";
                $giveronlyone = true;
                if ($eachmember['giver'] != null) {
                    foreach ($eachmember['giver'] as $eachgiver) {
                        if ($giveronlyone) {
                            $giverstr .= $eachgiver;
                            $giveronlyone = false;
                        } else {
                            $giverstr .= "," . $eachgiver;
                        }
                    }
                }
                $str = "<tr>";
                $str .= "<td><a href=\"mypage.php?userno=" . $eachmember['userno']
                    . "\" rel=\"external\"><img src=\"./model/showuserimage.php?userno="
                    . $eachmember['userno']
                    . "\" onerror=\"this.src='img/noimage.svg';\" width=\"50px\" /></a></td>";
                $str .= "<td class=\"memberprof\">" . $eachmember['userno']
                    . "</td>";
                $str .= "<td class=\"memberprof\">"
                    . $adminarray[$eachmember['admin']] . "</td>";
                $str .= "<td class=\"memberprof\">" . $eachmember['nickname']
                    . "</td>";
                $str .= "<td class=\"memberprof\">" . $eachmember['birthyear']
                    . "</td>";
                $str .= "<td class=\"memberprof\">" . $eachmember['mail'] . "</td>";
                $str .= "<td class=\"memberprof\">" . $eachmember['phone']
                    . "</td>";
                $str .= "<td class=\"memberprof\"><span id=\"currenteval_" . $i
                    . "\"></span> <button data-role=\"none\" onclick=\"changeeval("
                    . $eachmember['userno'] . ", " . $groupno . ", currenteval[" . $i
                    . "], " . $i . ");\">変更</button></td>";
                $str .= "<td class=\"memberprof\"><button data-role=\"none\" onclick=\"removefromgroup("
                    . $eachmember['userno'] . ", " . $groupno
                    . ");\">除名</button></td>";
                if ($eachmember['giver'] == null) {
                    $str .= "<td class=\"memberprof\"></td>";
                } else {
                    $str .= "<td class=\"memberprof\">" . $giverstr . "</td>";
                }
                if ($eachmember['memo'] == null) {
                    $str .= "<td class=\"memberprof\" id=\"memo_"
                        . $eachmember['userno'] . "\"></td>";
                } else {
                    $str .= "<td class=\"memberprof\" id=\"memo_"
                        . $eachmember['userno'] . "\">" . $eachmember['memo']
                        . "</td>";
                }
                $str .= "</tr>\n";
                echo $str;
                $i++;
            }
            ?>
            </tbody>
        </table>
        <script>
            <?php
            foreach ($records as $eachmember) {
                echo "$(\"td#memo_" . $eachmember['userno']
                    . "\").editable({action:\"dblclick\"}, function(e){editusermemo("
                    . $groupno . ",e.value," . $eachmember['userno'] . ");});\n";
            }
            ?>
        </script>
        </br>

        <?php
        echo "<script>\nvar currenteval = [];\n";
        $i = 0;
        foreach ($records as $eachmember) {
            echo "currenteval[" . $i . "] = " . $eachmember['eval'] . ";\n";
            $i++;
        }
        echo "</script>\n";
        ?>


        <h2>代理人を設定する</h2>
        <p>ID:<input type="number" name="taker" data-role="none"/>の代理人をID:<input
                    type="number" name="giver" data-role="none"/>に<input
                    type="button" value="設定" data-inline="true" data-mini="true"
                    data-theme="c"
                    onclick="registerCaretaker(<?php echo $groupno; ?>);"/>
        </p></br>


        <!--h2>メンバーの職業スキルを登録する</h2-->


        <h2>グループに人を追加する</h2>
        <form id="searchnickname" method="post">
            <input type="search" name="search" id="search"
                   placeholder="ニックネームを入力してください"/>
        </form>
        <div id="search_results"></div>


    </div>


</div><!-- end of content -->


<?php include("./common/commonFooter.php"); ?>
<script>
    var groupmemberidlist = [<?php
        $firstlist = true;
        foreach ($records as $eachrecord) {
            if ($firstlist) {
                echo "\"" . $eachrecord["userno"] . "\"";
                $firstlist = false;
            } else {
                echo ",\"" . $eachrecord["userno"] . "\"";
            }
        }
        ?>];
    // ニックネームを検索し，検索結果から追加メンバーを選択する
    $(document).ready(function () {
        $("#search_results").slideUp();
        $("#search").keyup(function (e) {
            e.preventDefault();
            ajax_search();
        });
    });

    function ajax_search() {
        $("#search_results").show();
        var search_val = $("#search").val();
        var groupno =<?php echo $groupno; ?>;
        $.post(baseurl + 'model/findNickname.php', {
            search: search_val,
            groupno: groupno
        }, function (data) {
            if (data.length > 0) {
                $("#search_results").html(data);
            }
        })
    }

    function addgroupmember(groupno, userno) {
        var JSONdata = {
            groupno: groupno,
            userno: userno
        };
        swal({
            title: "確認",
            text: "本当に追加しますか？",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "はい！",
            cancelButtonText: "いいえ",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                type: 'POST',
                data: JSON.stringify(JSONdata),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/addgroupmember.php',
                timeout: 10000,
                success: function (data) {
                    swal({
                            title: "成功",
                            text: "グループメンバーを追加しました",
                            type: "success"
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                window.location.href = "groupmember.php?groupno=" + groupno;
                            }
                        });
                },
                error: function (data) {
                    alert("既に登録されています");
                }
            });
        });
    }

    // メンバーの大まかな評価をクリックで切り替える
    var evalarray = ["未評価", "初心者", "中級者", "上級者"];
    for (var i = 0; i < currenteval.length; i++) {
        $("#currenteval_" + i).prepend(evalarray[currenteval[i]]);
    }

    function changeeval(userno, groupno, eval, i) {
        var neweval = 0;
        if (eval < 3) {
            neweval = eval + 1;
        }
        currenteval[i] = neweval;
        $("#currenteval_" + i).empty();
        $("#currenteval_" + i).prepend(evalarray[currenteval[i]]);
        var JSONdata = {
            groupno: groupno,
            userno: userno,
            neweval: neweval
        };
        $.ajax({
            type: 'POST',
            data: JSON.stringify(JSONdata),
            dataType: "jsonp",
            jsonp: 'jsoncallback',
            url: baseurl + 'model/changeEval.php',
            timeout: 10000,
            error: function (data) {
                alert("エラーのため変更できませんでした");
            }
        });
    }

    function removefromgroup(userno, groupno) {
        var JSONdata = {
            groupno: groupno,
            userno: userno
        };
        swal({
            title: "確認",
            text: "本当に除名しますか？",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "はい",
            cancelButtonText: "いいえ",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                type: 'POST',
                data: JSON.stringify(JSONdata),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/removeFromGroup.php',
                timeout: 10000,
                success: function (data) {
                    swal({
                            title: "成功",
                            text: "グループメンバーを除名しました",
                            type: "success"
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                window.location.href = "groupmember.php?groupno=" + groupno;
                            }
                        });
                },
                error: function (data) {
                    alert("エラーのため除名できませんでした");
                }
            });
        });
    }

    function registerCaretaker(groupno) {
        var taker = $("input[name='taker']").val();
        var giver = $("input[name='giver']").val();
        var JSONdata = {
            taker: taker,
            giver: giver
        };
        if (taker == "" || giver == "") {
            sweetAlert("エラー", "IDが未記入です", "error");
            return;
        } else if ($.inArray(taker, groupmemberidlist) < 0 || $.inArray(giver, groupmemberidlist) < 0) {
            sweetAlert("エラー", "IDがグループメンバーのものではありません", "error");
            return;
        }
        swal({
            title: "確認",
            text: "本当に代理人を登録しますか？",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "はい",
            cancelButtonText: "いいえ",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                type: 'POST',
                data: JSON.stringify(JSONdata),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/registerCaretaker.php',
                timeout: 10000,
                success: function (data) {
                    swal({
                            title: "成功",
                            text: "代理人を登録しました",
                            type: "success"
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                window.location.href = "groupmember.php?groupno=" + groupno;
                            }
                        });
                },
                error: function () {
                    sweetAlert("エラー", "エラーのため代理人を登録できませんでした。IDが正しいかご確認ください。", "error");
                }
            });
        });
    }

    /**** 仕事の詳細を掲示責任者やマスターアカウントが編集できるように ****/
    function editusermemo(groupno, newcontent, userno) {
        var JSONdata = {
            groupno: groupno,
            newcontent: newcontent,
            userno: userno
        };
        $.ajax({
            type: 'POST',
            data: JSON.stringify(JSONdata),
            dataType: "jsonp",
            jsonp: 'jsoncallback',
            url: baseurl + 'model/editUserMemo.php',
            timeout: 10000,
            error: function () {
                sweetAlert("エラー", "エラーのため更新できませんでした", "error");
            }
        });
    }

    setTimeout(function () {
        $("#sorttable").tablesorter({
            sortMultiSortKey: 'altKey',
            headers: {
                0: {sorter: false},
                3: {sorter: false},
                5: {sorter: false},
                6: {sorter: false},
                8: {sorter: false}
            }
        });
    }, 100);
</script>
</div><!-- end of wrapper -->
</body>
</html>