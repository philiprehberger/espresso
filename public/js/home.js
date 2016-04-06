/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$('document').ready(function () {
    jQuery.init.page();
});

jQuery.extend({
    core: {
        navigate: function (inDATA) {
            console.log({'core.navigate': inDATA});
            $(":mobile-pagecontainer").pagecontainer("change", inDATA.page);
        },
        reset: function (selector) {
            console.log({function: 'core.reset'});
            $.each($(selector + ' .reset'), function (i, v) {
                console.warn(v);
            });
        },
        get_geolocation: function () {

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(jQuery.core.set_geodata);
            } else {
                jQuery.core.set_geodata({coords: {latitude: false, longitude: false}});
            }

        },
        set_geodata: function (position) {
            console.info({latitude: position.coords.latitude, longitude: position.coords.longitude});
        }
    },
    init: {
        page: function () {
            console.log({function: 'init.page'});
            jQuery.init.listeners();
            $('title').html('Espresso Stand');

        },
        listeners: function () {
            console.log({function: 'init.listeners'});
            $('.btn_logout').off('tap').on({
                tap: function () {
                    jQuery.post.logout();
                }
            });
            $('.btn_qty_add').off('tap').on({
                tap: function () {
                    var id = $(this).closest('li').attr('id');
                    var data = $('#' + id).data();
                    if(typeof data.product_id !== 'undefined'){
                        var elem = '#product_sizes_' + data.product_id;
                        if($(elem).css('display') === 'none'){
                            $(elem).slideDown();
                        }else{
                            $(elem).slideUp();
                        }
                        
                    
                    }
                    
                    
                    if (typeof data.qty === 'undefined') {
                        data.qty = 0;
                    }
                    var new_qty = parseInt(data.qty + 1);
                    $('#' + id).data('qty', new_qty);
                    $('#' + id + ' .item_rate .qty').html(new_qty).show();
                    $('#' + id + ' .item_rate .x').show();
                    $('#' + id).addClass('on_order');
                    $('#' + id + ' .btn_qty_minus').show();
                    jQuery.update_order({product_id: data.product_id, change: '+', qty: 1});
                    console.warn(data);
                }
            });
            $('.btn_qty_minus').off('tap').on({
                tap: function () {
                    var id = $(this).closest('li').attr('id');
                    var data = $('#' + id).data();
                    if (typeof data.qty === 'undefined') {
                        data.qty = 0;
                    }
                    var new_qty = parseInt(data.qty - 1);
                    $('#' + id).data('qty', new_qty);
                    $('#' + id + ' .item_rate .qty').html(new_qty)
                    if (new_qty < 1) {
                        $('#' + id + ' .item_rate .x,#' + id + ' .item_rate .qty').hide();
                        $('#' + id).removeClass('on_order');
                        $('#' + id + ' .btn_qty_minus').hide();
                    }
                    jQuery.update_order({product_id: data.product_id, change: '-', qty: 1});
                    console.warn(data);
                }
            });
            $('.btn_signup').off('tap').on({
                tap: function () {
                    console.info({tap: '.btn_signup'});

                }
            });
            $('.btn_login').off('tap').on({
                tap: function () {
                    console.info({tap: '.btn_login'});
                    window.location = '/login';
                }
            });

            $('.btn_cart').off('tap').on({
                tap: function () {
                    $('#btn_tab_cart').click();
                }
            });
            $('#btn_submit_order').off('tap').on({
                tap: function () {
                   jQuery.post.order(jQuery.order);
                   
                }
            });
            $('.btn_manage_all').off().on({
                tap: function () {
                    console.info('btn_manage_all tap');
                   window.location = 'http://espresso.dcsuniverse.com/manage'
                }
            });
        },
    },
    post: {
        logout: function () {
            console.log({function: 'post.logout'});
            $.ajax({
                url: '/auth/logout',
                type: 'POST',
                data: {},
                beforeSend: function (x) {
                    $('.ui-loader').loader('show');
                },
                complete: function () {
                    $('.ui-loader').loader('hide');
                },
                success: function (resp) {
                    console.info(resp);
                    jQuery.core.reset('#leftpanel');
                    $('#leftpanel .remove').remove();
                    $('.btn_login,.btn_signup').show();
                },
                error: function (error) {
                    console.error(error);
                }
            });
        },
        settings: function (inDATA) {
            console.log({function: 'post.settings', inDATA: inDATA});
            //TODO: verify inDATA
            $.ajax({
                url: jQuery.core.baseURL + '/ajax/post_settings',
                type: 'POST',
                data: inDATA,
                beforeSend: function (x) {
                    $('.ui-loader').loader('show');
                },
                complete: function () {
                    $('.ui-loader').loader('hide');
                },
                success: function (resp) {
                    console.info(resp);
                    if (typeof resp === 'object' && typeof resp.status === 'string' && resp.status === 'success') {
                        jQuery.core.navigate({page: 'back'});
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        },
        signup: function (inDATA) {
            console.log({function: 'post.signup'});
            /*var data = jQuery.get.signup();
             if (data === false) {
             return false;
             }*/
            $.ajax({
                type: 'POST',
                url: '/ajax/post_signup',
                data: inDATA,
                beforeSend: function (x) {
                    $('.ui-loader').loader('show');
                },
                complete: function () {
                    $('.ui-loader').loader('hide');
                },
                success: function (resp) {
                    console.info(resp);
                    if (typeof resp === 'object' && typeof resp.status === 'string' && typeof resp.message === 'string') {
                        jQuery.core.alert({text: resp.message, type: resp.status});
                        if (resp.status === 'success') {
                            // window.location = '/?newAccount=success';
                        }
                        if (typeof resp.errors === 'object') {
                            $.each(resp.errors, function (i, v) {
                                jQuery.core.alert({text: v.error, type: 'error'});
                            });
                        }
                    } else {
                        jQuery.core.alert({text: resp.message, type: 'error'});
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        },
        login: function () {
            console.log({function: 'post.login'});
            var data = {};
            data["username"] = $("#username").val();
            data["password"] = window.md5($("#password").val());
            $.ajax({
                url: "/auth/login",
                data: data,
                type: 'POST',
                beforeSend: function (x) {
                    $('.ui-loader').loader('show');
                },
                complete: function () {
                    $('.ui-loader').loader('hide');
                },
                success: function (resp) {
                    if (typeof resp === 'object' && typeof resp.status === 'string' && resp.status === 'success') {
                        window.location.reload();
                        //jQuery.core.navigate({page:'#home'});
                    } else {
                        jQuery.core.alert(resp.message);
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        },
        order:function(inDATA){
          console.log({order:inDATA});  
          $.ajax({
                url: "/ajax/post_order",
                data: data,
                type: 'POST',
                beforeSend: function (x) {
                    $('.ui-loader').loader('show');
                },
                complete: function () {
                    $('.ui-loader').loader('hide');
                },
                success: function (resp) {
                    console.info(resp);
                    if (typeof resp === 'object' && typeof resp.status === 'string' && resp.status === 'success') {

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

            if (typeof d === 'object' && typeof d.qty === 'number' && typeof d.change === 'string') {//&& typeof d.product_id === 'number') {
                console.warn(typeof d.product_id);
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

                }
                console.warn(order.items[d.product_id].qty);
                //console.info(jQuery.order.items[1]);
                var qty = 0;
                if (typeof order.items[d.product_id].qty !== 'undefined') {
                    qty = order.items[d.product_id].qty;

                }
                if (qty === 0) {
                    $('#product_id_' + d.product_id + ' .btn_qty_minus').hide();
                    $('#product_id_' + d.product_id + ' .item_rate .x').hide();
                    $('#product_id_' + d.product_id + ' .item_rate .qty').hide();
                }
                // $('#product_id_' + d.product_id + ' .item_rate .qty').html(order.items[d.product_id].qty);
                if(parseInt(qty) !== parseInt($('#product_id_' + d.product_id + ' .item_rate .qty').html())){
                    $('#product_id_' + d.product_id + ' .item_rate .qty').html(qty);
                }
            }
            //localStorage.order = JSON.stringify(order);
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
            $('.cart_qty').html(qty);
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
                row += v.qty
                //row += '<div class="qty_grp">';
                //row += '<div class="qty_display">' + v.qty + '</div>';
                //row += '<button class="btn_cart_qty_plus ui-responsive ui-btn-inline"></button>';
                //row += '<button class="btn_cart_qty_minus ui-btn-inline"></button>';
                //row += '</div>';
                row += '</td>';
                row += '<td>' + line_subtotal.toFixed(2) + '</td>';
                row += '</tr>';
                $('#tbl_order tbody').append(row);
            } else {
                console.info('no data for productID: ' + i);
            }
        });
        $('.subtotal').html('$' + parseFloat(subtotal).toFixed(2));
        $('.btn_cart_qty_plus').button({
            icon: 'plus',
            iconpos: 'notext'
        }).parent().off().on({
            tap: function () {
                console.info('+1');
                var product_id = $(this).closest('tr').data('product_id');
                console.info(product_id);


                jQuery.update_order({product_id: product_id, change: '+', qty: 1});
            }
        });
        $('.btn_cart_qty_minus').button({
            icon: 'minus',
            iconpos: 'notext'
        }).parent().off().on({
            tap: function () {
                console.info('-1');
                var product_id = $(this).closest('tr').data('product_id');
                console.info(product_id);
                jQuery.update_order({product_id: product_id, change: '-', qty: 1});
            }
        });
        if (typeof d === 'object' && typeof d.show_cart === 'boolean' && d.show_cart === true) {
            //$(":mobile-pagecontainer").pagecontainer("change", '#cart');
        }
    },
});
