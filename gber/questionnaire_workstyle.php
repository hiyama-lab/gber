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

    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'questionnaire_workstyle.php', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    //本人のみ記入できることにする。代理人もこういうこと知らないはずなので。
    $userno = $_GET['userno'];
    if (!authorize($_SESSION['userno'], ROLE['USER'], ['userno' => $userno])){
        echo "閲覧権限がありません";
        exit;
    }

    mysql_close($con);

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <h2>質問紙② 就労希望形態</h2>
        <p>希望就労頻度、場所、種類、目的について伺います。</p>
        <form id="questionnaire_workstyle" name="questionnaire_workstyle">
            </br>
            <div style="display: none;">
                <label for="userno">ユーザナンバー</label>
                <input type="text" id="userno" name="userno"
                       value="<?php echo $userno; ?>" readonly="readonly"
                       required/></br>
            </div>


            <label for="workdayperweek"><b>Q1. 1週間の希望就業日数</b></label>
            <span>1週間に何日間働きたいか選択してください。</span>
            <select name="workdayperweek" id="workdayperweek" data-theme="c"
                    data-iconpos="left" data-native-menu="false">
                <option value="choose-one" data-placeholder="true">
                    週あたり希望就業日数を選択してください
                </option>
                <option value="0">0日</option>
                <option value="1">1日</option>
                <option value="2">2日</option>
                <option value="3">3日</option>
                <option value="4">4日</option>
                <option value="5">5日</option>
                <option value="6">6日</option>
                <option value="7">7日</option>
            </select>
            </br>


            <label for="worktimeperday"><b>Q2. 1日の希望就業時間</b></label>
            <span>1日に何時間働きたいか選択してください。</span>
            <select name="worktimeperday" id="worktimeperday" data-theme="c"
                    data-iconpos="left" data-native-menu="false">
                <option value="choose-one" data-placeholder="true">
                    日あたり希望就業時間を選択してください
                </option>
                <option value="0">0時間</option>
                <option value="1">1時間</option>
                <option value="2">2時間</option>
                <option value="3">3時間</option>
                <option value="4">4時間</option>
                <option value="5">5時間</option>
                <option value="6">6時間</option>
                <option value="7">7時間</option>
                <option value="8">8時間</option>
                <option value="9">9時間以上</option>
            </select>
            </br>


            <fieldset data-role="controlgroup" data-theme="c"
                      id="transitcheckbox">
                <legend><b>Q3. 交通手段</b></legend>
                <span>利用できる交通手段を全て選択してください。</span></br>
                <label for="checkbox-car">自家用車</label><input type="checkbox"
                                                             name="checkbox-car"
                                                             id="checkbox-car"
                                                             value="1">
                <label for="checkbox-train">電車</label><input type="checkbox"
                                                             name="checkbox-train"
                                                             id="checkbox-train"
                                                             value="1">
                <label for="checkbox-bus">バス</label><input type="checkbox"
                                                           name="checkbox-bus"
                                                           id="checkbox-bus"
                                                           value="1">
                <label for="checkbox-bicycle">自転車</label><input type="checkbox"
                                                                name="checkbox-bicycle"
                                                                id="checkbox-bicycle"
                                                                value="1">
                <label for="checkbox-onfoot">徒歩</label><input type="checkbox"
                                                              name="checkbox-onfoot"
                                                              id="checkbox-onfoot"
                                                              value="1">
                <label for="checkbox-other">その他</label><input type="checkbox"
                                                              name="checkbox-other"
                                                              id="checkbox-other"
                                                              value="1">
            </fieldset>
            </br>


            <label for="commutetime"><b>Q4. 希望通勤時間</b></label>
            <span>どのくらいの距離までの仕事なら行きたいか、通勤時間で選択してください</span>
            <select name="commutetime" id="commutetime" data-theme="c"
                    data-iconpos="left" data-native-menu="false">
                <option value="choose-one" data-placeholder="true">
                    希望通勤時間を選択してください
                </option>
                <option value="15分">15分まで</option>
                <option value="30分">30分まで</option>
                <option value="45分">45分まで</option>
                <option value="1">1時間まで</option>
                <option value="1">1時間以上</option>
            </select>
            </br>


            <fieldset data-role="controlgroup" data-theme="c"
                      id="workobjectcheckbox">
                <legend><b>Q5. 就労目的</b></legend>
                <span>就労を希望する場合、その理由を以下の中から全て選択してください</span></br>
                <label for="checkbox-workobject-1">収入の補填</label><input
                        type="checkbox" name="checkbox-workobject-1"
                        id="checkbox-workobject-1" value="1">
                <label for="checkbox-workobject-2">生活水準の向上</label><input
                        type="checkbox" name="checkbox-workobject-2"
                        id="checkbox-workobject-2" value="1">
                <label for="checkbox-workobject-3">生きがい</label><input
                        type="checkbox" name="checkbox-workobject-3"
                        id="checkbox-workobject-3" value="1">
                <label for="checkbox-workobject-4">健康の保持</label><input
                        type="checkbox" name="checkbox-workobject-4"
                        id="checkbox-workobject-4" value="1">
                <label for="checkbox-workobject-5">社会参加・貢献</label><input
                        type="checkbox" name="checkbox-workobject-5"
                        id="checkbox-workobject-5" value="1">
                <label for="checkbox-workobject-6">頼まれた</label><input
                        type="checkbox" name="checkbox-workobject-6"
                        id="checkbox-workobject-6" value="1">
                <label for="checkbox-workobject-7">時間がある</label><input
                        type="checkbox" name="checkbox-workobject-7"
                        id="checkbox-workobject-7" value="1">
                <label for="checkbox-workobject-8">能力・経験の活用</label><input
                        type="checkbox" name="checkbox-workobject-8"
                        id="checkbox-workobject-8" value="1">
                <label for="checkbox-workobject-9">その他</label><input
                        type="checkbox" name="checkbox-workobject-9"
                        id="checkbox-workobject-9" value="1">
            </fieldset>
            </br>


            <input type="button" value="登録する" data-theme="b" name="go"
                   onClick="answerworkstyle();"/>
        </form>
    </div>

    <?php include("./common/commonFooter.php"); ?>
    <script type="text/javascript" src="js/questionnaire.js"></script>
</div>
</body>
</html>
