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
    <!-- 地図スタイル -->
    <script src="js/googlemap_style.json"></script>
</head>
<body>
<?php

include __DIR__ . '/lib/mysql_credentials.php';
$activitylog
    = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
    . $_SESSION['userno'] . "', 'uploadjob.php', '" . date('Y-m-d G:i:s') . "')", $con)
or die('Error: ' . mysql_error());

?>

<div data-role="page" data-url="map-page">

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <h2>全体募集画面</h2>
        <p>※ 講演,講座,イベント,臨時バイト,ボランティアの募集情報をこの画面から登録し、GBER上に掲示できます。</p>
        <p>※ GBERに登録している全員が閲覧できます。</p>
        </br></br>
        <form id="upload-form">
            <label for="worktitile">【内容】</label>
            <div>タイトルと、具体的な募集内容を記入してください。具体的な日付と時間も入力してください。</div>
            <input type="text" id="worktitle" size="50" name="worktitle"
                   placeholder="タイトル(50文字まで)" required/>
            <textarea data-role="none" id="content" name="content"
                      placeholder="具体的な募集内容" required></textarea></br>
            </br>
            <label for="worktitile">【募集種類】</label>
            <div>募集内容の種類を選択してください。</div>
            <select name="workgenre" id="workgenre" data-theme="c"
                    data-native-menu="false">
                <option value="choose-one" data-placeholder="true">
                    募集内容の種類を一つお選びください
                </option>
                <option value="イベント、コンサート">イベント、コンサート</option>
                <option value="講演、講座、討論会">講演、講座、討論会</option>
                <option value="趣味体験会、教室">趣味体験会、教室</option>
                <option value="採用求人(定時)">採用求人(定時)</option>
                <option value="採用求人(臨時)">採用求人(臨時)</option>
                <option value="ボランティア">ボランティア</option>
                <option value="その他">その他</option>
            </select>
            </br>

            <label for="worktitile">【募集団体】</label>
            <div>募集団体/募集者の種類を選択してください。</div>
            <select name="groupgenre" id="groupgenre" data-theme="c"
                    data-native-menu="false">
                <option value="choose-one" data-placeholder="true">
                    募集団体/募集者の種類を一つお選びください
                </option>
                <option value="地方公共団体">地方公共団体</option>
                <option value="営利法人">営利法人</option>
                <option value="非営利法人">非営利法人</option>
                <option value="サークル・市民団体">サークル・市民団体</option>
                <option value="個人">個人</option>
                <option value="その他">その他</option>
            </select>
            </br>

            <label for"address">【場所】</label>
            <div>赤いピンのところが募集場所です。住所を入力するか、ピンを移動させるかしてください。</div>
            <input type="text" value="" name="address" id="mapsearch"
                   placeholder="住所入力欄">
            <div id="upload-map"></div>
            <p>緯度：<span id="visiblelat"></span>　経度：<span id="visiblelng"></span>
            </p>
            </br>

            <div style="display: none;">
                <label for="lat">緯度</label>
                <input type="text" id="lat" size="20" name="lat"
                       readonly="readonly" required/></br>
                <label for="lng">経度</label>
                <input type="text" id="lng" size="20" name="lng"
                       readonly="readonly" required/></br>
                <label for="userno">ユーザナンバー</label>
                <input type="text" id="userno" name="userno"
                       value="<?php echo $_SESSION['userno'] ?>"
                       readonly="readonly" required/></br>
            </div>

            <label for="workdate">【日付】</label>
            <div>募集日を選択してください。複数日ある場合は、すべて入力してください。</div>
            <input data-role="date" type="text" name="workdate" id="workdate"
                   placeholder="日付" required/>
            <div id="workdatelistshow"></div>
            <div id="workdatelisthide" style="display:none;"></div>
            </br>

            <label for="workernum">【対象、人数】</label>
            <div>募集対象者や人数を記入してください。</div>
            <input type="text" id="workernum" name="workernum"
                   placeholder="募集対象者、人数" required/></br>

            <label for="price">【費用/日給】</label>
            <div>参加費用や日給を記入してください。ボランティアの場合そう記載してください。</div>
            <input type="text" id="price" name="price" data-theme="c"
                   placeholder="費用/日給" required/></br>

            <label for="contact">【申込方法、連絡先】</label>
            <div>申込方法、連絡先、決定の通知方法を記入してください。</div>
            <input type="text" id="contact" name="contact" data-theme="c"
                   placeholder="申込方法、連絡先" required/></br>

            <input type="button" value="掲載する" onClick="uploadData();"/>

            <script>
                $(function () {
                    $.datepicker.setDefaults($.datepicker.regional['ja']);
                    $('#workdate').datepicker({
                        dateFormat: 'yy-mm-dd',
                        onSelect: function (dateText) {
                            $("#workdatelistshow").append("<span id=\"spandatelist_" + dateText + "\">" + dateText + "　<a onclick=\"deleteeachdate('" + dateText + "')\">削除</a></span></br>");
                            $("#workdatelisthide").append("<input input=\"text\" name=\"workdatelist[]\" id=\"inputdatelist_" + dateText + "\" value=\"" + dateText + "\"/>");
                            document.getElementById('workdate').value = "";
                        }
                    });

                });
            </script>
        </form>
    </div>

    <?php include("./common/commonFooter.php"); ?>
    <script>var isDemo= <?php echo $_ENV["IS_DEMO"] ?>;</script>
    <script type="text/javascript" src="js/uploadJob.js"></script>
    <script>
        $("#mapsearch").change(function () {
            var address = $("input[name='address']").val();
            geosearch(address);
        });
    </script>
    <?php $googleMap_callback = "initMap";
    include("./common/googleMapApi.php"); ?>
</div>
</body>
</html>
