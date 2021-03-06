<?php
require_once __DIR__ . '/lib/auth.php';
require_logined_session();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
    <!--カレンダー用-->
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css"/>
    <script type="text/javascript" src="js/calendar/jquery-ui.min.js"></script>
    <script type="text/javascript"
            src="js/calendar/jquery-ui-i18n.min.js"></script>
    <!-- 地図スタイル -->
    <script src="js/googlemap_style.json"></script>
</head>
<body>
<div data-role="page">
    <?php
    include __DIR__ . '/lib/mysql_credentials.php';

    if (!authorize($_SESSION['userno'], ROLE['GLOBAL_MASTER'], ['isapi' => false])){
        echo "閲覧権限がありません";
        exit;
    }

    // 住所一覧を取得
    $result
        = mysql_query("SELECT mylat,mylng FROM db_user WHERE mylat<>\"\" and mylng<>\"\"")
    or die ("Query error: " . mysql_error());
    $records = array();
    while ($row = mysql_fetch_assoc($result)) {
        $records[] = $row;
    }

    mysql_close($con);
    ?>
    <script>
        var latlng = <?php echo json_encode($records,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    </script>
    <!-- 全体募集の仕事を地図UIで探すためのファイル -->

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <div id="search-map"></div>
    </div>

    <?php include("./common/commonFooter.php"); ?>
    <?php include("./common/googleMapApi.php"); ?>
    <script src="js/oms.min.js"></script>
    <script type="text/javascript" src="js/residenceviewer.js"></script>
</div>
</body>
</html>
