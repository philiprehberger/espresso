/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    jQuery.init_page();
});

jQuery.extend({
    init_page: function () {
        jQuery.build_products();
        jQuery.listeners();
        if (typeof localStorage.order === 'string') {
            jQuery.update_order();
        }
    },
    listeners: function () {
        console.log({function:'listeners'});
        $('.btn_shopping_cart').off().on({
            tap: function () {
                jQuery.load_cart({show_cart: true});
            }
        });
        $('.btn_products').off().on({
            tap: function () {
                 jQuery.core.navigate({page: '#products'});
            }
        });
        $('.btn_menu').off().on({
            tap: function () {
                console.info({tap: '.btn_menu'});
                // jQuery.build_products();
                jQuery.core.navigate({page: '#products'});
            }
        });

    },
    build_products: function () {
        var tabs = String('<div data-role="navbar"><ul id="ul_products"></ul></div>');
        $('#tabs_products').html(tabs);
        var by_id = {};
        $.each(window.products, function (i, v) {
            by_id[v.product_id] = v;
            //console.info({i: i, v: v});
            if ($('#ul_products li[data-category_id="' + v.category_id + '"]').length === 0) {
                $('#ul_products').append('<li data-category_id="' + v.category_id + '"><a href="#fragment-' + v.category_id + '">' + v.category + '</a></li>');
                $('#tabs_products').append('<div id="fragment-' + v.category_id + '"><table><tbody></tbody></table></div>');
            }
            var row = String('');
            row += '<tr>';
            row += '<td><img src="/assets/uploads/files/' + v.image + '" /></td>';
            row += '<td><p>' + v.name + '</p>' + v.description + '</td>';
            row += '<td>' + parseFloat(v.price).toFixed(2) + '</td>';
            row += '<td><button class="btn_add" data-product_id="' + v.product_id + '" >add</button></td>';
            row += '</tr>';
            $('#fragment-' + v.category_id + ' tbody').append(row);
        });
        jQuery.products = by_id;
        $('#tabs_products').tabs({
            active: 0,
            collapsible: true
        });
        $('li.ui-tabs-active.ui-state-active a').addClass('ui-btn-active');
        $('.btn_add').on({
            tap: function () {
                var data = {
                    product_id: parseInt($(this).data('product_id')),
                    qty: 1,
                    change: '+',
                };
                jQuery.update_order(data);
            }
        });
    },
    update_order: function (d) {
        console.log('update_order');
        console.info(d);
        var order = {};
        if (typeof d === 'object' || true) {
            if (typeof jQuery.order === 'object') {
                console.warn('jQuery.order found');
                order = jQuery.order;
            } else if (typeof localStorage.order === 'string') {
                console.warn('localStroage.order found');
                order = JSON.parse(localStorage.order);
            } else {
                console.warn('order not found');

                order = {
                    type: false,
                    created: new Date().getTime(),
                    updated: false,
                    customer: {
                        user_id: false,
                        first_name: false,
                        last_name: false,
                        username: false,
                        email: false,
                        phone: false,
                    },
                    items: {}
                };
            }
            order.updated = new Date().getTime();
            if (typeof d === 'object' && typeof d.qty === 'number' && typeof d.change === 'string' && typeof d.product_id === 'number') {

                if (typeof order.items !== 'object') {
                    order.items = {};
                }
                if (typeof order.items[d.product_id] !== 'object') {
                    order.items[d.product_id] = jQuery.products[d.product_id];
                    order.items[d.product_id].qty = 0;
                }
                if (d.change === '+') {
                    order.items[d.product_id].qty += d.qty;
                } else if (d.change === '-') {
                    order.items[d.product_id].qty -= d.qty;
                } else if (d.change === '=') {
                    order.items[d.product_id].qty = d.qty;
                }
                if (typeof order.items[d.product_id] === 'object' && order.items[d.product_id].qty === 0) {
                    order.items[d.product_id] = false;
                    $('')
                }
                //console.info(jQuery.order.items[1]);


            }
            localStorage.order = JSON.stringify(order);
            jQuery.order = order;
            jQuery.update_cart_icon();
            jQuery.load_cart();
            return true;
        }
        console.error({error: 'invalid input'});
    },
    update_cart_icon: function () {
        if (typeof jQuery.order === 'object' && typeof jQuery.order.items === 'object') {
            var qty = 0;
            $.each(jQuery.order.items, function (i, v) {
                console.info(v);
                if (typeof v === 'object' && typeof v.qty === 'number') {
                    qty += v.qty;
                }
            });
            if (qty === 0) {
                console.info('no order items');
            } else {
                console.info('order items: ' + qty);
            }
        }
    },
    load_cart: function (d) {
        $('#tbl_order tbody').html('');
        var subtotal = 0;
        $.each(jQuery.order.items, function (i, v) {
            if (typeof v === 'object' && typeof v.qty === 'number' && v.qty > 0) {
                var line_subtotal = v.qty * v.price;
                subtotal += line_subtotal;
                var row = String('');

                row += '<tr data-product_id="' + v.product_id + '">';
                row += '<td>' + v.name + '</td>';
                row += '<td>' + parseFloat(v.price).toFixed(2) + '</td>';
                row += '<td class="qty">';
                row += '<div class="qty_grp">';
                row += '<div class="qty_display">' + v.qty + '</div>';
                row += '<a class="btn_qty_plus"></a>';
                row += '<a class="btn_qty_minus"></a>';
                row += '</div>';
                row += '</td>';
                row += '<td>' + line_subtotal.toFixed(2) + '</td>';
                row += '</tr>';
                $('#tbl_order tbody').append(row);
            } else {
                console.info('no data for productID: ' + i);
            }
        });
        $('.subtotal').html('$' + parseFloat(subtotal).toFixed(2));
        $('.btn_qty_plus').button({
            icon: 'plus',
            iconpos: 'notext'
        }).parent().on({
            tap: function () {
                console.info('+1');
                var product_id = $(this).closest('tr').data('product_id');
                console.info(product_id);
                jQuery.update_order({product_id: product_id, change: '+', qty: 1});
            }
        });
        $('.btn_qty_minus').button({
            icon: 'minus',
            iconpos: 'notext'
        }).parent().on({
            tap: function () {
                console.info('-1');
                var product_id = $(this).closest('tr').data('product_id');
                console.info(product_id);
                jQuery.update_order({product_id: product_id, change: '-', qty: 1});
            }
        });
        if (typeof d === 'object' && typeof d.show_cart === 'boolean' && d.show_cart === true) {
            $(":mobile-pagecontainer").pagecontainer("change", '#cart');
        }
    },
});