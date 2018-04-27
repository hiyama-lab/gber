<?php
$server = $_ENV["MYSQL_SERVER"];
$username = $_ENV["MYSQL_USER"];
$password = $_ENV["MYSQL_PASSWORD"];
$database = $_ENV["MYSQL_DATABASE"];

$protocol = ($_SERVER["HTTPS"] == "on" || $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") ? "https" : "http";
$baseurl = "$protocol://" . $_SERVER["HTTP_HOST"] . "/";
$con = mysql_connect($server, $username, $password)
or die ("Could not connect: " . mysql_error());
mysql_select_db($database, $con);
mysql_query('SET NAMES utf8', $con);
$groupnameresult = mysql_query("SELECT groupno, groupname FROM groupnamelist")
or die ("Query error: " . mysql_error());
$groupnamerecords = array();
while ($groupnamerow = mysql_fetch_assoc($groupnameresult)) {
    $groupnamerecords[] = $groupnamerow;
}
$groupnamelist = array();
$groupcounter = 0;
$groupstr = "";
foreach ($groupnamerecords as $eachrecord) {
    $groupnamelist[$eachrecord['groupno']] = $eachrecord['groupname'];
    if ($groupcounter == 0) {
        $groupstr = $groupstr . "\"" . $eachrecord['groupname'] . "\"";
    } else {
        $groupstr = $groupstr . ",\"" . $eachrecord['groupname'] . "\"";
    }
    $groupcounter = $groupcounter + 1;
}
?>
