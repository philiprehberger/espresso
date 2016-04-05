<?php
/**
 * @file       /application/views/main.php
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
<style>
    #tabs_products table{
        width:100%;
    }
    .ui-tabs-panel table img{
        max-width:200px;
        max-height:200px;
    }
    .ui-tabs-panel table tr td:first-child{
        width:200px;
        height:200px;
    }
    .ui-tabs-panel table tr td{
        border-bottom: 1px solid #eeeeee;
    }
    .btn_shopping_cart{

    }
    .btn_shopping_cart img{
        width:20px;
    }
    #tbl_order{
        width:100%;
    }
    #tbl_order thead td{
        font-weight:800;
        border-bottom:2px solid #2a6da2;
    }

    #tbl_order tbody td{
        border-bottom:1px solid #2a6da2;
    }

    #tbl_order tbody tr:last-child td{

        border-bottom:2px solid #2a6da2;
    }
    #tbl_order tfoot td{
        font-weight:800;
    }
    #tbl_order .qty_grp{
        display: flex;
        float: right;
    }
    #tbl_order .qty_grp .qty_display{
        margin-top: 9px;
        margin-right: 10px;
    }
    #tbl_order tr td:nth-child(2),
    #tbl_order tr td:nth-child(3),
    #tbl_order tr td:nth-child(4){
        width:100px;
        text-align: right;
    }
    #tbl_order .qty_grp .ui-btn:active{
        margin-top:8.5px;
        margin-bottom:4.5px;
    }

    #tbl_order .qty_grp .ui-btn{
        background-color: #3388cc !important;
    }
    .btn_products{
        margin-right:10px !important;
    }
    .btn_panel_left{
        border-radius: 0px !important;
        margin:0px !important;
    }

    .btn_panel_left{
       
        left: 0;

    
    }
    [data-role="header"].header_bar button{
         height: 44px;
        top: 0;
            border-radius:0 !important;
                    border-width: 0;
        width: 100px !important;
        border-radius:0 !important;
    }

    
    .btn_panel_right{
  
        right: 0;
        
    }
</style>
<!--PAGE: main-->
<div data-role="page" id="home">
    <div class='header_bar ui-shadow' data-role="header" data-position="fixed" >
        <button class="btn_panel_left"><img src="http://dcsuniverse.com/mobile/public/img/icons/menu-alt-64.png" alt=""/></button>

    </div>
    <div class="container_alerts">
        <div class="box_alerts ui-shadow">
            <div class="alert_message"></div>
        </div>
    </div>  
    <div data-role="content" role="main" class="ui-content content_main dialog_content">
        <button class="btn_menu">menu</button>

    </div>

</div>
<!-- PAGE: login -->
<div data-role="page" data-dialog="false" id="login" class="ui-content ui-page-dialog">
    <div class='header_bar ui-shadow' data-role="header" >
        <button class="btn_panel_left"><img src="http://dcsuniverse.com/mobile/public/img/icons/menu-alt-64.png" alt=""/></button>
        <h2 class="dialog_title"></h2>

    </div>
    <div class="container_alerts">
        <div class="box_alerts ui-shadow">
            <div class="alert_message"></div>
        </div>
    </div>  
    <div data-role="content" role="main" class="ui-content content_main dialog_content">
        <p></p>
        <div class="ui-field-contain">
            <label for="username">Username:</label>
            <input type="text" class="reset" name="username" id="username" value="">
        </div>
        <div class="ui-field-contain">
            <label for="password">Password:</label>
            <input type="password" class="reset" name="password" id="password" value="">
        </div>
    </div>
    <div  class="footer ui-shadow" data-role="footer" data-position="fixed" >
        <div data-role="controlgroup" data-type="horizontal" data-corners="true" data-mini="true">
            <button href="#home" data-rel="back" data-transition="slide" class="btn_close btn_footer"  id="btn_login_cancel">Cancel</button>
            <button href="#" id="btn_login" class="btn_footer">Login</button>
        </div>
    </div>
</div>
<!-- PAGE: signup -->
<div data-role="page" data-dialog="false" id="signup" class="ui-content ui-page-dialog">
    <div data-role="header" class='header_bar ui-shadow' data-position="fixed">
        <button class="btn_panel_left"><img src="http://dcsuniverse.com/mobile/public/img/icons/menu-alt-64.png" alt=""/></button>
        <h2 class="dialog_title">Sign Up</h2>
    </div>
    <div class="container_alerts">
        <div class="box_alerts ui-shadow">
            <div class="alert_message"></div>
        </div>
    </div>  
    <div data-role="content" role="main" class="ui-content content_main dialog_content">
        <p></p>
        <div class="ui-field-contain">
            <label for="first_name">First Name:</label>
            <input type="text" class="reset" name="first_name" id="first_name" value="">
            <p style="display:none;" >First Name required</p>
        </div>
        <div class="ui-field-contain">
            <label for="last_name">Last Name:</label>
            <input type="text" class="reset" name="last_name" id="last_name" value="">
            <p style="display:none;" >Last Name required</p>
        </div>
        <div class="ui-field-contain">
            <label for="email">Email:</label>
            <input type="email" class="reset" name="email" id="email" value="">
            <p style="display:none;" id="error_message_email">Email required</p>
        </div>
        <div class="ui-field-contain">
            <label for="years">Years Teaching:</label>
            <input type="number" class="reset" name="years" id="years" value="">
        </div>
    </div>
    <div  class="footer ui-shadow" data-role="footer" data-position="fixed" >
        <div data-role="controlgroup" data-type="horizontal" data-corners="true" data-mini="true">
            <button href="#home" data-rel="back" data-transition="slide" class="btn_close btn_footer" id="btn_signup_cancel">Cancel</button>
            <button id="btn_signup" class="btn_footer">Create</button>
        </div>
    </div>
</div>
<!-- PAGE: my_settings -->
<div data-role="page" data-dialog="false" id="my_settings" class="ui-content ui-page-dialog">
    <div data-role="header" class='header_bar ui-shadow' data-position="fixed">
        <button class="btn_panel_left"><img src="http://dcsuniverse.com/mobile/public/img/icons/menu-alt-64.png" alt=""/></button>
        <h2 class="dialog_title">My Settings</h2>
    </div>
    <div class="container_alerts">
        <div class="box_alerts ui-shadow">
            <div class="alert_message"></div>
        </div>
    </div>  
    <div data-role="content" role="main" class="ui-content content_main dialog_content">
        <p></p>
        <div class="ui-field-contain">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="user_first_name" value="<?php
            if (isset($_SESSION, $_SESSION['first_name']) === TRUE) {
                echo $_SESSION['first_name'];
                echo '" data-current="' . $_SESSION['first_name'];
            }
            ?>" />
        </div>
        <div class="ui-field-contain">
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="user_last_name" value="<?php
            if (isset($_SESSION, $_SESSION['last_name']) === TRUE) {
                echo $_SESSION['last_name'];
                echo '" data-current="' . $_SESSION['last_name'];
            }
            ?>" />
        </div>
        <div class="ui-field-contain">
            <label for="email">Email:</label>
            <input type="email" name="email" id="user_email" value="<?php
            if (isset($_SESSION, $_SESSION['email']) === TRUE) {
                echo $_SESSION['email'];
                echo '" data-current="' . $_SESSION['email'];
            }
            ?>" />
        </div>
    </div>
    <div  class="footer ui-shadow" data-role="footer" data-position="fixed" >
        <div data-role="controlgroup" data-type="horizontal" data-corners="true" data-mini="true">
            <button href="#home" data-rel="back" data-transition="slide" class="btn_close btn_footer" id="btn_my_settings_cancel"><img class="btn-img_close"  src="/public/img/main/back_128.png" /></button>
            <button class="btn_save btn_footer" id="btn_my_settings_save"><img src="/public/img/32/blue_save_1.png" /></button>
        </div>
    </div>
</div>


<!-- PAGE: products -->
<div data-role="page" data-dialog="false" id="products" class="ui-content ui-page-dialog">
    <div data-role="header" class='header_bar ui-shadow' data-position="fixed">
        <button class="btn_panel_left"><img src="http://dcsuniverse.com/mobile/public/img/icons/menu-alt-64.png" alt=""/></button>
        <h2 class="dialog_title">Menu</h2>

        <button class="btn_panel_right btn_shopping_cart">
            <span class="subtotal"></span>
            <img src="/public/img/cart_32.png" />
        </button>
    </div>
    <div class="container_alerts">
        <div class="box_alerts ui-shadow">
            <div class="alert_message"></div>
        </div>
    </div>  

    <div role="main" class="ui-content">
        <div data-role="tabs" id="tabs_products">

        </div>
    </div>

    
</div>

<!-- PAGE: cart -->
<div data-role="page" data-dialog="false" id="cart" class="ui-content ui-page-dialog">
    <div data-role="header" class='header_bar ui-shadow' data-position="fixed">
        <button class="btn_panel_left"><img src="http://dcsuniverse.com/mobile/public/img/icons/menu-alt-64.png" alt=""/></button>
        <h2 class="dialog_title">Cart</h2>
        <button class="btn_panel_right btn_products">
            <span class="ui-btn-text">Add More</span>

        </button>
    </div>
    <div class="container_alerts">
        <div class="box_alerts ui-shadow">
            <div class="alert_message"></div>
        </div>
    </div>  
    <div role="main" class="ui-content">
        <table id="tbl_order">
            <thead>
                <tr>
                    <td>Item</td>
                    <td>Rate</td>
                    <td>Qty</td>
                    <td>Subtotal</td>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <span class="subtotal"></span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
  
</div>