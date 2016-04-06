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
            .item_rate{
                /* position: absolute;
                 right: 115px;
                 top: 20px;*/
            }
            .item_rate .x,
            .item_rate .qty{
                display:none;
            }
            .item_rate .x{
                padding-left: 10px;
                padding-right: 10px;
            }
            .btn_qty_add{
                margin-right:0 !important;

            }
            .order_item  {
                border-bottom:1px solid #DDDDDD;
                border-top:1px solid #DDDDDD;
            }
            .nd2Tabs-content-tab ul.ui-listview li:nth-child(2){
                border-top:1px solid #DDDDDD;
            }
            .btn_qty_minus{

                position: absolute;
                right: 0;
                top: 0;
                height: 100%;
                width:100px !important;
                width:100px !important;
                border-radius: 0 !important;
                background-color: rgba(178,34,34,.6) !important;
            }
            .on_order .btn_qty_minus {

            }
            .btn_qty_minus.ui-icon-minus:after {
                background-image: url("data:image/svg+xml;charset=US-ASCII,%3C%3Fxml%20version%3D%221.0%22%20encoding%3D%22iso-8859-1%22%3F%3E%3C!DOCTYPE%20svg%20PUBLIC%20%22-%2F%2FW3C%2F%2FDTD%20SVG%201.1%2F%2FEN%22%20%22http%3A%2F%2Fwww.w3.org%2FGraphics%2FSVG%2F1.1%2FDTD%2Fsvg11.dtd%22%3E%3Csvg%20version%3D%221.1%22%20id%3D%22Layer_1%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20xmlns%3Axlink%3D%22http%3A%2F%2Fwww.w3.org%2F1999%2Fxlink%22%20x%3D%220px%22%20y%3D%220px%22%20%20width%3D%2214px%22%20height%3D%2214px%22%20viewBox%3D%220%200%2014%2014%22%20style%3D%22enable-background%3Anew%200%200%2014%2014%3B%22%20xml%3Aspace%3D%22preserve%22%3E%3Crect%20y%3D%225%22%20style%3D%22fill%3A%23FFFFFF%3B%22%20width%3D%2214%22%20height%3D%224%22%2F%3E%3C%2Fsvg%3E") !important;
            }
            .btn_qty_add{
                margin-right:0;
            }
            .btn_cart{
                position: absolute;
                right: 25px;
            }
            #tbl_order{
                width:100%;   
            }
            #tbl_order tfoot td:nth-child(4),
            #tbl_order tbody td:nth-child(2),
            #tbl_order tbody td:nth-child(3),
            #tbl_order tbody td:nth-child(4){
                text-align: center;
            }
            #tbl_order tfoot td:nth-child(4){
                font-weight: 800;
            }
            #tbl_order thead th:nth-child(1){
                text-align: left;
            }
            .qty_grp{
                display:flex;
            }
            #title_subtotal{
                position: absolute;
                right: -40px;
                top: 50px;
            }
            .product_description{
                margin-right:100px !important;
                white-space: initial !important;
            }
            .grp_btns_inline{
                display:flex;
            }
            .grp_btns_inline .ui-input-text:after,
            .grp_btns_inline .ui-input-text,
            .grp_btns_inline .ui-input-text input{
                width:100px !important;
            }
            .grp_btns_inline label{
                width:75px;
                margin-right:10px;
                text-align: center;
            }
            .btn_size_minus,
            .btn_size_plus{
                border-radius: 0 !important;
                width:45px;
                height:45px;
                border:solid 1px #FFFFFF !important;
                    
    padding-top: 0px;
    padding-bottom: 0px;
    
    color: #FFFFFF !important;
                
            }
            .btn_size_minus {
                background-color: rgba(178,34,34,.6) !important;
                font-size: 38px;
                padding-left: 15.5px;
    padding-right: 15.5px;
    line-height: 1px;
            }
            .btn_size_plus {
                font-size: 36px;
                padding-left: 11.5px;
    padding-right: 11.5px;
                    background-color: rgba(0, 148, 133,.6) !important;
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
                <h1 class="wow fadeIn" data-wow-delay='0.4s'>
                    Espresso Stand
                    <button class="btn_cart">cart(<span class="cart_qty">0</span>)</button>
                </h1>
                <h3 id="title_subtotal" class='subtotal'></h3>
                <ul data-role="nd2tabs" data-swipe="true">
                    <?php
                    if (isset($products) === TRUE) {
                        echo '<script>var products=' . json_encode($products) . ';</script>';
                        foreach ($products AS $k => $v) {
                            echo '<li data-tab="category_id_' . $v['category_id'] . '">' . $k . '</li>';
                        }
                    }
                    ?>
                    <li id="btn_tab_cart" style="display:none;" data-tab="page_cart">cart</li>
                </ul>
            </div>
            <div role="main" class="ui-content wow fadeIn" data-inset="false" data-wow-delay="0.2s">
                <?php
                if (isset($products) === TRUE) {
                    $products_by_id = array();
                    foreach ($products AS $k => $v) {

                        echo '<div data-role="nd2tab" data-tab="category_id_' . $v['category_id'] . '">';
                        echo '<ul data-role="listview" data-icon="false">';
                        echo '<li data-role="list-divider">' . $k . '</li>';
                        foreach ($v['items'] AS $item) {
                            $products_by_id[$item['product_id']] = $item;
                            echo '<li id="product_id_' . $item['product_id'] . '" class="order_item" data-product_id="' . $item['product_id'] . '" >';
                            echo '<a class="btn_qty_add" href="#">';
                            echo '<img src="/assets/uploads/files/' . $item['image'] . '" class="ui-thumbnail ui-thumbnail-circular" />';
                            echo '<h2>' . $item['name'] . '</h2>';
                            echo '<p class="product_description">' . $item['description'] . '</p>';
                            if (is_array($item['sizes']) === FALSE || count($item['sizes']) === 0) {
                                echo '<table class="item_rate">';
                                echo '<tr>';
                                echo '<td class="qty"></td>';
                                echo '<td class="x">x</td>';
                                echo '<td class="rate">$' . number_format($item['price'], 2) . '</td>';
                                echo '</tr>';
                                echo '</table>';
                            }

                            echo '</a>';
                            echo '<a style="display:none;" class="btn_qty_minus ui-btn ui-btn-icon-notext ui-icon-minus waves-effect waves-button" title="remove 1"></a>';
                            echo '</li>';
                            if (is_array($item['sizes']) === TRUE && count($item['sizes']) > 0) {
                                echo '<li id="product_sizes_' . $item['product_id'] . '" style="display:none;" class="product_size_options" data-product_id="' . $item['product_id'] . '" >';
                                foreach ($item['sizes'] AS $sizeDATA) {
                                    echo '<div class="grp_btns_inline ">';
                                    echo '<label>';
                                    echo $sizeDATA['name'];
                                    echo '<br>';
                                    echo '$' . number_format($sizeDATA['price'], 2);
                                    echo '</label>';
                                    echo '<input id="product_sizes_' . $item['product_id'] . '" />';
                                    echo '<button class="btn_size_minus ui-btn ui-btn-inline waves-effect waves-button waves-effect waves-button" data-product_id="' . $item['product_id'] . '">-</button>';
                                    echo '<button class="btn_size_plus ui-btn ui-btn-inline waves-effect waves-button waves-effect waves-button">+</button>';
                                    echo '</div>';
                                }
                            }
                            echo '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                }
                ?>
                <div data-role="nd2tab" data-tab="page_cart">
                    <table id="tbl_order" class="ui-responsive table-stroke">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Rate</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
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
                    <button id="btn_submit_order">Submit Order</button>
                </div>
            </div>


        </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
        <script src="/public/plugins/nativeDroid2/vendor/waves/waves.min.js"></script>
        <script src="/public/plugins/nativeDroid2/vendor/wow/wow.min.js"></script>
        <script src="/public/plugins/nativeDroid2/js/nativedroid2.js"></script>
        <script src="/public/plugins/nativeDroid2/nd2settings.js"></script>
        <script src="/public/js/home.js"></script>

        <script>
<?php
if (isset($products_by_id) === TRUE) {
    echo 'jQuery.products=' . json_encode($products_by_id) . ';';
}
?>

        </script>
    </body>
</html>
