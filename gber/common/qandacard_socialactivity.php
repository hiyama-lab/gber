<?php
//質問リスト
$questionlist_social = array(
    'worktype_prune' => "植木剪定",
    'worktype_agriculture' => "農業",
    'worktype_cleaning' => "清掃",
    'worktype_housework' => "家事代行",
    'worktype_shopping' => "買い物代行",
    'worktype_repair' => "組立・修理",
    'worktype_caretaking' => "子守・介護",
    'worktype_teaching' => "趣味教室",
    'worktype_consulting' => "コンサルティング",
    'study_english' => "英語",
    'study_foreignlanguage' => "英語以外の外国語",
    'study_it' => "パソコンなど情報処理",
    'study_business' => "商業実務・ビジネス関係",
    'study_caretaking' => "介護関係",
    'study_housework' => "家政・家事",
    'study_liberalarts' => "人文・社会・自然科学",
    'study_art' => "芸術・文化",
    'volunteer_health' => "健康や医療サービスに関係した活動",
    'volunteer_elderly' => "高齢者を対象とした活動",
    'volunteer_disable' => "障害者を対象とした活動",
    'volunteer_children' => "子供を対象とした活動",
    'volunteer_sport' => "スポーツ・文化・芸術・学術に関係した活動",
    'volunteer_town' => "まちづくりのための活動",
    'volunteer_safety' => "安全な生活のための活動",
    'volunteer_nature' => "自然や環境を守るための活動",
    'volunteer_disaster' => "災害に関係した活動",
    'volunteer_international' => "国際協力に関係した活動",
    'hobby_musicalinstrument' => "楽器の演奏",
    'hobby_chorus' => "コーラス・声楽",
    'hobby_dance' => "舞踊・ダンス",
    'hobby_shodo' => "書道",
    'hobby_kado' => "華道",
    'hobby_sado' => "茶道",
    'hobby_wasai' => "和裁・洋裁",
    'hobby_knit' => "編み物・手芸",
    'hobby_cooking' => "料理・菓子作り",
    'hobby_gardening' => "園芸・ガーデニング",
    'hobby_diy' => "日曜大工",
    'hobby_painting' => "絵画・彫刻",
    'hobby_pottery' => "陶芸・工芸",
    'hobby_photo' => "写真撮影・プリント",
    'hobby_writing' => "詩・和歌・俳句・小説",
    'hobby_go' => "囲碁・将棋",
    'hobby_camp' => "キャンプ・釣り",
    'hobby_watchsport' => "スポーツ観覧",
    'hobby_watchperformance' => "演芸演劇鑑賞",
    'hobby_watchmovie' => "映画鑑賞",
    'hobby_listenmusic' => "音楽鑑賞",
    'hobby_reading' => "読書",
    'hobby_pachinko' => "ギャンブル(パチンコ,競馬など)",
    'hobby_karaoke' => "カラオケ",
    'hobby_game' => "ゲーム",
    'hobby_attraction' => "遊園地‧水族館‧動物園",
    'hobby_train' => "鉄道",
    'hobby_car' => "車",
    'trip_daytrip' => "日帰り旅行",
    'trip_domestic' => "国内旅行",
    'trip_international' => "海外旅行",
    'sport_baseball' => "野球",
    'sport_tabletennis' => "卓球",
    'sport_tennis' => "テニス",
    'sport_badminton' => "バドミントン",
    'sport_golf' => "ゴルフ",
    'sport_gateball' => "ゲートボール",
    'sport_bowling' => "ボウリング",
    'sport_fishing' => "釣り",
    'sport_swimming' => "水泳",
    'sport_skiing' => "スキー",
    'sport_climbing' => "登山ハイキング",
    'sport_cycling' => "サイクリング",
    'sport_jogging' => "ジョギング",
    'sport_walking' => "ウォーキング",
);
//ランダムにシャッフル
$keys = array_keys($qanda_social);
shuffle($keys);
foreach ($keys as $key) {
    $qanda_social_suffled[$key] = $qanda_social[$key];
}
//ここから未回答のものについて表示
echo "<div id=\"qandawrapper_social\">";
echo "<h2>以下のアクティビティに興味ありますか？</h2>";
echo "<p>全72問です。途中でやめても問題ありませんので、お手すきの際にお願いします。</p>";
echo "<div id=\"oyaouter_social\" class=\"your-class\">";
$slickarray = array();
$i = 0;
while (($eachqanda = current($qanda_social_suffled)) !== false) {
    if (key($qanda_social_suffled) == "userno"
        || key($qanda_social_suffled) == "socialactivityid"
        || key($qanda_social_suffled) == "answered"
    ) {
        next($qanda_social_suffled);
    } else {
        if ($eachqanda != 0) {
            next($qanda_social_suffled);
        } else {
            $slickarray[$i] = key($qanda_social_suffled);
            echo "<div class=\"eachslide\">"
                . $questionlist_social[key($qanda_social_suffled)] . "</div>";
            $i++;
            next($qanda_social_suffled);
        }
    }
}
echo "</div>";
echo "<div class=\"ui-grid-a\">";
echo "<div class=\"ui-block-a\"><div class=\"button-wrap\" style=\"padding: 10px;\"><input type=\"button\" value=\"興味あり！\" onclick=\"answerinterest_social("
    . $_SESSION['userno'] . ",1);\"/></div></div>";
echo "<div class=\"ui-block-b\"><div class=\"button-wrap\" style=\"padding: 10px;\"><input type=\"button\" value=\"興味なし\" data-theme=\"c\" onclick=\"answerinterest_social("
    . $_SESSION['userno'] . ",-1);\"/></div></div>";
echo "</div></div></br>";
if ($i == 0) {
    echo "<script>$(\"#qandawrapper_social\").hide();</script>";
}
?>

<script>
    $(document).ready(function () {
//  var oyawidth = $("#oyaouter_social").outerWidth()-105;
        $('.eachslide').css('max-width', 260);
        $('.your-class').slick({
            arrows: true,
            infinite: true,
            slidesToShow: 1,
            adaptiveHeight: true,
            centerMode: true,
            centerPadding: "40px",
            variableWidth: true,
            focusOnSelect: true,
        });
    });
    var allindex =<?php echo $i; ?>;
    var slickarray =<?php echo json_encode($slickarray); ?>;

    function answerinterest_social(userno, interest) {
        var answeredrow = slickarray[$('.your-class').slick('slickCurrentSlide')];
        var JSONdata = {
            answeredrow: answeredrow,
            userno: userno,
            interest: interest
        };
        $.ajax({
            type: 'POST',
            data: JSON.stringify(JSONdata),
            dataType: "jsonp",
            jsonp: 'jsoncallback',
            url: baseurl + 'model/answerSocialInterest.php',
            timeout: 10000,
            success: function (data) {
                //スライド一覧から除く
                slickarray.splice($('.your-class').slick('slickCurrentSlide'), 1);
                $('.your-class').slick('slickRemove', $('.your-class').slick('slickCurrentSlide'));
                allindex -= 1;
                if (allindex == 0) {
                    $("#qandawrapper_social").hide();
                }
            },
            error: function () {
                sweetAlert("エラー", "エラーです", "error");
            }
        });
    }
</script>