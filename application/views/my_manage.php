<?php
/**
 * @file       /application/views/my_manage.php
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
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DCS Universe</title>
        <?php foreach ($css_files as $file): ?>
            <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
        <?php endforeach; ?>
        <?php foreach ($js_files as $file): ?>
            <script src="<?php echo $file; ?>"></script>
        <?php endforeach; ?>
        <?php
        if (isset($scriptDATA, $scriptDATA['cssDATA'])) {
            foreach ($scriptDATA['cssDATA'] as $file) {
                echo '    <link href="' . $baseURL . $file . '" rel="stylesheet" type="text/css"/>' . "\n\r";
            }
        }
        if (isset($scriptDATA, $scriptDATA['jsDATA'])) {
            foreach ($scriptDATA['jsDATA'] as $file) {
                echo '    <script src="' . $baseURL . $file . '" type="text/javascript"></script>' . "\n\r";
            }
        }
        
        ?>
            <style>
                td[align="left"][width="20%"],
                th[align="left"][width="20%"]{
                    min-width:75px !important;
                    max-width:75px !important;
                    width:3% !important;
                }
                
                #search_field,
                #per_page,
                #crud_page,
                #search_text{
                    border-radius:4px;
                }
                .sDiv2{
                        display: -webkit-box !important;
                }
                .bDiv table {
font-family:monospace;
}
            </style>
    </head>
    <body>
        <div style='height:20px;'>
            <a href="/">Home</a>
        </div>  
        <div class="container_gcrud">
            <?php echo $output; ?>
        </div>
    </body>
    <script>
        $('document').ready(function () {
            jQuery.manage.init_page();
        });
        jQuery.extend({
            manage: {
                init_page: function () {
                    var pages = {
                        '/my/plans': {
                            title: 'My Plans'
                        },
                        '/my/reviews': {
                            title: 'My Reviews'
                        },
                        '/my/ratings': {
                            title: 'My Ratings'
                        },
                        '/my/settings': {
                            title: 'My Settings'
                       }
                    };
                    var path = window.location.pathname;
                    if (typeof pages[path] === 'object') {
                        var page = pages[path];
                        $('.ftitle').html(page.title);
                        $('title').html('Uniting Teachers | ' + page.title);
                    }

                }
            }
        });




    </script>
</html>
