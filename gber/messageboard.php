<?php
require_once __DIR__ . '/lib/sessionUtil.php';
require_logined_session();
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
    $messageid = $_GET['messageid'];

    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'messageboard.php?messageid=" . $messageid . "', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    //メッセージのメンバーに入っているか確認する
    $result
        = mysql_query("SELECT userno,nickname FROM db_user WHERE userno IN (SELECT memberid FROM messagemember WHERE messageid='"
        . $messageid . "')") or die ("Query error: " . mysql_error());
    $memberlist = array();
    while ($row = mysql_fetch_assoc($result)) {
        $memberlist[$row['userno']]['nickname'] = $row['nickname'];
        $memberlist[$row['userno']]['userno'] = $row['userno'];
    }

    $notfound = true;
    foreach ($memberlist as $eachmember) {
        if ($eachmember['userno'] == $userno) {
            $notfound = false;
        }
    }
    if ($notfound) {
        echo "閲覧権限がありません";
        exit;
    }

    //メッセージタイトルを確認する
    $result3
        = mysql_query("SELECT messagename,workid FROM message WHERE messageid='"
        . $messageid . "'") or die ("Query error: " . mysql_error());
    $row3 = mysql_fetch_assoc($result3);
    $messagetitle = $row3['messagename'];
    $workid = $row3['workid'];

    //メッセージ一覧を取得する
    $result2
        = mysql_query("SELECT messageeachid,senderid,message,messagedate FROM messageeach WHERE messageid='"
        . $messageid . "' ORDER BY messagedate DESC LIMIT 100")
    or die ("Query error: " . mysql_error());
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
        <h2 style="margin:0 auto; text-align: center;"
            id="messagetitle"><?php echo $messagetitle; ?></h2></br>
        <?php
        if ($workid != 0) {
            echo "<a href=\"jobdetail.php?workid=" . $workid
                . "\" rel=\"external\">募集ページに戻る</a>";
        }
        ?>
        <script>
            $("h2#messagetitle").editable({action: "dblclick"}, function (e) {
                editmessagetitle(<?php echo $messageid; ?>, e.value);
            });
        </script>

        <h3>メンバー一覧<?php
            if ($workid == 0) {
                echo "　<button onclick=\"showaddmember();\" data-mini=\"true\" data-inline=\"true\" data-theme=\"c\">追加</button>";
            }
            ?></h3>
        <div id="addmember" style="display:none;">
            <input type="search" name="search" id="search"
                   placeholder="追加メンバーのニックネームを入力"/>
            <div id="search_results"></div>
            </br></br>
        </div>

        <?php
        echo "<table style=\"text-align: center;\"><tr>";
        foreach ($memberlist as $eachmember) {
            echo "<td><a href=\"mypage.php?userno=" . $eachmember['userno']
                . "\" rel=\"external\">"
                . $memberlist[$eachmember['userno']]['nickname'] . "</a></td>";
        }
        echo "</tr><tr>";
        foreach ($memberlist as $eachmember) {
            echo "<td><a href=\"mypage.php?userno=" . $eachmember['userno']
                . "\" rel=\"external\"><img src=\"./model/showuserimage.php?userno="
                . $eachmember['userno']
                . "\" onerror=\"this.src='img/noimage.svg';\" class=\"bbsphoto\" width=\"50px\"></a></td>";
        }
        if ($workid == 0) {
            echo "</tr><tr>";
            foreach ($memberlist as $eachmember) {
                if ($_SESSION['userno'] == $eachmember['userno']) {
                    echo "<td><button data-role=\"none\" onclick=\"removemember("
                        . $messageid . "," . $eachmember['userno']
                        . ",1)\">削除</button></td>";
                } else {
                    echo "<td><button data-role=\"none\" onclick=\"removemember("
                        . $messageid . "," . $eachmember['userno']
                        . ",0)\">削除</button></td>";
                }
            }
        }
        echo "</tr></table>";
        ?>
        </br></br>
        <form id="message-post-form">
            <div style="display: none;">
                <input type="text" id="userno" name="userno"
                       value="<?php echo $userno; ?>" readonly="readonly"
                       required/></br>
                <input type="number" id="messageid" name="messageid"
                       value="<?php echo $messageid; ?>" readonly="readonly"
                       required/></br>
                <input type="text" id="messagename" name="messagename"
                       value="<?php echo $messagetitle; ?>" readonly="readonly"
                       required/></br>
            </div>
            <textarea data-role="none" width="100%" id="postcontent"
                      name="postcontent" placeholder="メッセージをご記入ください。"
                      required></textarea>
            <input type="button" value="メッセージを投稿する" onClick="uploadMessage();"/>
            <input type="button" value="メッセージを投稿し、メール通知する"
                   onClick="uploadandemailMessage();"/>
        </form>


        </br>
        <div id="messagelist">
            <?php
            if (count($records2) == 0) {
                echo "まだメッセージはありません。";
            } else {


                foreach ($records2 as $eachrecord) {
                    $string = "<div class=\"chat-box\" id=\"messageid_"
                        . $eachrecord['messageeachid']
                        . "\"><div class=\"chat-face\"><img src=\"./model/showuserimage.php?userno="
                        . $eachrecord['senderid']
                        . "\" onerror=\"this.src='img/noimage.svg';\" class=\"bbsphoto\" width=\"50px\"></div>";
                    $eachrecord['message'] = nl2br($eachrecord['message']);
                    if ($eachrecord['senderid'] == $userno) {
                        $string .= "<div class=\"chat-area\"><div class=\"chat-hukidashi mine\" style=\"display:inline-block;\"><span class=\"mytweet\" id=\"mytweet_"
                            . $eachrecord['messageeachid'] . "\">"
                            . $eachrecord['message']
                            . "</span></br><span class=\"smallletter\">"
                            . $eachrecord['messagedate']
                            . " <span style=\"cursor: pointer\" onclick=\"erasemessage("
                            . $eachrecord['messageeachid']
                            . "); return false;\">削除</span></span></div></div></div>";
                    } else {
                        $string .= "<div class=\"chat-area\"><div class=\"chat-hukidashi someone\" style=\"display:inline-block;\">"
                            . $eachrecord['message']
                            . "</br><span class=\"smallletter\"><a href=\"mypage.php?userno="
                            . $eachrecord['senderid'] . "\" rel=\"external\">"
                            . $memberlist[$eachrecord['senderid']]['nickname']
                            . "</a> " . $eachrecord['messagedate']
                            . "</span></div></div></div>";
                    }
                    echo $string;
                    if ($eachrecord['senderid'] == $userno) {
                        echo "<script>$(\"span#mytweet_"
                            . $eachrecord['messageeachid']
                            . "\").editable({type:\"textarea\", action:\"dblclick\"}, function(e){editpost("
                            . $eachrecord['messageeachid']
                            . ",e.value);});</script>";
                    }
                }


            }
            ?>
        </div>
    </div>

    <script type="text/javascript">
        var userno = <?php echo $userno; ?>;
        var messageid = <?php echo $messageid; ?>;
    </script>
    <?php include("./common/commonFooter.php"); ?>
    <script>
        setTimeout(function () {
            var bbswidth = screen.width - 135;
            $(".chat-hukidashi").attr("style", "width:" + bbswidth + "px;");
        }, 10);

        function uploadMessage() {
            if ($("input[name='userno']").val() == "") {
                sweetAlert("エラー", "ユーザナンバーが未入力です。", "error");
            } else if ($("input[name='messageid']").val() == "") {
                sweetAlert("エラー", "メッセージIDが未入力です。", "error");
            } else if ($("[name='postcontent']").val() == "") {
                sweetAlert("エラー", "投稿内容が未入力です。", "error");
            } else {
                $("[name='postcontent']").val(AutoLink($("[name='postcontent']").val()));
                $.ajax({
                    type: 'POST',
                    data: $('#message-post-form').serialize(),
                    dataType: "jsonp",
                    jsonp: 'jsoncallback',
                    url: baseurl + 'model/post2message.php',
                    timeout: 10000,
                    success: function (data) {
                        swal({
                                title: "成功",
                                text: "投稿しました。",
                                type: "success"
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = "messageboard.php?messageid=" + messageid;
                                }
                            });
                    },
                    error: function () {
                        sweetAlert("エラー", "エラーのため投稿できませんでした", "error");
                    }
                })
            }
        }

        function uploadandemailMessage() {
            if ($("input[name='userno']").val() == "") {
                sweetAlert("エラー", "ユーザナンバーが未入力です。", "error");
            } else if ($("input[name='messageid']").val() == "") {
                sweetAlert("エラー", "メッセージIDが未入力です。", "error");
            } else if ($("input[name='messagename']").val() == "") {
                sweetAlert("エラー", "メッセージタイトルが未入力です。", "error");
            } else if ($("[name='postcontent']").val() == "") {
                sweetAlert("エラー", "投稿内容が未入力です。", "error");
            } else {
                $("[name='postcontent']").val(AutoLink($("[name='postcontent']").val()));
                $.ajax({
                    type: 'POST',
                    data: $('#message-post-form').serialize(),
                    dataType: "jsonp",
                    jsonp: 'jsoncallback',
                    url: baseurl + 'model/post2messageAndEmail.php',
                    //timeout: 10000,
                    success: function (data) {
                        swal({
                                title: "成功",
                                text: "投稿しました。",
                                type: "success"
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = "messageboard.php?messageid=" + messageid;
                                }
                            });
                    },
                    error: function () {
                        sweetAlert("エラー", "エラーのため投稿できませんでした", "error");
                    }
                })
            }
        }

        function erasemessage(messageeachid) {
            var JSONdata = {
                messageid: messageeachid
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
                    url: baseurl + 'model/eraseMessage.php',
                    timeout: 10000,
                    success: function (data) {
                        swal({
                                title: "成功",
                                text: "削除しました。",
                                type: "success"
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    $("#messageid_" + messageeachid).hide();
                                }
                            });
                    },
                    error: function () {
                        sweetAlert("エラー", "エラーのため削除できませんでした", "error");
                    }
                });
            });
        }

        function removemember(messageeachid, memberno, isme) {
            var JSONdata = {
                messageid: messageeachid,
                memberno: memberno
            };
            swal({
                title: "確認",
                text: "本当に削除しますか？",
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
                    url: baseurl + 'model/removeMessageMember.php',
                    timeout: 10000,
                    success: function (data) {
                        swal({
                                title: "成功",
                                text: "削除しました。",
                                type: "success"
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    if (isme == 1) {
                                        window.location.href = "messagelist.php";
                                    } else {
                                        window.location.href = "messageboard.php?messageid=" + messageid;
                                    }
                                }
                            });
                    },
                    error: function () {
                        if (isme == 1) {
                            sweetAlert("エラー", "エラーのため退会できませんでした", "error");
                        } else {
                            sweetAlert("エラー", "エラーのため削除できませんでした", "error");
                        }
                    }
                });
            });
        }

        function editpost(messageeachid, newmessage) {
            var JSONdata = {
                messageid: messageeachid,
                newmessage: AutoLink(newmessage)
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(JSONdata),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/editMessage.php',
                timeout: 10000,
                success: function (data) {
                    swal({
                            title: "成功",
                            text: "更新しました。",
                            type: "success"
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                window.location.href = "messageboard.php?messageid=" + messageid;
                            }
                        });
                },
                error: function () {
                    sweetAlert("エラー", "エラーのため更新できませんでした", "error");
                }
            });
        }

        function editmessagetitle(messageid, newtitle) {
            var JSONdata = {
                messageid: messageid,
                newtitle: newtitle
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(JSONdata),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/editMessageTitle.php',
                timeout: 10000,
                success: function (data) {
                    swal({
                            title: "成功",
                            text: "更新しました。",
                            type: "success"
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                window.location.href = "messageboard.php?messageid=" + messageid;
                            }
                        });
                },
                error: function () {
                    sweetAlert("エラー", "エラーのため更新できませんでした", "error");
                }
            });
        }

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
            $.post(baseurl + 'model/findNickname4Message.php', {search: search_val}, function (data) {
                if (data.length > 0) {
                    $("#search_results").html(data);
                }
            })
        }

        function addMessageMember(userno) {
            var JSONdata = {
                userno: userno,
                messageid: messageid
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
                    url: baseurl + 'model/addMessageMember.php',
                    timeout: 10000,
                    success: function (data) {
                        swal({
                                title: "成功",
                                text: "メンバーを追加しました",
                                type: "success"
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = "messageboard.php?messageid=" + messageid;
                                }
                            });
                    },
                    error: function (data) {
                        sweetAlert("エラー", "エラーのため追加できませんでした", "error");
                    }
                });
            });
        }

        function showaddmember() {
            $("#addmember").show();
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
