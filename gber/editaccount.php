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
<div data-role="page">

    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    $userno = $_GET['userno'];

    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'editaccount.php?userno=" . $userno . "', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    // ユーザを世話する人を取得
    $result4 = mysql_query("SELECT giver FROM caretakerlist WHERE taker='"
        . $userno . "'") or die ("Query error: " . mysql_error());
    $caretakerflag = false;
    while ($row4 = mysql_fetch_assoc($result4)) {
        if ($row4['giver'] == $_SESSION['userno']) {
            $caretakerflag = true;
        }
    }

    // Get User Profile
    if ($userno == $_SESSION['userno'] || $caretakerflag) {
        $sql
            = "SELECT userno,mail,phone,nickname,gender,birthyear,pass,intro,mylat,mylng,address_string FROM db_user WHERE userno='"
            . $userno . "'";
        $result = mysql_query($sql) or die ("Query error: " . mysql_error());
        $records = array();
        $row = mysql_fetch_assoc($result);
        $records[] = $row;
        //var_dump($records);
    } else {
        echo "アクセス権限がありません";
        exit;
    }

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <form id="account-form">
            <div style="display: none;">
                <label for="userno">ユーザナンバー</label>
                <input type="text" id="userno" name="userno"
                       value="<?php echo $userno; ?>" readonly="readonly"
                       required/>
            </div>
            <label for="nickname">ニックネーム</label>
            <input type="text" id="nickname" name="nickname"
                   value="<?php echo $records[0]['nickname']; ?>"
                   required/></br>
            <label for="mail">メールアドレス</label>
            <input type="text" id="mail" name="mail"
                   value="<?php echo $records[0]['mail']; ?>"
                   required/></br>
            <label for="pass">パスワード</label>
            <input type="password" id="pass" name="pass" required/></br>
            <label for="pass_re">パスワード(再入力)</label>
            <input type="password" id="pass_re" name="pass_re" required/></br>
            <input type="button" value="アカウント情報を更新する" onClick="uploadData();"/>
        </form>
    </div>

    <?php include("./common/commonFooter.php"); ?>
    <script>

        function uploadData() {
            var pass = $("input[name='pass']").val();
            if ($("input[name='mail']").val() == "") {
                sweetAlert("エラー", "メールアドレスが未入力です。", "error");
            } else if (!$("input[name='mail']").val().match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/)) {
                sweetAlert("エラー", "メールアドレスの書式が無効です。", "error");
            } else if ($("input[name='nickname']").val() == "") {
                sweetAlert("エラー", "ニックネームが未入力です。", "error");
            } else if (pass != "" && (pass.length < 6 || pass.length > 32)) {
                sweetAlert("エラー", "パスワードは6文字以上32文字以下で設定してください。", "error");
            } else if( pass != $("input[name='pass_re']").val() ) {
                sweetAlert("エラー", "パスワードが再入力のものと一致しません。", "error");
            } else {
                uploadaccount();
            }
        }

        function uploadaccount() {
            $.ajax({
                type: 'POST',
                data: $('#account-form').serialize(),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/editAccount.php',
                timeout: 10000,
                success: function (data) {
                    swal({
                            title: "成功",
                            text: "アカウント情報を変更しました。クリックするとプロフィールページに移動します。",
                            type: "success"
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                window.location.href = "mypage.php?userno=" + $("input[name='userno']").val();
                            }
                        });
                },
                error: function () {
                    sweetAlert("エラー", "エラーのため変更できませんでした", "error");
                }
            })
        }
    </script>

</div>
</body>
</html>
