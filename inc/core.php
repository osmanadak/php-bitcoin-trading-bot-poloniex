<?php
include('settings.php');

/* User session check */
if(isset($_SESSION['userid'])){
    $stmt = $dbh->prepare('SELECT * FROM users WHERE id = "'.$_SESSION['userid'].'"');
    $stmt->execute();
    $us = $stmt->fetch();
}else{

    if($_COOKIE['REMEMBER_ME']) {
        $cookie = $_COOKIE['REMEMBER_ME'];
        $stmt = $dbh->prepare("SELECT * FROM remember WHERE rand_key='$cookie'");
        $stmt->execute();
        $ch = $stmt->fetch();
        if($ch) {
            $key = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 255);
            $stmt = $dbh->prepare("UPDATE remember SET rand_key='$key' WHERE rand_key='$key'");
            $stmt->execute();
            setcookie("REMEMBER_ME", $key, time() + (86400 * 60), "/");  //Set cookie for 2 months
            $_SESSION['userid'] = $ch['user_id'];
            echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
        }

    }

}
/* User session check */

$ip = $_SERVER['REMOTE_ADDR'];
$date = date("Y-m-d H:i:s");

/* Geo location api */
$geo_url = "http://freegeoip.net/json/".$ip."";
/* Geo location api */

/* Settings fetch */
$st = mysql_fetch_array(mysql_query("SELECT * FROM settings WHERE id='1'"));
/* Settings fetch */

/* Page check */
$currentFile = $_SERVER["PHP_SELF"];
$parts = Explode('/', $currentFile);
/* Page check */

