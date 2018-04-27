<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
include __DIR__ . '/../lib/sendEmail.php';

$userno = $_GET["userno"];

mysql_query("UPDATE db_user SET mail='', phone='', nickname='退会済', gender='未設定', birthyear='0', pass='', intro='', address_string='', mylat='0.0', mylng='0.0', master='0', certification='0' WHERE userno='"
    . $userno . "'", $con) or die('Error: ' . mysql_error());
mysql_query("DELETE FROM grouplist WHERE userno='" . $userno . "'", $con)
or die('Error: ' . mysql_error());
mysql_query("DELETE FROM caretakerlist WHERE giver='" . $userno . "'", $con)
or die('Error: ' . mysql_error());
mysql_query("DELETE FROM caretakerlist WHERE taker='" . $userno . "'", $con)
or die('Error: ' . mysql_error());

echo "削除しました";

mysql_close($con);

?>