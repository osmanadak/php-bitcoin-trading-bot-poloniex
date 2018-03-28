<?php
include 'inc/core.php';
if($us) { echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>"; exit(); }
if(isset($_POST)) {
    if($_POST['username']!='' && $_POST['password']!='') {
        $password = md5($_POST['password']);
        $stmt = $dbh->prepare('SELECT * FROM users WHERE username = "'.$_POST['username'].'" AND password = "'.$password.'"');
        $stmt->execute();
        $number_of_rows = $stmt->rowCount();
        if($number_of_rows == 1) {
            $us = $stmt->fetch();

            /* Remember me */
            if($_POST['remember']) {
                $key = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 255);
                /* Delete existing keys */
                $stmt = $dbh->prepare("DELETE FROM remember WHERE user_id='$us[id]'");
                $stmt->execute();
                /* Delete existing keys */
                $stmt = $dbh->prepare("INSERT INTO remember (user_id, rand_key) VALUES('$us[id]', '$key')")or die(mysql_error());
                $stmt->execute();
                setcookie("REMEMBER_ME", $key, time() + (86400 * 60), "/");  //Set cookie for 2 months
            }
            /* Remember me */

            $_SESSION['userid'] = $us['id'];
            echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
            exit();
        }else{
            $msg = "Wrong username or password!";
        }
    }
}
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BTC AUTOTRADER | Smart Coin Trader Robot</title>

    <!-- Vendor CSS -->
    <link href="css/animate.min.css" rel="stylesheet">
    <link href="css/material-design-iconic-font.min.css" rel="stylesheet">

    <!-- CSS -->
    <link href="css/app.min.1.css" rel="stylesheet">
    <link href="css/app.min.2.css" rel="stylesheet">
</head>

<body class="login-content">
<!-- Login -->
<div class="lc-block toggled" id="l-login">
    <form method="POST">
        <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
            <div class="fg-line">
                <input type="text" name="username" class="form-control" placeholder="Username">
            </div>
        </div>

        <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
            <div class="fg-line">
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="checkbox">
            <label>
                <input type="checkbox" value="" name="remember">
                <i class="input-helper"></i>
                Keep me signed in
            </label>
        </div>

        <button type="submit" class="btn btn-login btn-danger btn-float"><i class="zmdi zmdi-arrow-forward"></i></button>
    </form>

</div>



<!-- Javascript Libraries -->
<script src="lib/jquery.min.js"></script>
<script src="lib/bootstrap.min.js"></script>

<script src="lib/waves.min.js"></script>

<script src="js/functions.js"></script>

</body>
</html>
