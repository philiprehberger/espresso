<?php
/**
 * @file       /application/views/manage.php
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
<html>
    <head>
        <title>Manage</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php foreach ($css_files as $file): ?>
            <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
        <?php endforeach; ?>
        <?php foreach ($js_files as $file): ?>
            <script src="<?php echo $file; ?>"></script>
        <?php endforeach; ?>
        <style type='text/css'>
            body{
                font-family: Arial;
                font-size: 14px;
            }
            a {
                color: blue;
                text-decoration: none;
                font-size: 14px;
            }
            a:hover{
                text-decoration: underline;
            }
            #gcrud_container{
                display:none;
            }
            .ptogtitle{
                display:none;
            }
            .ftitle{
                height: 20px;
                font-size: 16pt;
                padding-top: 6px !important;
                text-align: center;
                font-weight: 300;
                cursor:pointer;
            }
        </style>
    </head>
    <body >
        <button class="btn_home">home</button>
        <div style='height:20px;'></div>  
        <div id="gcrud_container">
            <?php echo $output; ?>
        </div>
    </body>
</html>
<script>
    $('document').ready(function () {
        set_table_titles();
        $('.ptogtitle').click();
        $('.ptogtitle').on({
            click: function (e) {

                var container = $(this).closest('.flexigrid');
                var data = $(container).data();

                if (typeof data.active === 'boolean' && data.active === true) {
                    console.info(data.active);
                    $('.flexigrid.inactive').show();
                    $(container).data({active: false});
                } else {
                    $(container).data({active: true});
                    $('.flexigrid').addClass('inactive');
                    $(container).removeClass('inactive');
                    $('.flexigrid.inactive').hide();
                }


                $('.ptogtitle').addClass('inactive');
                $(this).removeClass('inactive');

                console.info(data);

            }
        });
        $('.mDiv').on({
            click: function (e) {

                if (e.target.className === 'ftitle') {
                    $(this).find('.ptogtitle').click();

                }

            }
        });
        $('#gcrud_container').slideDown(500);
        $('.btn_home').on({
           click:function(){
               window.location = 'http://espresso.dcsuniverse.com';
           } 
        });
    });

    function set_table_titles() {
        $.each($('.fbutton span.add'), function (i, v) {
            //console.info({i: i, v: v});
            var title = $(v).html().replace('Add ', '');
            var target = $(v).parent().parent().parent().parent().parent().parent().parent();
            target = $(target).children()[1];
            target = $(target).children()[0];
            $(target).html(title);
            //console.info(target);
        });
    }
</script>