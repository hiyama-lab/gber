<?php
include __DIR__ . '/lib/sessioncheck.php';
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


    $groupno = $_GET['groupno'];
    $workid = $_GET['workid'];

    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'worktag.php?groupno=" . $groupno . "&workid="
        . $workid . "', '" . date('Y-m-d G:i:s') . "')", $con) or die('Error: '
        . mysql_error());

    $workdetail = array();
    if ($groupno == 0) {
        $workdetailresult
            = mysql_query("SELECT worktitle, content FROM helplist WHERE id='"
            . $workid . "'", $con) or die('Error: ' . mysql_error());
        $workdetail = mysql_fetch_assoc($workdetailresult);
    } else {
        $workdetailresult
            = mysql_query("SELECT worktitle, content FROM worklist WHERE id='" . $workid . "'", $con) or die('Error: ' . mysql_error());
        $workdetail = mysql_fetch_assoc($workdetailresult);
    }

    mysql_close($con);

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <h2><?php echo $workdetail['worktitle']; ?></h2>
        <p><?php echo nl2br($workdetail['content']); ?></p>
        <?php
        if ($groupno == 0) {
            echo "<p><a href=\"jobdetail.php?workid=" . $workid
                . "\" rel=\"external\">詳細</a></p>";
        } else {
            //echo "<p><a href=\"quotation.php?groupno=".$groupno."&workid=".$workid."\" rel=\"external\">詳細</a></p>";
        }
        ?>
        <form id="questionnaire_socialactivity"
              name="questionnaire_socialactivity">
            </br>
            <div style="display: none;">
                <label for="groupno">グループナンバー</label>
                <input type="text" id="groupno" name="groupno"
                       value="<?php echo $groupno; ?>" readonly="readonly"
                       required/></br>
                <label for="workid">ワークID</label>
                <input type="text" id="workid" name="workid"
                       value="<?php echo $workid; ?>" readonly="readonly"
                       required/></br>
                <label for="userno">ユーザID</label>
                <input type="text" id="userno" name="userno"
                       value="<?php echo $_SESSION['userno']; ?>"
                       readonly="readonly" required/></br>
            </div>


            <p>当てはまるものにチェックを入れてください</p>
            <p>※仕事/イベントと下記ジャンルに直接的な関連がなくても、<br>※下記のジャンルが好きな人は当該仕事/イベントに興味ありそうだと思われる場合は、<br>※チェックをつけるようにしてください。
            </p>


            <fieldset data-role="controlgroup" data-theme="c"
                      id="worktypecheckbox">
                <legend><b>就労業種</b></legend>
                <label for="checkbox-worktype-1">植木剪定</label><input
                        type="checkbox" name="checkbox-worktype-1"
                        id="checkbox-worktype-1" value="1">
                <label for="checkbox-worktype-2">農業</label><input
                        type="checkbox" name="checkbox-worktype-2"
                        id="checkbox-worktype-2" value="農業">
                <label for="checkbox-worktype-3">清掃</label><input
                        type="checkbox" name="checkbox-worktype-3"
                        id="checkbox-worktype-3" value="清掃">
                <label for="checkbox-worktype-4">家事代行</label><input
                        type="checkbox" name="checkbox-worktype-4"
                        id="checkbox-worktype-4" value="家事代行">
                <label for="checkbox-worktype-5">買い物代行</label><input
                        type="checkbox" name="checkbox-worktype-5"
                        id="checkbox-worktype-5" value="買い物代行">
                <label for="checkbox-worktype-6">組立・修理</label><input
                        type="checkbox" name="checkbox-worktype-6"
                        id="checkbox-worktype-6" value="組立・修理">
                <label for="checkbox-worktype-7">子守・介護</label><input
                        type="checkbox" name="checkbox-worktype-7"
                        id="checkbox-worktype-7" value="子守・介護">
                <label for="checkbox-worktype-8">趣味教室</label><input
                        type="checkbox" name="checkbox-worktype-8"
                        id="checkbox-worktype-8" value="趣味教室">
                <label for="checkbox-worktype-9">コンサルティング</label><input
                        type="checkbox" name="checkbox-worktype-9"
                        id="checkbox-worktype-9" value="コンサルティング">
            </fieldset>
            </br>


            <fieldset data-role="controlgroup" data-theme="c"
                      id="studycheckbox">
                <legend><b>学習・自己啓発・訓練</b></legend>
                <label for="checkbox-study-english">英語</label><input
                        type="checkbox" name="checkbox-study-english"
                        id="checkbox-study-english" value="1">
                <label for="checkbox-study-foreignlanguage">英語以外の外国語</label><input
                        type="checkbox" name="checkbox-study-foreignlanguage"
                        id="checkbox-study-foreignlanguage" value="1">
                <label for="checkbox-study-it">パソコンなど情報処理</label><input
                        type="checkbox" name="checkbox-study-it"
                        id="checkbox-study-it" value="1">
                <label for="checkbox-study-business">商業実務・ビジネス関係</label><input
                        type="checkbox" name="checkbox-study-business"
                        id="checkbox-study-business" value="1">
                <label for="checkbox-study-caretaking">介護関係</label><input
                        type="checkbox" name="checkbox-study-caretaking"
                        id="checkbox-study-caretaking" value="1">
                <label for="checkbox-study-housework">家政・家事</label><input
                        type="checkbox" name="checkbox-study-housework"
                        id="checkbox-study-housework" value="1">
                <label for="checkbox-study-liberalarts">人文・社会・自然科学</label><input
                        type="checkbox" name="checkbox-study-liberalarts"
                        id="checkbox-study-liberalarts" value="1">
                <label for="checkbox-study-art">芸術・文化</label><input
                        type="checkbox" name="checkbox-study-art"
                        id="checkbox-study-art" value="1">
            </fieldset>
            </br>


            <fieldset data-role="controlgroup" data-theme="c"
                      id="volunteercheckbox">
                <legend><b>地域活動</b></legend>
                <label for="checkbox-volunteer-health">健康や医療サービスに関係した活動</label><input
                        type="checkbox" name="checkbox-volunteer-health"
                        id="checkbox-volunteer-health" value="1">
                <label for="checkbox-volunteer-elderly">高齢者を対象とした活動</label><input
                        type="checkbox" name="checkbox-volunteer-elderly"
                        id="checkbox-volunteer-elderly" value="1">
                <label for="checkbox-volunteer-disable">障害者を対象とした活動</label><input
                        type="checkbox" name="checkbox-volunteer-disable"
                        id="checkbox-volunteer-disable" value="1">
                <label for="checkbox-volunteer-children">子供を対象とした活動</label><input
                        type="checkbox" name="checkbox-volunteer-children"
                        id="checkbox-volunteer-children" value="1">
                <label for="checkbox-volunteer-sport">スポーツ・文化・芸術・学術に関係した活動</label><input
                        type="checkbox" name="checkbox-volunteer-sport"
                        id="checkbox-volunteer-sport" value="1">
                <label for="checkbox-volunteer-town">まちづくりのための活動</label><input
                        type="checkbox" name="checkbox-volunteer-town"
                        id="checkbox-volunteer-town" value="1">
                <label for="checkbox-volunteer-safety">安全な生活のための活動</label><input
                        type="checkbox" name="checkbox-volunteer-safety"
                        id="checkbox-volunteer-safety" value="1">
                <label for="checkbox-volunteer-nature">自然や環境を守るための活動</label><input
                        type="checkbox" name="checkbox-volunteer-nature"
                        id="checkbox-volunteer-nature" value="1">
                <label for="checkbox-volunteer-disaster">災害に関係した活動</label><input
                        type="checkbox" name="checkbox-volunteer-disaster"
                        id="checkbox-volunteer-disaster" value="1">
                <label for="checkbox-volunteer-international">国際協力に関係した活動</label><input
                        type="checkbox" name="checkbox-volunteer-international"
                        id="checkbox-volunteer-international" value="1">
            </fieldset>
            </br>


            <fieldset data-role="controlgroup" data-theme="c"
                      id="hobbycheckbox">
                <legend><b>趣味・娯楽</b></legend>
                <label for="checkbox-hobby-musicalinstrument">楽器の演奏</label><input
                        type="checkbox" name="checkbox-hobby-musicalinstrument"
                        id="checkbox-hobby-musicalinstrument" value="1">
                <label for="checkbox-hobby-chorus">コーラス・声楽</label><input
                        type="checkbox" name="checkbox-hobby-chorus"
                        id="checkbox-hobby-chorus" value="1">
                <label for="checkbox-hobby-dance">舞踊・ダンス</label><input
                        type="checkbox" name="checkbox-hobby-dance"
                        id="checkbox-hobby-dance" value="1">
                <label for="checkbox-hobby-shodo">書道</label><input
                        type="checkbox" name="checkbox-hobby-shodo"
                        id="checkbox-hobby-shodo" value="1">
                <label for="checkbox-hobby-kado">華道</label><input
                        type="checkbox" name="checkbox-hobby-kado"
                        id="checkbox-hobby-kado" value="1">
                <label for="checkbox-hobby-sado">茶道</label><input
                        type="checkbox" name="checkbox-hobby-sado"
                        id="checkbox-hobby-sado" value="1">
                <label for="checkbox-hobby-wasai">和裁・洋裁</label><input
                        type="checkbox" name="checkbox-hobby-wasai"
                        id="checkbox-hobby-wasai" value="1">
                <label for="checkbox-hobby-knit">編み物・手芸</label><input
                        type="checkbox" name="checkbox-hobby-knit"
                        id="checkbox-hobby-knit" value="1">
                <label for="checkbox-hobby-cooking">料理・菓子作り</label><input
                        type="checkbox" name="checkbox-hobby-cooking"
                        id="checkbox-hobby-cooking" value="1">
                <label for="checkbox-hobby-gardening">園芸・ガーデニング</label><input
                        type="checkbox" name="checkbox-hobby-gardening"
                        id="checkbox-hobby-gardening" value="1">
                <label for="checkbox-hobby-diy">日曜大工</label><input
                        type="checkbox" name="checkbox-hobby-diy"
                        id="checkbox-hobby-diy" value="1">
                <label for="checkbox-hobby-painting">絵画・彫刻</label><input
                        type="checkbox" name="checkbox-hobby-painting"
                        id="checkbox-hobby-painting" value="1">
                <label for="checkbox-hobby-pottery">陶芸・工芸</label><input
                        type="checkbox" name="checkbox-hobby-pottery"
                        id="checkbox-hobby-pottery" value="1">
                <label for="checkbox-hobby-photo">写真撮影・プリント</label><input
                        type="checkbox" name="checkbox-hobby-photo"
                        id="checkbox-hobby-photo" value="1">
                <label for="checkbox-hobby-writing">詩・和歌・俳句・小説</label><input
                        type="checkbox" name="checkbox-hobby-writing"
                        id="checkbox-hobby-writing" value="1">
                <label for="checkbox-hobby-go">囲碁・将棋</label><input
                        type="checkbox" name="checkbox-hobby-go"
                        id="checkbox-hobby-go" value="1">
                <label for="checkbox-hobby-camp">キャンプ・釣り</label><input
                        type="checkbox" name="checkbox-hobby-camp"
                        id="checkbox-hobby-camp" value="1">
                <label for="checkbox-hobby-watchsport">スポーツ観覧</label><input
                        type="checkbox" name="checkbox-hobby-watchsport"
                        id="checkbox-hobby-watchsport" value="1">
                <label for="checkbox-hobby-watchperformance">演芸演劇鑑賞</label><input
                        type="checkbox" name="checkbox-hobby-watchperformance"
                        id="checkbox-hobby-watchperformance" value="1">
                <label for="checkbox-hobby-watchmovie">映画鑑賞</label><input
                        type="checkbox" name="checkbox-hobby-watchmovie"
                        id="checkbox-hobby-watchmovie" value="1">
                <label for="checkbox-hobby-listenmusic">音楽鑑賞</label><input
                        type="checkbox" name="checkbox-hobby-listenmusic"
                        id="checkbox-hobby-listenmusic" value="1">
                <label for="checkbox-hobby-reading">読書</label><input
                        type="checkbox" name="checkbox-hobby-reading"
                        id="checkbox-hobby-reading" value="1">
                <label for="checkbox-hobby-pachinko">ギャンブル(パチンコ,競馬など)</label><input
                        type="checkbox" name="checkbox-hobby-pachinko"
                        id="checkbox-hobby-pachinko" value="1">
                <label for="checkbox-hobby-karaoke">カラオケ</label><input
                        type="checkbox" name="checkbox-hobby-karaoke"
                        id="checkbox-hobby-karaoke" value="1">
                <label for="checkbox-hobby-game">ゲーム</label><input
                        type="checkbox" name="checkbox-hobby-game"
                        id="checkbox-hobby-game" value="1">
                <label for="checkbox-hobby-attraction">遊園地‧水族館‧動物園</label><input
                        type="checkbox" name="checkbox-hobby-attraction"
                        id="checkbox-hobby-attraction" value="1">
                <label for="checkbox-hobby-train">鉄道</label><input
                        type="checkbox" name="checkbox-hobby-train"
                        id="checkbox-hobby-train" value="1">
                <label for="checkbox-hobby-car">車</label><input type="checkbox"
                                                                name="checkbox-hobby-car"
                                                                id="checkbox-hobby-car"
                                                                value="1">
            </fieldset>
            </br>


            <fieldset data-role="controlgroup" data-theme="c" id="tripcheckbox">
                <legend><b>旅行・行楽</b></legend>
                <label for="checkbox-trip-daytrip">日帰り旅行</label><input
                        type="checkbox" name="checkbox-trip-daytrip"
                        id="checkbox-trip-daytrip" value="1">
                <label for="checkbox-trip-domestic">国内旅行(泊まり)</label><input
                        type="checkbox" name="checkbox-trip-domestic"
                        id="checkbox-trip-domestic" value="1">
                <label for="checkbox-trip-international">海外旅行</label><input
                        type="checkbox" name="checkbox-trip-international"
                        id="checkbox-trip-international" value="1">
            </fieldset>
            </br>


            <fieldset data-role="controlgroup" data-theme="c"
                      id="sportcheckbox">
                <legend><b>スポーツ</b></legend>
                <label for="checkbox-sport-baseball">野球</label><input
                        type="checkbox" name="checkbox-sport-baseball"
                        id="checkbox-sport-baseball" value="1">
                <label for="checkbox-sport-tabletennis">卓球</label><input
                        type="checkbox" name="checkbox-sport-tabletennis"
                        id="checkbox-sport-tabletennis" value="1">
                <label for="checkbox-sport-tennis">テニス</label><input
                        type="checkbox" name="checkbox-sport-tennis"
                        id="checkbox-sport-tennis" value="1">
                <label for="checkbox-sport-badminton">バドミントン</label><input
                        type="checkbox" name="checkbox-sport-badminton"
                        id="checkbox-sport-badminton" value="1">
                <label for="checkbox-sport-golf">ゴルフ</label><input
                        type="checkbox" name="checkbox-sport-golf"
                        id="checkbox-sport-golf" value="1">
                <label for="checkbox-sport-gateball">ゲートボール</label><input
                        type="checkbox" name="checkbox-sport-gateball"
                        id="checkbox-sport-gateball" value="1">
                <label for="checkbox-sport-bowling">ボウリング</label><input
                        type="checkbox" name="checkbox-sport-bowling"
                        id="checkbox-sport-bowling" value="1">
                <label for="checkbox-sport-fishing">釣り</label><input
                        type="checkbox" name="checkbox-sport-fishing"
                        id="checkbox-sport-fishing" value="1">
                <label for="checkbox-sport-swimming">水泳</label><input
                        type="checkbox" name="checkbox-sport-swimming"
                        id="checkbox-sport-swimming" value="1">
                <label for="checkbox-sport-skiing">スキー</label><input
                        type="checkbox" name="checkbox-sport-skiing"
                        id="checkbox-sport-skiing" value="1">
                <label for="checkbox-sport-climbing">登山ハイキング</label><input
                        type="checkbox" name="checkbox-sport-climbing"
                        id="checkbox-sport-climbing" value="1">
                <label for="checkbox-sport-cycling">サイクリング</label><input
                        type="checkbox" name="checkbox-sport-cycling"
                        id="checkbox-sport-cycling" value="1">
                <label for="checkbox-sport-jogging">ジョギング</label><input
                        type="checkbox" name="checkbox-sport-jogging"
                        id="checkbox-sport-jogging" value="1">
                <label for="checkbox-sport-walking">ウォーキング</label><input
                        type="checkbox" name="checkbox-sport-walking"
                        id="checkbox-sport-walking" value="1">
            </fieldset>
            </br>


            <input type="button" value="登録する" data-theme="b" name="go"
                   onClick="answerworktag();"/>
        </form>
    </div>

    <?php include("./common/commonFooter.php"); ?>
    <script type="text/javascript" src="js/questionnaire.js"></script>
</div>
</body>
</html>