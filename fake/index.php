<?php
include ("flag.php");
function GetIP() {
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) $cip = $_SERVER["HTTP_CLIENT_IP"];
    else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if (!empty($_SERVER["REMOTE_ADDR"])) $cip = $_SERVER["REMOTE_ADDR"];
    else $cip = "0.0.0.0";
    return $cip;
}
$GetIPs = GetIP();
if ($GetIPs === "1.1.1.1") {
    if ($_SERVER['HTTP_REFERER'] === "www.doyouhaveagirlfriend.com") {
        echo $flag;
    } else {
        echo "Only Referer www.doyouhaveagirlfriend.com<br>";
    }
} else {
    echo "Only IP 1.1.1.1<br>";
}
?>
