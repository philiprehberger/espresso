<?php
/**
 * @file       /application/views/header.php
 * 
 * @project    unitingteachers.com 
 * @category   Uniting Teachers
 * @package    m_lessonplans
 * @link       http://dcsuniverse.com
 * @link       http://unitingteachers.com
 * @author     Philip Rehberger <me@philiprehberger.com>
 * @copyright  ©2016 DCS Universe
 * @version    2.0.2.0
 * @since      1.0.0.0
 * @date       Oct 12, 2014
 * 
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="apple-touch-icon" sizes="57x57" href="/public/img/black_cup_57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/public/img/black_cup_60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/public/img/black_cup_72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/public/img/black_cup_76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/public/img/black_cup_114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/public/img/black_cup_120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/public/img/black_cup_144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/public/img/black_cup_152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/public/img/black_cup_180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/public/img/black_cup_192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/public/img/black_cup_32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/public/img/black_cup_96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/public/img/black_cup_16.png">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/public/img/black_cup_144.png">
        <meta name="theme-color" content="#ffffff">
        <title>Espresso</title>
        <?php
        if (isset($scriptDATA, $scriptDATA['cssDATA'])) {
            foreach ($scriptDATA['cssDATA'] as $file) {
                echo '    <link href="' . $baseURL . $file . '" rel="stylesheet" type="text/css"/>' . "\n\r";
                //echo '<script>console.warn({"CSS LOADED":"' . $file . '"});</script>';
            }
        }
        if (isset($scriptDATA, $scriptDATA['jsDATA'])) {
            foreach ($scriptDATA['jsDATA'] as $file) {
                echo '    <script src="' . $baseURL . $file . '" type="text/javascript"></script>' . "\n\r";
                //echo '<script>console.warn({"JS LOADED":"' . $file . '"});</script>';
            }
        }
        ?>
        <!--<link href="/public/plugins/jQuery-Mobile-Icon-Pack/dist/jqm-icon-pack-fa-builder.css" rel="stylesheet" type="text/css"/>
        <link href="/public/plugins/jQuery-Mobile-Icon-Pack/dist/jqm-icon-pack-fa.css" rel="stylesheet" type="text/css"/>
        -->
        <link href="/public/fonts/icons/flaticon.css" rel="stylesheet" type="text/css"/>
        <link href="/public/plugins/rateyo/jquery.rateyo.min.css" rel="stylesheet" type="text/css"/>
        <script src="/public/plugins/rateyo/jquery.rateyo.min.js" type="text/javascript"></script>
        <link href='https://fonts.googleapis.com/css?family=Exo' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <!--PANEL: left-->
        <div data-role="panel" id="panel_left">
            <div id="panel_user">
                <ul id="ul_user"  data-role="listview" data-inset="true" data-divider-theme="a" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" >
                    <?php
                    if (isset($_SESSION, $_SESSION['user_id']) === TRUE) {
                        echo '<li data-role="list-divider" role="heading" class="ui-li-divider ui-bar-a ui-first-child">' . $_SESSION['username'] . '</li>';
                        echo '<li  data-page="my_settings" class="option ui-li-static ui-body-inherit">My Settings  </li>';
                        echo '<li data-post="logout" class="option ui-li-static ui-body-inherit">Logout </li>';
                    } else {
                        echo '<li data-role="list-divider" role="heading" class="ui-li-divider ui-bar-a ui-first-child">Guest</li>';
                        echo '<li data-page="login" class="option option_login ui-li-static ui-body-inherit">Login</li>';
                        echo '<li data-page="signup" class="option option_signup ui-li-static ui-body-inherit">Sign Up</li>';
                    }
                    ?>
                </ul>
                <?php
                if (isset($_SESSION, $_SESSION['user_type_id']) === TRUE && $_SESSION['user_type_id'] === 1) {
                    echo '<ul id="ul_admin"  data-role="listview" data-inset="true" data-divider-theme="a" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" >';
                    echo '<li data-role="list-divider" role="heading" class="ui-li-divider ui-bar-a ui-first-child">Admin</li>';
                    echo '<li data-page="manage" class="option option_manage ui-li-static ui-body-inherit">Manage</li>';
                    
                    echo '</ul>';
                }
                ?>

            </div>
            <div id="container_recent">

            </div>
        </div>
