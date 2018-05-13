<header data-role="header" data-position="fixed">
    <div class="ui-btn-left">
        <a href="index.php" rel="external"
           class="ui-btn ui-icon-home ui-btn-icon-notext ui-nodisc-icon">ホーム</a>
        <a href="hoge.dummy" data-rel="back"
           class="ui-btn ui-icon-back ui-btn-icon-notext ui-nodisc-icon">前へ</a>
        <!--a href="help.php" rel="external" class="ui-btn ui-icon-info ui-btn-icon-notext ui-nodisc-icon">ヘルプ</a-->
    </div>
    <h1>
        <a href="index.php" rel="external">
          <div id="header-logo" <?php if ($_ENV["IS_DEMO"] === 'true') { ?>class="demo"<?php } ?>></div>
        </a>
    </h1>
    <div class="ui-btn-right">
        <!--a href="groupbbs.php" rel="external" class="ui-btn ui-icon-comment ui-btn-icon-notext ui-nodisc-icon">グループ</a-->
        <a href="schedule.php" rel="external"
           class="ui-btn ui-icon-calendar ui-btn-icon-notext ui-nodisc-icon">グループ</a>
        <a href="mypage.php?userno=<?php echo $_SESSION['userno']; ?>"
           rel="external"
           class="ui-btn ui-icon-user ui-btn-icon-notext ui-nodisc-icon">マイページ</a>
    </div>
</header>
