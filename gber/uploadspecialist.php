<?php
include __DIR__ . '/lib/sessioncheck.php';
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <!-- 地図スタイル -->
    <script src="js/googlemap_style.json"></script>
</head>
<body>
<?php

include __DIR__ . '/lib/mysql_credentials.php';
$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $_SESSION['userno'] . "', 'uploadspecialist.php', '" . date('Y-m-d G:i:s')
    . "')", $con) or die('Error: ' . mysql_error());

$groupno = $_GET['groupno'];

?>

<div data-role="page" data-url="map-page">

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <input type="text" value="" data-mini="true" name="address"
               id="mapsearch" placeholder="住所を入力する場合はこちら">
        <div id="upload-map"></div>
        <p>緯度：<span id="visiblelat"></span>　経度：<span id="visiblelng"></span></p>
        </br>
        <form id="upload-form">
            <div style="display: none;">
                <label for="lat">緯度</label>
                <input type="text" id="lat" size="20" name="lat"
                       readonly="readonly" required/>
                <label for="lng">経度</label>
                <input type="text" id="lng" size="20" name="lng"
                       readonly="readonly" required/>
                <label for="userno">ユーザナンバー</label>
                <input type="text" id="userno" name="userno"
                       value="<?php echo $_SESSION['userno'] ?>"
                       readonly="readonly" required/></br>
                <label for="message">メッセージ</label>
                <textarea name="message" id="message"
                          placeholder="もしメッセージがあれば入力してください"></textarea></br>
            </div>
            <label for="genre">【依頼先グループ】</label>
            <select name="genre" id="genre" data-theme="c"
                    data-native-menu="false">
                <option value="choose-one" data-placeholder="true">
                    グループを一つお選びください
                </option>
                <?php
                foreach ($groupnamerecords as $groupname) {
                    if ($groupname['groupno'] != 0) {
                        if ($groupno == $groupname['groupno']) {
                            echo "\t\t\t<option value=\"" . $groupname['groupno']
                                . "\" selected>" . $groupname['groupname']
                                . "</option>\n";
                        } else {
                            echo "\t\t\t<option value=\"" . $groupname['groupno']
                                . "\" disabled>" . $groupname['groupname']
                                . "</option>\n";
                        }
                    }
                }
                ?>
            </select>
            </br>
            <label for="worktitile">【依頼タイトル】</label>
            <span>※ 依頼者氏名、団体名、もしくは概要を記入してください。</span>
            <input type="text" id="worktitle" size="50" name="worktitle"
                   placeholder="タイトルを記入してください。(50文字まで)" required/></br>
            <label for="content">【具体的な内容】</label>
            <span>※ 仕事内容、場所など勤務に必要な情報を入力してください。</span>
            <textarea data-role="none" id="content" name="content"
                      placeholder="具体的な依頼内容をご記入ください。"
                      required></textarea></br></br>
            <label for="workdatetime">【依頼日時】</label>
            <span>※ (例)8/20日10:00〜、8月上旬から3日間</span></br>
            <span>※ 未定の場合、「未定」と記入してください。</span>
            <textarea data-role="none" id="workdatetime" name="workdatetime"
                      placeholder="勤務日時を記入してください。"
                      required></textarea></br></br>
            <label for="contact">【連絡先】</label>
            <span>※ メールアドレス、電話番号などを記入してください。</span>
            <input type="text" id="contact" size="50" name="contact"
                   placeholder="連絡先を記入してください。" required/></br></br>
            <p>※投稿後は②見積もりから続けて操作を行ってください。</p>
            <input type="button" value="投稿する" onClick="uploadData();"/>
        </form>
    </div>

    <?php include("./common/commonFooter.php"); ?>
    <script type="text/javascript" src="js/uploadSpecialist.js"></script>
    <?php $googleMap_callback = "initMap";
    include("./common/googleMapApi.php"); ?>
    <script>
        $("#mapsearch").change(function () {
            var address = $("input[name='address']").val();
            geosearch(address);
        });
    </script>
</div>
</body>
</html>
