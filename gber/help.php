<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
</head>
<body>
<div data-role="page">
    <?php
    $userno = $_SESSION['userno'];
    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content" class="help-center">
        <div id="toplogo" margin="0 auto">
            <img src="./img/logo1.svg"/></br>
            <h3>GBERは地域活動サポートプラットフォームです</h3>
        </div>

        <!-- マニュアルを画像で表示 -->
        <div id="topmanual">
            <h2>操作マニュアル内「1.GBERとは」抜粋</h2>
            <img src="img/GBER_Manual.001.jpeg" width="80%"/>
            <img src="img/GBER_Manual.002.jpeg" width="80%"/>
            <img src="img/GBER_Manual.003.jpeg" width="80%"/>
            <h2><a href="GBER_Manual.pdf"
                   rel="external">操作マニュアル全編(PDF)をダウンロード</a></h2>
        </div>

    </div><!-- END OF TABS -->

    <?php
    if ($_GET['register'] === "true") {
        echo "<div id=\"registerbutton\"><h2 class=\"chuo-yose\">早速始めてみましょう！</br>⬇︎</h2><button class=\"ui-btn\" onclick=\"location.href='registeruser.php'\">新規登録</button></div>";
    }
    ?>
</div><!-- END OF CONTENT -->


<?php include("./common/commonFooter.php"); ?>
<script>
    setTimeout(function () {
        $("#navbar li:nth-child(1) a").click();
    }, 100);
</script>
</div>
</body>
</html>
