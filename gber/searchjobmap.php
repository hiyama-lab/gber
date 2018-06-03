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

    $activitylog
        = mysql_query("INSERT INTO activity_logs (userno, queryname, datetime) VALUES ('"
        . $_SESSION['userno'] . "', 'searchjobmap.php', '" . date('Y-m-d G:i:s')
        . "')", $con) or die('Error: ' . mysql_error());

    // 全体募集のある日(今日以降)を一覧で取得する
    $demosql = $_ENV["IS_DEMO"] === 'true' ? "" : "WHERE workdate > DATE_SUB(CURRENT_DATE(),interval 1 day)";
    $result = mysql_query("SELECT DISTINCT workdate FROM helpdate $demosql ORDER BY workdate LIMIT 100") or die ("Query error: " . mysql_error());
    $records = array();
    while ($row = mysql_fetch_assoc($result)) {
        $records[] = $row['workdate'];
    }

    mysql_close($con);

    ?>

    <!-- 全体募集の仕事を地図UIで探すためのファイル -->

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <form id="search-form">
            <div style="display: none;">
                <input type="text" id="userno" name="userno"
                       value="<?php echo $_SESSION['userno'] ?>"
                       readonly="readonly" required/></br>
            </div>
            <input data-role="date" type="text" name="workdate" id="workdate"
                   placeholder="日付を選択してください" required/>
            <script>
                // カレンダー
                var holidays = [<?php $firstflag = 0; foreach (
                    $records as $eachrecord
                ) {
                    if ($firstflag == 0) {
                        echo "'" . $eachrecord . "'";
                        $firstflag = 1;
                    } else {
                        echo ",'" . $eachrecord . "'";
                    }
                }?>];
                $(function () {
                    $.datepicker.setDefaults($.datepicker.regional['ja']);
                    $('#workdate').datepicker({
                        dateFormat: 'yy-mm-dd',
                        beforeShowDay: function (date) {
                            for (var i = 0; i < holidays.length; i++) {
                                var holiday = new Date();
                                holiday.setTime(Date.parse(holidays[i]));   // 仕事日を日付型に変換
                                if (holiday.getYear() == date.getYear() &&  // 仕事日の判定
                                    holiday.getMonth() == date.getMonth() &&
                                    holiday.getDate() == date.getDate()) {
                                    return [true, 'class-holiday', '仕事あり'];
                                }
                            }
                            return [true, 'class-weekday', '仕事なし'];
                        },
                        onSelect: function () {
                            loadData();
                        }
                    });
                });
            </script>
        </form>
        <div id="search-map"></div>
    </div>

    <?php include("./common/commonFooter.php"); ?>
    <?php $googleMap_callback = "initMap";
    include("./common/googleMapApi.php"); ?>
    <script src="js/oms.min.js"></script>
    <script>var isDemo= <?php echo $_ENV["IS_DEMO"] ?>;</script>
    <script type="text/javascript" src="js/searchjobmap.js"></script>
</div>
</body>
</html>
