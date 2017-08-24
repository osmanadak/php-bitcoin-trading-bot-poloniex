<?php
include("inc/core.php");
if(!$us) { header("Location: index.php"); } ?>
<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BTC AUTOTRADER | Smart Coin Trader Robot</title>

        <!-- Vendor CSS -->
        <link href="css/fullcalendar.min.css" rel="stylesheet">
        <link href="css/animate.min.css" rel="stylesheet">
        <link href="css/sweet-alert.css" rel="stylesheet">
        <link href="css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" type="text/css">



        <!-- CSS -->
        <link href="css/app.min.1.css" rel="stylesheet">
        <link href="css/app.min.2.css" rel="stylesheet">

    </head>
    <body>
        <header id="header">
            <ul class="header-inner">
                <li id="menu-trigger" data-trigger="#sidebar">
                    <div class="line-wrap">
                        <div class="line top"></div>
                        <div class="line center"></div>
                        <div class="line bottom"></div>
                    </div>
                </li>

                <li class="logo hidden-xs">
                    <a href="/">
                        BTC AUTOTRADER
                    </a>
                </li>

                <li class="pull-right">
                <ul class="top-menu">
                    <li id="toggle-width">
                        <div class="toggle-switch">
                            <input id="tw-switch" type="checkbox" hidden="hidden">
                            <label for="tw-switch" class="ts-helper"></label>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="tm-notification" href=""></a>
                        <div class="dropdown-menu dropdown-menu-lg pull-right">
                            <div class="listview" id="notifications">
                                <div class="lv-header">
                                    Notification

                                    <ul class="actions">
                                        <li class="dropdown">
                                            <a href="" data-clear="notification">
                                                <i class="zmdi zmdi-check-all"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="lv-body">
                                    <?php
                                    $stmt = $dbh->prepare("select * from notifications order by id desc limit 8");
                                    $stmt->execute();
                                    while ($row = $stmt->fetch()) {
                                    ?>
                                    <a class="lv-item" href="javascript:;">
                                        <div class="media">
                                            <div class="media-body">
                                                <small class="lv-small"><?php echo $row['text'];?></small>
                                            </div>
                                        </div>
                                    </a>
                                    <?php } ?>
                                </div>

                                <a class="lv-footer" href="notifications.php">View All Notifications</a>
                            </div>

                        </div>
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="tm-settings" href=""></a>
                        <ul class="dropdown-menu dm-icon pull-right">
                            <li class="hidden-xs">
                                <a data-action="fullscreen" href=""><i class="zmdi zmdi-fullscreen"></i> Toggle Fullscreen</a>
                            </li>
                            <li>
                                <a data-action="clear-localstorage" href=""><i class="zmdi zmdi-delete"></i> Clear Local Storage</a>
                            </li>
                            <li>
                                <a href="logout.php" style="color: red;"><i class="zmdi zmdi-sign-in"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                </li>
            </ul>

            <!-- Top Search Content -->
            <div id="top-search-wrap">
                <input type="text">
                <i id="top-search-close">&times;</i>
            </div>
        </header>


            <aside id="sidebar">
                <div class="sidebar-inner c-overflow">
                    <div class="profile-menu">
                        <a href="">
                            <div class="profile-pic">
                                <img src="img/profile-pics/1.jpg" alt="">
                            </div>

                            <div class="profile-info">
                                Admin

                                <i class="zmdi zmdi-arrow-drop-down"></i>
                            </div>
                        </a>

                        <ul class="main-menu">
                            <li>
                                <a href="changepassword.php"><i class="zmdi zmdi-key"></i> Change Password</a>
                            </li>
                            <li>
                                <a href="apisettings.php"><i class="zmdi zmdi-settings"></i> Api Settings</a>
                            </li>
                            <li>
                                <a href="logout.php"><i class="zmdi zmdi-time-restore"></i> Logout</a>
                            </li>
                        </ul>
                    </div>

                    <ul class="main-menu">
                        <li class="active"><a href="/"><i class="zmdi zmdi-home"></i> Home</a></li>
                        <li class="sub-menu">
                            <a href=""><i class="zmdi zmdi-widgets"></i> Rules</a>

                            <ul>
                                <li><a href="newrule.php">New Rule</a></li>
                                <li><a class="active" href="rules.php">All Rules</a></li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href=""><i class="zmdi zmdi-trending-up"></i>Reports</a>
                            <ul>
                                <li><a href="notifications.php">Notifications</a></li>
                                <li><a href="openorders.php">Open Orders</a></li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href=""><i class="zmdi zmdi-collection-item"></i> Settings</a>
                            <ul>
                                <li><a href="apisettings.php">Api Settings</a></li>
                                <li><a href="changepassword.php">Change Password</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </aside>