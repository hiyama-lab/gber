<?php
include __DIR__ . '/lib/sessioncheck.php';
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <script type="text/javascript"
            src="js/editable/jquery.editable.min.js"></script>
</head>
<body>


<div data-role="page" id="mypage">
    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    $userno = $_SESSION['userno'];
    $groupno = $_GET['groupno'];

    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'groupbbs.php?groupno=" . $groupno . "', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    // グループに所属しているかを調べる
    $result
        = mysql_query("SELECT DISTINCT registeredid, admin FROM grouplist WHERE userno='"
        . $userno . "' and groupno='" . $groupno . "'") or die ("Query error: "
        . mysql_error());
    if (mysql_num_rows($result) == 0) {
        echo "グループに所属していません";
        exit;
    }
    // 管理人の場合、投稿者と同じ権限を与える
    $adminflag = 0;
    $records[] = mysql_fetch_assoc($result);
    if ($records[0]['admin'] == 1) {
        $adminflag = 1;
    }

    $result2
        = mysql_query("SELECT messageid,senderid,message,datetime,jobpost FROM bbs_group WHERE groupno=$groupno ORDER BY messageid DESC LIMIT 100") or die ("Query error: "
        . mysql_error());
    $records2 = array();
    while ($row2 = mysql_fetch_assoc($result2)) {
        $records2[] = $row2;
    }

    mysql_close($con);

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <h2 style="margin:0 auto; text-align: center;"><?php echo $groupnamelist[$groupno]; ?>
            掲示板</h2>
        <div>
            <?php

            foreach ($groupnamerecords as $eachgroup) {
                if ($eachgroup['groupno'] == $groupno) {
                    if (array_key_exists('groupmemo', $groupnamerecords)) {
                        echo $eachgroup['groupmemo'];
                    }
                }
            }

            ?>
        </div>
        <form id="bbs-post-form">
            <div style="display: none;">
                <input type="text" id="userno" name="userno"
                       value="<?php echo $userno; ?>" readonly="readonly"
                       required/></br>
                <input type="number" id="groupno" name="groupno"
                       value="<?php echo $groupno; ?>" readonly="readonly"
                       required/></br>
            </div>
            <textarea data-role="none" width="100%" id="postcontent"
                      name="postcontent" placeholder="投稿内容をご記入ください。"
                      required></textarea>
            <input type="button" value="掲示板に投稿する" onClick="uploadPost();"/>
            <?php
            if ($groupno > 0) {
                echo "<p><font color=\"red\">※メール投稿は時間がかかります(10秒程度)。ボタンは1度だけ押して待機してください。</font><p>";
                echo "<input type=\"button\" value=\"掲示板に投稿し、メール通知する\" onClick=\"uploadPostAndEmail();\" />";
            }
            ?>
        </form>
        </br>
        <div id="chat-screen">
            <?php

            foreach ($records2 as $eachrecord) {
                $string = "<div class=\"chat-box\" id=\"messageid_"
                    . $eachrecord['messageid']
                    . "\"><div class=\"chat-face\"><img src=\"./model/showuserimage.php?userno="
                    . $eachrecord['senderid']
                    . "\" onerror=\"this.src='img/noimage.svg';\" class=\"bbsphoto\" width=\"50px\"></div>";
                $eachrecord['message'] = nl2br($eachrecord['message']);
                if ($eachrecord['senderid'] == $userno
                    || $userno == 1
                ) { //そのポストの投稿者のとき
                    if ($eachrecord['jobpost'] == 1) {
                        $string .= "<div class=\"chat-area\"><div class=\"chat-hukidashi bbsjobpost\" style=\"display:inline-block;\"><span class=\"mytweet\">"
                            . $eachrecord['message']
                            . "</span></br><span class=\"smallletter\"><a href=\"mypage.php?userno="
                            . $eachrecord['senderid']
                            . "\" rel=\"external\">投稿者ID: "
                            . $eachrecord['senderid'] . "</a> "
                            . $eachrecord['datetime']
                            . " <span style=\"cursor: pointer\" onclick=\"erasepost("
                            . $groupno . "," . $eachrecord['messageid']
                            . "); return false;\">削除</span></span></div></div></div>";
                    } else {
                        $string .= "<div class=\"chat-area\"><div class=\"chat-hukidashi mine\" style=\"display:inline-block;\"><span class=\"mytweet\" id=\"mytweet_"
                            . $eachrecord['messageid'] . "\">"
                            . $eachrecord['message']
                            . "</span></br><span class=\"smallletter\"><a href=\"mypage.php?userno="
                            . $eachrecord['senderid']
                            . "\" rel=\"external\">投稿者ID: "
                            . $eachrecord['senderid'] . "</a> "
                            . $eachrecord['datetime']
                            . " <span style=\"cursor: pointer\" onclick=\"erasepost("
                            . $groupno . "," . $eachrecord['messageid']
                            . "); return false;\">削除</span></span></div></div></div>";
                    }
                } else {
                    if ($adminflag) { //管理者が見ているとき
                        if ($eachrecord['jobpost'] == 1) {
                            $string .= "<div class=\"chat-area\"><div class=\"chat-hukidashi bbsjobpost\" style=\"display:inline-block;\"><span class=\"mytweet\">"
                                . $eachrecord['message']
                                . "</span></br><span class=\"smallletter\"><a href=\"mypage.php?userno="
                                . $eachrecord['senderid']
                                . "\" rel=\"external\">投稿者ID: "
                                . $eachrecord['senderid'] . "</a> "
                                . $eachrecord['datetime']
                                . " <span style=\"cursor: pointer\" onclick=\"erasepost("
                                . $groupno . "," . $eachrecord['messageid']
                                . "); return false;\">削除</span></span></div></div></div>";
                        } else {
                            $string .= "<div class=\"chat-area\"><div class=\"chat-hukidashi someone\" style=\"display:inline-block;\"><span class=\"mytweet\" id=\"mytweet_"
                                . $eachrecord['messageid'] . "\">"
                                . $eachrecord['message']
                                . "</span></br><span class=\"smallletter\"><a href=\"mypage.php?userno="
                                . $eachrecord['senderid']
                                . "\" rel=\"external\">投稿者ID: "
                                . $eachrecord['senderid'] . "</a> "
                                . $eachrecord['datetime']
                                . " <span style=\"cursor: pointer\" onclick=\"erasepost("
                                . $groupno . "," . $eachrecord['messageid']
                                . "); return false;\">削除</span></span></div></div></div>";
                        }
                    } else { //どちらでもないとき
                        if ($eachrecord['jobpost'] == 1) {
                            $string .= "<div class=\"chat-area\"><div class=\"chat-hukidashi bbsjobpost\" style=\"display:inline-block;\">"
                                . $eachrecord['message']
                                . "</br><span class=\"smallletter\"><a href=\"mypage.php?userno="
                                . $eachrecord['senderid']
                                . "\" rel=\"external\">投稿者ID: "
                                . $eachrecord['senderid'] . "</a> "
                                . $eachrecord['datetime']
                                . "</span></div></div></div>";
                        } else {
                            $string .= "<div class=\"chat-area\"><div class=\"chat-hukidashi someone\" style=\"display:inline-block;\">"
                                . $eachrecord['message']
                                . "</br><span class=\"smallletter\"><a href=\"mypage.php?userno="
                                . $eachrecord['senderid']
                                . "\" rel=\"external\">投稿者ID: "
                                . $eachrecord['senderid'] . "</a> "
                                . $eachrecord['datetime']
                                . "</span></div></div></div>";
                        }
                    }
                }
                echo $string;
                if (($eachrecord['senderid'] == $userno || $adminflag
                        || $userno == 1)
                    && $eachrecord['jobpost'] != 1
                ) {
                    echo "<script>$(\"span#mytweet_" . $eachrecord['messageid']
                        . "\").editable({type:\"textarea\", action:\"dblclick\"}, function(e){editpost("
                        . $groupno . "," . $eachrecord['messageid']
                        . ",e.value);});</script>";
                }
            }

            ?>
        </div>
    </div>

    <script type="text/javascript">
        var userno = <?php echo $userno; ?>;
        var groupno = <?php echo $groupno; ?>;
    </script>
    <?php include("./common/commonFooter.php"); ?>
    <script>
        setTimeout(function () {
            var bbswidth = screen.width - 135;
            //alert(bbswidth);
            $(".chat-hukidashi").attr("style", "width:" + bbswidth + "px;");
        }, 10);

        function uploadPost() {
            if ($("input[name='userno']").val() == "") {
                sweetAlert("エラー", "ユーザナンバーが未入力です。", "error");
            } else if ($("[name='postcontent']").val() == "") {
                sweetAlert("エラー", "投稿内容が未入力です。", "error");
            } else {
                $("[name='postcontent']").val(AutoLink($("[name='postcontent']").val()));
                $.ajax({
                    type: 'POST',
                    data: $('#bbs-post-form').serialize(),
                    dataType: "jsonp",
                    jsonp: 'jsoncallback',
                    url: baseurl + 'model/post2bbs.php',
                    timeout: 10000,
                    success: function (data) {
                        swal({
                                title: "成功",
                                text: "掲示板に投稿しました。クリックすると掲示板ページに移動します。",
                                type: "success"
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = "groupbbs.php?groupno=" + groupno;
                                }
                            });
                    },
                    error: function () {
                        sweetAlert("エラー", "エラーのため投稿できませんでした", "error");
                    }
                })
            }
        }

        function uploadPostAndEmail() {
            if ($("input[name='userno']").val() == "") {
                sweetAlert("エラー", "ユーザナンバーが未入力です。", "error");
            } else if ($("[name='postcontent']").val() == "") {
                sweetAlert("エラー", "投稿内容が未入力です。", "error");
            } else {
                $("[name='postcontent']").val(AutoLink($("[name='postcontent']").val()));
                $.ajax({
                    type: 'POST',
                    data: $('#bbs-post-form').serialize(),
                    dataType: "jsonp",
                    jsonp: 'jsoncallback',
                    url: baseurl + 'model/post2bbsAndEmail.php',
                    //timeout: 10000,
                    success: function (data) {
                        swal({
                                title: "成功",
                                text: "掲示板に投稿しました。クリックすると掲示板ページに移動します。",
                                type: "success"
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = "groupbbs.php?groupno=" + groupno;
                                }
                            });
                    },
                    error: function () {
                        sweetAlert("エラー", "エラーのため投稿できませんでした", "error");
                    }
                })
            }
        }

        function erasepost(groupno, messageid) {
            var JSONdata = {
                groupno: groupno,
                messageid: messageid
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(JSONdata),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/eraseBBSPost.php',
                timeout: 10000,
                success: function (data) {
                    swal({
                            title: "成功",
                            text: "削除しました。",
                            type: "success"
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                $("#messageid_" + messageid).hide();
                            }
                        });
                },
                error: function () {
                    sweetAlert("エラー", "エラーのため削除できませんでした", "error");
                }
            });
        }

        function editpost(groupno, messageid, newmessage) {
            var JSONdata = {
                groupno: groupno,
                messageid: messageid,
                newmessage: AutoLink(newmessage)
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(JSONdata),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/editBBSPost.php',
                timeout: 10000,
                success: function (data) {
                    swal({
                            title: "成功",
                            text: "更新しました。",
                            type: "success"
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                window.location.href = "groupbbs.php?groupno=" + groupno;
                            }
                        });
                },
                error: function () {
                    sweetAlert("エラー", "エラーのため更新できませんでした", "error");
                }
            });
        }

        function AutoLink(str) {
            var regexp_url = /((h?)(ttps?:\/\/[a-zA-Z0-9.\-_@:/~?%&;=+#',()*!]+))/g; // ']))/;
            var regexp_makeLink = function (all, url, h, href) {
                return '<a href="h' + href + '" rel="external">' + url + '</a>';
            }

            return str.replace(regexp_url, regexp_makeLink);
        }
    </script>
</div>
</body>
</html>
