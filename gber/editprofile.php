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
        . $_SESSION['userno'] . "', 'editprofile.php?userno=" . $userno . "', '"
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
            = "SELECT userno,mail,phone,nickname,gender,birthyear,intro,mylat,mylng,address_string FROM db_user WHERE userno='"
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
        <!--input type="button" onclick="javascript:history.back()" value="戻る"-->
        <div>プロフィール画像のプレビュー</div>
        <div id="preview_field">
            <img src="./model/showuserimage.php?userno=<?php echo $userno; ?>"
                 onerror="this.src='img/noimage.svg';"/>
        </div>
        <!--p>プロフィール画像の変更は現在行えません。</p-->

        <div>アップロード画像は2MBまでです。PNG、JPEG、GIFファイルが利用できます。</div>
        <form id="profileimage">
            <div style="display: none;"><input type="number" name="photouserno"
                                               value="<?php echo $userno; ?>"
                                               readonly="readonly"/></div>
            <input type="file" name="file" id="file" onchange="preview(this)"/>
        </form>

        <form id="profile-form">
            <div style="display: none;">
                <label for="userno">ユーザナンバー</label>
                <input type="text" id="userno" name="userno"
                       value="<?php echo $userno; ?>" readonly="readonly"
                       required/></br>
                <label for="lat">緯度</label>
                <input type="text" id="lat" size="20" name="lat"
                       value="<?php echo $records[0]['mylat']; ?>"
                       readonly="readonly" required/></br>
                <label for="lng">経度</label>
                <input type="text" id="lng" size="20" name="lng"
                       value="<?php echo $records[0]['mylng']; ?>"
                       readonly="readonly" required/></br>
                <input type="text" id="mail" size="30" name="mail"
                       value="<?php echo $records[0]['mail']; ?>"
                       autocapitalize="none" readonly="readonly" required/></br>
            </div>
            <label for="address">自宅住所</label>
            <input type="text" name="address" id="mapsearch"
                   value="<?php echo $records[0]['address_string']; ?>"
                   placeholder="住所を入力してください" required/></br>
            <label for="mail">メールアドレス(現在変更不可)</label>
            <p>　<?php echo $records[0]['mail']; ?></p>
            <label for="phone">電話番号</label>
            <input type="text" id="phone" size="30" name="phone"
                   value="<?php echo $records[0]['phone']; ?>"
                   placeholder="電話番号を記入してください。" required/></br>
            <label for="nickname">ニックネーム</label>
            <input type="text" id="nickname" name="nickname"
                   value="<?php echo $records[0]['nickname']; ?>"
                   required/></br>
            <label for="birthyear">生年(西暦，半角数字4桁)</label>
            <input type="number" name="birthyear" id="birthyear" min="1900"
                   step="1" value="<?php echo $records[0]['birthyear']; ?>"
                   placeholder="生年を記入してください。" required/></br>
            <label for="gender">性別</label>
            <select name="gender" id="gender" data-theme="c" data-iconpos="left"
                    data-native-menu="false">
                <option value="choose-one" data-placeholder="true">性別を選択してください
                </option>
                <option value="男性">男性</option>
                <option value="女性">女性</option>
                <option value="その他">その他</option>
            </select>
            <label for="intro">自己紹介</label>
            <textarea data-role="none" id="intro" name="intro"
                      placeholder="自己紹介文をご記入ください。" required></textarea></br>
            <input type="button" value="プロフィールを更新する" onClick="uploadData();"/>
            <script>
                var userdetail = <?php echo json_encode($records); ?>;
                document.getElementById('gender').value = userdetail[0].gender;
                document.getElementById('intro').value = userdetail[0].intro;
            </script>
        </form>
    </div>


    <?php include("./common/commonFooter.php"); ?>
    <?php include("./common/googleMapApi.php"); ?>
    <script>

        var imgchangeflag = 0;

        function uploadData() {
            // プロフィール部分のチェック
            if ($("input[name='mail']").val() == "") {
                sweetAlert("エラー", "メールアドレスが未入力です。", "error");
            } else if (!$("input[name='mail']").val().match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/)) {
                sweetAlert("エラー", "メールアドレスの書式が無効です。", "error");
            } else if ($("input[name='phone']").val() == "") {
                sweetAlert("エラー", "電話番号が未入力です。", "error");
            } else if ($("input[name='address']").val() == "") {
                sweetAlert("エラー", "住所が未入力です。", "error");
            } else if ($("input[name='lat']").val() == "") {
                sweetAlert("エラー", "緯度が未入力です。", "error");
            } else if ($("input[name='lng']").val() == "") {
                sweetAlert("エラー", "経度が未入力です。", "error");
            } else if ($("input[name='nickname']").val() == "") {
                sweetAlert("エラー", "ニックネームが未入力です。", "error");
            } else if ($("input[name='userno']").val() == "") {
                sweetAlert("エラー", "ユーザナンバーが未入力です。", "error");
            } else if ($("input[name='birthyear']").val() == "") {
                sweetAlert("エラー", "生年が未入力です。", "error");
            } else if (!$("input[name='birthyear']").val().match(/^-?[0-9]+$/)) {
                sweetAlert("エラー", "生年が整数ではありません。", "error");
            } else if ($("input[name='birthyear']").val() < 1900) {
                sweetAlert("エラー", "生年が過去すぎます。", "error");
            } else if ($("[name='gender']").val() == "choose-one") {
                sweetAlert("エラー", "性別が選択されていません。", "error");
            } else {
                if (imgchangeflag == 0) { // 画像を変更していない時
                    uploadprofile();
                }
                if (imgchangeflag == 1) { // 画像以外を選択した時
                    sweetAlert("エラー", "画像ファイルとして使えるのはpng,jpeg,gifファイルのみです。", "error");
                    return;
                } else if (imgchangeflag == 2) { //画像が容量オーバー
                    sweetAlert("エラー", "画像ファイルが容量オーバーです。2MB以下のファイルを選択してください。", "error");
                    return;
                } else if (imgchangeflag == 3) { //画像が選択された時アップロードする
                    uploadimage();
                }
            }
        }

        function uploadimage() {
            var fd = new FormData($('#profileimage').get(0));
            $.ajax({
                url: baseurl + 'model/uploadimage.php?userno=' + userdetail[0].userno,
                type: "POST",
                data: fd,
                processData: false,
                contentType: false,
                success: function (data) {
                    uploadprofile();
                },
                error: function () {
                    sweetAlert("エラー", "エラーのため画像を変更できませんでした", "error");
                }
            })
        }

        function uploadprofile() {
            // プロフィール部分の送信
            $.ajax({
                type: 'POST',
                data: $('#profile-form').serialize(),
                dataType: "jsonp",
                jsonp: 'jsoncallback',
                url: baseurl + 'model/editProfile.php',
                timeout: 10000,
                success: function (data) {
                    swal({
                            title: "成功",
                            text: "プロフィール変更しました。クリックするとプロフィールページに移動します。",
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

        function preview(ele) {
            if (!ele.files.length) { // ファイル未選択
                imgchangeflag = 1;
                return;
            }
            var file = ele.files[0];
            if (!/^image\/(png|jpeg|gif)$/.test(file.type)) { // typeプロパティでMIMEタイプを参照
                imgchangeflag = 1;
                sweetAlert("エラー", "選択されたファイルは画像ファイルではありません。", "error");
                return;
            }

            if (file.size > 2000000) { // 容量オーバー
                imgchangeflag = 2;
                sweetAlert("エラー", "画像ファイルが容量オーバーです。2MB以下のファイルを選択してください。", "error");
                return;
            }

            var img = document.createElement('img');
            var fr = new FileReader();
            fr.onload = function () {
                img.src = fr.result;  // 読み込んだ画像データをsrcにセット
                document.getElementById('preview_field').appendChild(img);
            }
            fr.readAsDataURL(file);  // 画像読み込み

            imgchangeflag = 3;

            // 画像名・MIMEタイプ・ファイルサイズ
            document.getElementById('preview_field').innerHTML = "ファイルタイプ" + file.type + "</br>ファイルサイズ" + file.size + "B</br>";
        }

        $("#mapsearch").change(function () {
            var address = $("input[name='address']").val();
            geosearch(address);
        });

        function geosearch(address) {
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var addressLatLng = results[0].geometry.location;
                    document.getElementById('lat').value = addressLatLng.lat();
                    document.getElementById('lng').value = addressLatLng.lng();
                } else {
                    alert("Error");
                }
            });
        }
    </script>

</div>
</body>
</html>
