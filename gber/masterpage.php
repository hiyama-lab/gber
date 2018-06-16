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

    //****** マスター権限のあるSESSION_IDかどうか確認 ******//
    $mastercheck = mysql_query("SELECT master FROM db_user WHERE userno = '"
        . $_SESSION['userno'] . "'") or die ("Query error: " . mysql_error());
    $masterflag = mysql_fetch_assoc($mastercheck)['master'];
    if ($masterflag == 0) {
        echo "マスター権限がありません";
        exit;
    }

    if ($masterflag) {
        //テーブル一覧を取得
        $result = mysql_query("SHOW TABLES FROM " . $database, $con)
        or die ("Query error: " . mysql_error());
        $dbtables = array();
        while ($row = mysql_fetch_row($result)) {
            $dbtables[] = $row[0];
        }
    }

    mysql_close($con);

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content"><!-- START OF CONTENT -->


        <h3>新規グループを作成する</h3>
        <?php
            $groupnomax = 0;
            foreach($groupnamerecords as $eachgroup){
                if($eachgroup['groupno']>$groupnomax){
                    $groupnomax = $eachgroup['groupno'];
                }
            }
            $groupnomax = $groupnomax+1;
        ?>
        グループ名：<input type="text" name="newgroupname" data-role="none" />
        <button data-role="none" onclick="creategroup(<?php echo $groupnomax;?>);">作成</button>
        <h3>グループ管理者を任命する</h3>
        グループ<select name="groupno" data-role="none">
        <?php
            foreach($groupnamerecords as $eachgroup){
                echo "<option value=\"".$eachgroup['groupno']."\">".h($eachgroup['groupname'])."</option>";
            }
        ?>
        </select>
        IDニックネーム<select name="userno" data-role="none" id="userselector"></select>
        <button data-role="none" onclick="assignadmin();">任命</button>
        </br>

        <h3>パスワード再設定フォーム</h3>
        <form id="register-form">
            <input type="text" id="mail" size="30" name="mail"
                   autocapitalize="none" placeholder="メアド" required/>
            <input type="password" id="pass" name="pass" placeholder="パスワード"
                   required/>
            <input type="button" value="登録する" data-theme="b" name="go"
                   onClick="updatePassword();"/>
        </form>
        <span id="repass"></span>

        <h3>スキルビューワー</h3>
        <p><a href="skillprofile.php" rel="external">個人のスキルプロフィールを確認する</a></p>


        <h3>仕事タグ付け</h3>
        <p><a href="worktaglist.php" rel="external">仕事のタグ付けを行う</a></p>
        <p><a href="worksummarylist.php" rel="external">仕事のサマリーを書く</a></p>
        <p><a href="worktagviewer.php" rel="external">仕事のタグ付けを確認する</a></p>


        <h3>登録住所一覧</h3>
        <p><a href="residenceviewer.php" rel="external">登録住所を見る</a></p>


    </div><!-- end of content -->


    <?php include("./common/commonFooter.php"); ?>
    <script>
        // グループを追加する
        function creategroup(groupno) {
            var JSONdata = {
                groupno: groupno,
                groupname: $("input[name='newgroupname']").val()
            };
            swal({
                title: "確認",
                text: "本当に作成しますか？",
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
                    url: baseurl + 'model/createGroup.php',
                    timeout: 10000,
                    success: function (data) {
                        swal({
                                title: "成功",
                                text: "グループを作成しました",
                                type: "success"
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = "masterpage.php";
                                }
                            });
                    },
                    error: function () {
                        sweetAlert("エラー", "エラーのためグループを作成できませんでした", "error");
                    }
                });
            });
        }


        // グループ管理者を任命する
        $("[name='groupno']").change(function () {
            var groupno = $(this).val();
            var JSONdata = {
                groupno: groupno
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(JSONdata),
                url: baseurl + 'model/downloadGroupMember.php',
                dataType: 'jsonp',
                jsonp: 'jsoncallback',
                timeout: 5000,
                success: function (receivedData, status) {
                    //セレクターをリセットする
                    $("#userselector").empty();
                    //各グループメンバー（でAdminじゃない人）を表示する
                    $.each(receivedData, function (i, item) {
                        var string = "<option value=\"" + item.userno + "\">" + item.userno + ": " + item.nickname + "</option>";
                        $("#userselector").append(string);
                    });
                },
                error: function () {
                    console.log('There was an error loading the data.');
                }
            });
        });
        $("[name='groupno']").val(['0']).trigger("change");

        function assignadmin() {
            var JSONdata = {
                userno: $("[name='userno']").val(),
                groupno: $("[name='groupno']").val()
            };
            swal({
                title: "確認",
                text: "本当に任命しますか？",
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
                    url: baseurl + 'model/assignAdmin.php',
                    timeout: 10000,
                    success: function (data) {
                        swal({
                                title: "成功",
                                text: "管理者に任命しました",
                                type: "success"
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = "masterpage.php";
                                }
                            });
                    },
                    error: function () {
                        sweetAlert("エラー", "エラーのため管理者に任命できませんでした", "error");
                    }
                });
            });
        }

        //パスワード再設定
        function updatePassword() {
            $.ajax({
                type: 'POST',
                data: $('#register-form').serialize(),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/updatePassword.php',
                timeout: 10000,
                error: function (data) {
                    $("#repass").empty();
                    $("#repass").prepend(data.responseText);
                    console.log(data.responseText);
                }
            });
        }

    </script>
</div><!-- end of wrapper -->
</body>
</html>