<?php
$_SESSION = array();
if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 604800, '/');
}
if (isset($_COOKIE["userno"])) {
    setcookie("userno", '', time() - 604800, '/');
}
session_destroy();
?>