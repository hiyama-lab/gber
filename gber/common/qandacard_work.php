<?php
if (count($qanda_work) > 0) {
    shuffle($qanda_work);
    echo "<div id=\"qandawrapper\">";
    echo "<h2>オススメの仕事/イベント</h2>";
    echo "<p>今後開催される仕事/イベントを表示しています</p>";
    echo "<div id=\"oyaouter\" class=\"your-class\">";
    $slickarray = array();
    $i = 0;
    foreach ($qanda_work as $eachqanda) {
        $slickarray[$i] = $eachqanda['id'];
        echo "<div class=\"eachslide\"><h4>" . $eachqanda['worktitle'] . "</h4>"
            . nl2br($eachqanda['summary']) . "</div>";
        $i++;
    }
    echo "</div>";
    echo "<div class=\"ui-grid-a\">";
    echo "<div class=\"ui-block-a\"><div class=\"button-wrap\" style=\"padding: 10px;\"><input type=\"button\" value=\"興味あり！\" onclick=\"answerinterest("
        . $_SESSION['userno'] . ",1);\"/></div></div>";
    echo "<div class=\"ui-block-b\"><div class=\"button-wrap\" style=\"padding: 10px;\"><input type=\"button\" value=\"興味なし\" data-theme=\"c\" onclick=\"answerinterest("
        . $_SESSION['userno'] . ",0);\"/></div></div>";
    echo "</div></div></br>";
}
?>

<script>
    $(document).ready(function () {
//  var oyawidth = $("#oyaouter").outerWidth()-105;
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
    var allindex =<?php echo count($qanda_work); ?>;
    var slickarray =<?php echo json_encode($slickarray); ?>;

    function answerinterest(userno, interest) {
        var workid = slickarray[$('.your-class').slick('slickCurrentSlide')];
        var JSONdata = {
            workid: workid,
            workerno: userno,
            interest: interest
        };
        $.ajax({
            type: 'POST',
            data: JSON.stringify(JSONdata),
            dataType: "jsonp",
            jsonp: 'jsoncallback',
            url: baseurl + 'model/answerInterest.php',
            timeout: 10000,
            success: function (data) {
                slickarray.splice($('.your-class').slick('slickCurrentSlide'), 1);
                $('.your-class').slick('slickRemove', $('.your-class').slick('slickCurrentSlide'));
                allindex -= 1;
                if (allindex == 0) {
                    $("#qandawrapper").hide();
                }
            },
            error: function () {
                sweetAlert("エラー", "エラーです", "error");
            }
        });
    }
</script>   