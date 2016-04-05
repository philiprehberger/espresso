<?php
/**
 * @file       /application/views/my_ratings.php
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
 * @date       Mar 4, 2016
 * 
 */
?>

<div data-role="page" data-dialog="false" id="my_unrated" class="ui-content ui-page-dialog">
            <div data-role="header" class='header_bar ui-shadow' data-position="fixed">
                <button class="btn_panel_left"><img src="http://dcsuniverse.com/mobile/public/img/icons/menu-alt-64.png" alt=""/></button>
                <h2 class="dialog_title">Rate Downloads</h2>
                <button class="btn_panel_right" href="#"><img src="/public/img/logo_unitingteachers.com.png" alt=""/></button>
            </div>
            <div role="main" class="ui-content dialog_content">
                <div >
                    <table class="default ratings" style="width:100%;margin-bottom:40px;">
                        <thead>
                            <tr>
                                <td  style="text-align: center;" colspan="3">Not Rated</td>
                            </tr>
                            <tr>
                                <td class="td_downloaded_on">Downloaded</td>
                                <td class="td_title">Title</td>
                                <td class="td_rating">Rating</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($todoDATA, $todoDATA['data']) === TRUE) {
                                foreach ($todoDATA['data'] AS $row) {
                                    if (isset($row['downloaded_on']) === TRUE && $row['rated'] === 0) {
                                        //echo '<tr  data-row="' . json_encode($row) . '">';
                                        echo '<tr>';
                                        echo '<td class="td_downloaded_on">' . $row['downloaded_on'] . '</td>';
                                        echo '<td class="td_title">' . $row['title'] . '</td>';
                                        echo '<td class="td_rating">' . '<input class="item_rating" value="' . $row['rating'] . '" data-item_id="' . $row['item_id'] . '" type="range" max="5" min="0"/>' . '</td>';
                                        echo '</tr>';
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <table class="default ratings" style="width:100%;margin-bottom:40px;">
                        <thead>
                            <tr>
                                <td  style="text-align: center;" colspan="3">Rated</td>
                            </tr>
                            <tr>
                                <td class="td_downloaded_on">Downloaded</td>
                                <td class="td_title">Title</td>
                                <td class="td_rating">Rating</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($todoDATA, $todoDATA['data']) === TRUE) {
                                foreach ($todoDATA['data'] AS $row) {
                                    if (isset($row['downloaded_on']) === TRUE && $row['rated'] === 1) {
                                        //echo '<tr  data-row="' . json_encode($row) . '">';
                                        echo '<tr>';
                                        echo '<td class="td_downloaded_on">' . $row['downloaded_on'] . '</td>';
                                        echo '<td class="td_title">' . $row['title'] . '</td>';
                                        echo '<td class="td_rating">' . '<input class="item_rating" value="' . $row['rating'] . '" data-item_id="' . $row['item_id'] . '" type="range" max="5" min="0"/>' . '</td>';
                                        echo '</tr>';
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div  class="footer ui-shadow" data-role="footer" data-position="fixed" >
                <div class="btn_grp">
                    <button href="#home" data-rel="back" data-transition="slide" class="btn_close"  id="btn_page_my_unrated_close">Close</button>
                </div>
            </div>
        </div>
<script>
    $('document').ready(function(){
        //jQuery.login.init();
    });
    jQuery.extend({
       core:{
         side_menu:function(){
             $('.btn_home').off('tap').on({
                tap: function (e) {
                    e.preventDefault();
                    // window.location = jQuery.core.baseURL;
                }
            }).css({
                cursor: 'pointer'
            }).attr({
                title: 'Home'
            });
            $('a.home').off('tap').on({
                tap: function () {
                    // window.location = 'http://' + window.location.host;
                }
            });
            $('a.btn_panel_right').off('tap').on({
                tap: function () {
                    $("#panel_right").panel('toggle');
                }
            });
            $('.btn_panel_left').off('tap').on({
                tap: function () {
                    $("#panel_left").panel("toggle");
                }
            });
            $('#panel_left li').off('tap').on({
                tap: function () {
                    var data = $(this).data();
                    if (typeof data.page === 'string') {
                        jQuery.load.page({page: data.page});
                    } else if (typeof data.content === 'string') {
                        jQuery.load.page({page: data.content});
                    } else if (typeof data.post === 'string' && ['logout'].indexOf(data.post) !== -1) {
                        if (data.post === 'logout') {
                            jQuery.post.logout();
                        }
                    } else {
                        jQuery.core.search(data);
                    }
                }
            });
            $('.btn_grp a').button();
            $('.btn_close').off('tap').on({
                tap: function (e) {
                    //e.preventDefault();
                    //console.info(e);
                    jQuery.core.navigate({page: 'back'});
                }
            });
            $('#btn_login').off('tap').on({
                tap: function (e) {
                    e.preventDefault();
                    jQuery.post.login();
                }
            });
            $('#btn_signup').off('tap').on({
                tap: function () {
                    jQuery.post.signup();
                }
            });
            $('#btn_add_plan_files').off('tap').on({
                tap: function () {
                    console.info({'tap': '#btn_add_plan_files'});
                }
            });
            $('#email').off('tap').on({
                change: function () {
                    jQuery.get.email_status();
                }
            });
         }  
       },
        login:{
            init:function(){
                
            }
        }
    });
</script>