<!DOCTYPE HTML>
<html>
    <head>
        <title>Espresso Stand</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css" />
        <link rel="stylesheet" href="/public/plugins/nativeDroid2/vendor/waves/waves.min.css" />
        <link rel="stylesheet" href="/public/plugins/nativeDroid2/vendor/wow/animate.css" />
        <link rel="stylesheet" href="/public/plugins/nativeDroid2/css/nativedroid2.css" />
        <style>
            /* Prevent FOUC */
            body { opacity: 0; }
            #login_container{
                max-width:500px;
                margin-left:auto;
                margin-right:auto;
            }
        </style>
    </head>
    <body class="clr-accent-lime">
        <div data-role="page" class="nd2-no-menu-swipe">
            <!-- panel left --> 
           <?php include '/var/www/html/espresso/public/fragments/panel_left.php'; ?>
            <!-- /panel left -->
            <div data-role="header" data-position="fixed" class="wow fadeIn">
                <a href="#leftpanel" class="ui-btn ui-btn-left"><i class="zmdi zmdi-menu"></i></a>
                <h1 class="wow fadeIn" data-wow-delay='0.4s'>Espresso Stand</h1>

            </div>
            <div role="main" class="ui-content wow fadeIn" data-inset="false" data-wow-delay="0.2s">
                <div id="login_container">
                    <label for="login_username">Username:</label>
                    <div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset ui-input-has-clear">
                        <input type="text" name="username" id="login_username" value="" data-clear-btn="true" placeholder="">
                    </div>
                    <label for="login_password">Password:</label>
                    <div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset ui-input-has-clear">
                        <input type="password" name="password" id="login_password" value="" data-clear-btn="true" placeholder="">
                    </div>
                    <button class="btn_post_login ui-btn ui-btn-inline"><i class="zmdi zmdi-mail-send"></i>Login</button>

                </div>
            </div>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
            <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
            <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
            <script src="/public/plugins/nativeDroid2/vendor/waves/waves.min.js"></script>
            <script src="/public/plugins/nativeDroid2/vendor/wow/wow.min.js"></script>
            <script src="/public/plugins/nativeDroid2/js/nativedroid2.js"></script>
            <script src="/public/plugins/nativeDroid2/nd2settings.js"></script>
            <script src="/public/js/md5.min.js"></script>
    </body>
</html>
<script>
    $('documnent').ready(function () {
        jQuery.init.listeners();
    });
    jQuery.extend({
        init: {
            listeners: function () {
                $('.btn_post_login').on({
                    tap: function () {
                        var data = {};
                        data.username = $('#login_username').val();
                        data.password = window.md5($('#login_password').val());
                        jQuery.post.login(data);
                    }
                });
            },
        },
        post: {
            login: function (inDATA) {
                console.log({function: 'post.login', inDATA: inDATA});

            $.ajax({
                url: "/auth/login",
                data: inDATA,
                type: 'POST',
                beforeSend: function (x) {
                    $('.ui-loader').loader('show');
                },
                complete: function () {
                    $('.ui-loader').loader('hide');
                },
                success: function (resp) {
                    if (typeof resp === 'object' && typeof resp.status === 'string' && resp.status === 'success') {
                        //window.location = '/';
                        //jQuery.core.navigate({page:'#home'});
                        window.location = 'http://espresso.dcsuniverse.com';
                    } else {
                        jQuery.core.alert(resp.message);
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        },
        },
    });



</script>
