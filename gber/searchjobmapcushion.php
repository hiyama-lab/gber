<?php
require_once __DIR__ . '/lib/sessionUtil.php';
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
        . $_SESSION['userno'] . "', 'searchjobmapcushion.php', '"
        . date('Y-m-d G:i:s') . "')", $con) or die('Error: ' . mysql_error());

    // グループメンバーかどうか調べる
    $groupmembersql = mysql_query("SELECT groupno FROM grouplist WHERE userno='"
        . $_SESSION['userno'] . "' and groupno > 0") or die ("Query error: "
        . mysql_error());
    $groupmemberflag = false;
    if (mysql_num_rows($groupmembersql) > 0) {
        $groupmemberflag = true;
    }

    mysql_close($con);

    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">

        <div>
            <h2>仕事/イベントの応募方法</h2>
            </br>
            <p>下のボタンを押すと、仕事/イベントが検索できます。</p>
            <p>開くと地図上に赤いピンが表示されます。</br>その一つ一つが仕事/イベント情報です。</p>
            <p>ピンをクリックすると、タイトルと、「詳細を見る」ボタンが表示されます。</br>
                「詳細を見る」ボタンを押すと、詳しい情報が見られます</p>
            <p>また上のバーをクリックすることで、日付からピンを絞り込むこともできます。</p>
            <p>地域の仕事/イベント探しにご活用ください。</p>
            </br>

            <?php if ($groupmemberflag) {
                echo "<p><h4>※ グループメンバーの方へ</h4>こちらはグループ活動の仕事画面とは異なります。</p></br>";
            } ?>
        </div>

        <a href="searchjobmap.php" rel="external" style="text-decoration:none;">
            <button>仕事/イベントの検索に進む</button>
        </a>


    </div>

    <?php include("./common/commonFooter.php"); ?>
</div>
</body>
</html>
