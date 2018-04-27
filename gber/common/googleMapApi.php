<?php
echo "<script ";
if (isset($googleMap_callback)) {
    echo "async defer ";
}
echo "src=\"https://maps.googleapis.com/maps/api/js?key=";
echo $_ENV["GOOGLE_MAP_APIKEY"];
if (isset($googleMap_callback)) {
    echo "&callback=" . $googleMap_callback;
}
echo "\"></script>";
?>
