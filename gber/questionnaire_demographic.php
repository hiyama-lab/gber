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
        . $_SESSION['userno'] . "', 'questionnaire_demographic.php', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    //本人のみ記入できることにする。代理人もこういうこと知らないはずなので。
    $userno = $_GET['userno'];
    if ($userno != $_SESSION['userno'] && $_SESSION['userno'] != 1) {
        echo "閲覧権限がありません";
        exit;
    }

    mysql_close($con);

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <h2>質問紙① 基本情報</h2>
        <p>職歴、資格、生活、運動状況について伺います。</p>
        <form id="questionnaire_demographic" name="questionnaire_demographic">
            </br>
            <div style="display: none;">
                <label for="userno">ユーザナンバー</label>
                <input type="text" id="userno" name="userno"
                       value="<?php echo $userno; ?>" readonly="readonly"
                       required/></br>
            </div>


            <label for="gakureki"><b>Q1. 最終学歴</b></label>
            <span>最終学歴を選択してください。</span>
            <select name="gakureki" id="gakureki" data-theme="c"
                    data-iconpos="left" data-native-menu="false">
                <option value="choose-one" data-placeholder="true">
                    最終学歴を選択してください
                </option>
                <option value="中学校">中学校</option>
                <option value="高等学校">高等学校</option>
                <option value="専門学校">専門学校</option>
                <option value="高等専門学校">高等専門学校</option>
                <option value="短期大学">短期大学</option>
                <option value="4年制大学">4年制大学</option>
                <option value="大学院(修士)">大学院(修士)</option>
                <option value="大学院(博士)">大学院(博士)</option>
                <option value="その他">その他</option>
            </select>
            </br>


            <label for="gyoushu"><b>Q2. 業種(大分類)</b></label>
            <span>もっとも当てはまる業種を1つ選択してください。</span>
            <select name="gyoushu" id="gyoushu" data-theme="c"
                    data-iconpos="left" data-native-menu="false"
                    onchange="selectgyoushu(this)">
                <option value="choose-one" data-placeholder="true">
                    業種(大分類)を選択してください
                </option>
                <option value="0">IT・通信・インターネット</option>
                <option value="1">機械・電気・電子</option>
                <option value="2">素材</option>
                <option value="3">住宅関連</option>
                <option value="4">生活関連</option>
                <option value="5">商社</option>
                <option value="6">サービス</option>
                <option value="7">レジャー</option>
                <option value="8">流通・小売</option>
                <option value="9">フード</option>
                <option value="10">マスコミ・広告・デザイン</option>
                <option value="11">金融・保険</option>
                <option value="12">コンサルティング</option>
                <option value="13">不動産・建設・設備</option>
                <option value="14">運輸・交通・物流・倉庫</option>
                <option value="15">環境・エネルギー</option>
                <option value="16">公的機関</option>
                <option value="17">その他</option>
            </select>
            </br>
            <label for="gyoushu_detail"><b>Q3. 業種(中分類)</b></label>
            <span>もっとも当てはまる業種を1つ選択してください。まず大分類から回答してください。</span>
            <select name="gyoushu_detail" id="gyoushu_detail" data-theme="c"
                    data-iconpos="left" data-native-menu="false">
                <option value="choose-one" data-placeholder="true">
                    業種(中分類)を選択してください
                </option>
            </select>
            </br>


            <label for="shokushu"><b>Q4. 職種(大分類)</b></label>
            <span>もっとも当てはまる職種を1つ選択してください。</span>
            <select name="shokushu" id="shokushu" data-theme="c"
                    data-iconpos="left" data-native-menu="false"
                    onchange="selectshokushu(this)">
                <option value="choose-one" data-placeholder="true">
                    職種(大分類)を選択してください
                </option>
                <option value="0">営業</option>
                <option value="1">企画・経営</option>
                <option value="2">管理・事務</option>
                <option value="3">販売・フード・アミューズメント</option>
                <option value="4">美容・ブライダル・ホテル・交通</option>
                <option value="5">医療・福祉</option>
                <option value="6">保育・教育・通訳</option>
                <option value="7">クリエイティブ</option>
                <option value="8">WEB・インターネット・ゲーム</option>
                <option value="9">コンサルタント・⾦融・不動産専⾨職</option>
                <option value="10">公共サービス</option>
                <option value="11">ITエンジニア</option>
                <option value="12">電気・電⼦・機械・半導体</option>
                <option value="13">建築・⼟⽊</option>
                <option value="14">医薬・⾷品・化学・素材</option>
                <option value="15">技能⼯・設備・配送・農林⽔産</option>
                <option value="16">その他</option>
            </select>
            </br>
            <label for="shokushu_detail"><b>Q5. 職種(中分類)</b></label>
            <span>もっとも当てはまる職種を1つ選択してください。まず大分類から回答してください。</span>
            <select name="shokushu_detail" id="shokushu_detail" data-theme="c"
                    data-iconpos="left" data-native-menu="false">
                <option value="choose-one" data-placeholder="true">
                    職種(中分類)を選択してください
                </option>
            </select>
            </br>


            <label for="certificate"><b>Q6. 資格</b></label>
            <div>資格を持っている場合記入してください。</div>
            <textarea data-role="none" id="certicate" name="certificate"
                      placeholder="資格記入欄"></textarea></br>


            <fieldset data-role="controlgroup" data-theme="c"
                      id="doukyocheckbox">
                <legend><b>Q7. 同居人</b></legend>
                <span>同居人を全て選択してください。独居の場合は独居を選択してください。</span></br>
                <label for="checkbox-dokkyo">独居</label>
                <input type="checkbox" name="checkbox-dokkyo"
                       id="checkbox-dokkyo" value="独居">
                <label for="checkbox-partner">配偶者、パートナー</label>
                <input type="checkbox" name="checkbox-partner"
                       id="checkbox-partner" value="配偶者・パートナー">
                <label for="checkbox-child">子</label>
                <input type="checkbox" name="checkbox-child" id="checkbox-child"
                       value="子">
                <label for="checkbox-parents">親</label>
                <input type="checkbox" name="checkbox-parents"
                       id="checkbox-parents" value="親">
                <label for="checkbox-parentsinlaw">配偶者の親</label>
                <input type="checkbox" name="checkbox-parentsinlaw"
                       id="checkbox-parentsinlaw" value="配偶者の親">
                <label for="checkbox-grandchildren">孫</label>
                <input type="checkbox" name="checkbox-grandchildren"
                       id="checkbox-grandchildren" value="孫">
                <label for="checkbox-brotherssisters">兄弟姉妹</label>
                <input type="checkbox" name="checkbox-brotherssisters"
                       id="checkbox-brotherssisters" value="兄弟姉妹">
                <label for="checkbox-otherfamily">その他親族</label>
                <input type="checkbox" name="checkbox-otherfamily"
                       id="checkbox-otherfamily" value="その他親族">
                <label for="checkbox-notfamily">親族以外</label>
                <input type="checkbox" name="checkbox-notfamily"
                       id="checkbox-notfamily" value="親族以外">
                <label for="checkbox-pet">ペット</label>
                <input type="checkbox" name="checkbox-pet" id="checkbox-pet"
                       value="ペット">
            </fieldset>
            </br>


            <span><b>Q8. 運動習慣</b></span></br>
            <span>普段の運動の状況について当てはまるものを1つ選択してください。</span></br></br>
            <label for="undou_light">息が弾まない軽い運動(ストレッチ・軽い体操など)</label>
            <select name="undou_light" id="undou_light" data-theme="c"
                    data-iconpos="left" data-native-menu="false">
                <option value="choose-one" data-placeholder="true">
                    頻度を1つ選択してください
                </option>
                <option value="0">月に1日程度</option>
                <option value="1">週に1日程度</option>
                <option value="2">週に2〜3日</option>
                <option value="3">週に4〜5日</option>
                <option value="4">ほぼ毎日</option>
                <option value="5">運動していない</option>
            </select>
            </br>
            <label for="undou_medium">多少息がはずむ運動(ウォーキング・ジョギングなど)</label>
            <select name="undou_medium" id="undou_medium" data-theme="c"
                    data-iconpos="left" data-native-menu="false">
                <option value="choose-one" data-placeholder="true">
                    頻度を1つ選択してください
                </option>
                <option value="0">月に1日程度</option>
                <option value="1">週に1日程度</option>
                <option value="2">週に2〜3日</option>
                <option value="3">週に4〜5日</option>
                <option value="4">ほぼ毎日</option>
                <option value="5">運動していない</option>
            </select>
            </br>
            <label for="undou_heavy">激しく息がはずむ運動(エアロビクス・水泳など)</label>
            <select name="undou_heavy" id="undou_heavy" data-theme="c"
                    data-iconpos="left" data-native-menu="false">
                <option value="choose-one" data-placeholder="true">
                    頻度を1つ選択してください
                </option>
                <option value="0">月に1日程度</option>
                <option value="1">週に1日程度</option>
                <option value="2">週に2〜3日</option>
                <option value="3">週に4〜5日</option>
                <option value="4">ほぼ毎日</option>
                <option value="5">運動していない</option>
            </select>
            </br>


            <input type="button" value="登録する" data-theme="b" name="go"
                   onClick="answerdemographic();"/>
        </form>
    </div>

    <?php include("./common/commonFooter.php"); ?>
    <script type="text/javascript" src="js/questionnaire.js"></script>
</div>
</body>
</html>
