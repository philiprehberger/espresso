/**
 * footer.js
 * 
 * @category   Uniting Teachers <unitingteachers.com>
 * @package    m_lessonplans
 * @author     Philip Rehberger <me@philiprehberger.com>
 * @copyright  2016 DCS Universe
 * @version    2.0.1.0
 * @link       http://dcsuniverse.com
 * @link       http://unitingteachers.com
 * @since      2.0.1.0
 */

var CKEDITOR = window.CKEDITOR;
var parseFloat = window.parseFloat;
jQuery(document).ready(function ($) {
    jQuery.init.page();
});
jQuery.extend({
    switch : function (inDATA) {
        console.log({function: 'switch', inDATA: inDATA});
        if (typeof inDATA === 'object' && typeof inDATA.page === 'string' && typeof jQuery.pages[inDATA.page] === 'object') {
           $('#panel_left').panel('close');
            var data = jQuery.pages[inDATA.page];
            $.each(inDATA, function (i, v) {
                data[i] = v;
            });
            if(typeof data.reset === 'boolean' && data.reset === true){
                jQuery.core.reset(inDATA);
            }
            if (typeof data.get === 'string' && typeof jQuery.get[data.get] === 'function') {
                jQuery.get[data.get](data);
            } else if (typeof data.load === 'string' && typeof jQuery.load[data.load] === 'function') {
                jQuery.load[data.load](data);
            } else if (typeof data.navigate === 'string') {
                if(typeof data.page_change === 'boolean' && data.page_change === true){
                    window.location = data.navigate;
                }else{
                    jQuery.core.navigate({page:  data.navigate});
                }

            }
            return true;
        }
        return false;
    },
    pages: {
        my_settings: {
            get: false,
            load: false,
            navigate: '#my_settings'
        },
        login: {
            get: false,
            load: false,
            navigate: '#login'
        },
        signup: {
            get: false,
            load: false,
            navigate: '#signup'
        },
        manage: {
            get: false,
            load: false,
            navigate: '/manage/',
            page_change: true
        },
    },
    core: {
        ready: false,
        baseURL: 'http://' + window.location.hostname,
        play_sound: function (name) {
            console.log({function: 'core.play_sound', name: name});
            var mp3s = {
                'alert': '/public/sounds/sounds-913-served.mp3',
                'error': '/public/sounds/sounds-913-served.mp3',
                'success': '/public/sounds/322930__rhodesmas__success-03.wav',
                'info': '/public/sounds/322930__rhodesmas__success-03.wav'
            };
            if (typeof mp3s[name] !== 'undefined') {
                var audio = new Audio(mp3s[name]);
                audio.play();
            } else {
                console.error('unable to play sound');
            }
        },
        alert: function (inDATA) {
            console.log({function: 'core.alert', inDATA: inDATA});
            if (typeof inDATA === 'object') {
                $('.box_alerts').slideUp();
                $('.box_alerts').removeClass('ui-error');
                var sound = 'alert';
                var timeout = 10000;
                if (typeof inDATA.type === 'string') {
                    sound = inDATA.type;
                    if (inDATA.type === 'error') {
                        $('.box_alerts').addClass('ui-error');
                    }
                }
                if (typeof inDATA.timeout === 'number') {
                    timeout = inDATA.timeout;
                }
                $('.alert_message').html(inDATA.text);
                jQuery.core.play_sound(sound);
                $('.box_alerts').off('click').on({
                    click: function () {
                        $('.box_alerts').slideUp();
                    }
                }).css({
                    'z-index': 99999999
                }).slideDown();
                setTimeout(function () {
                    $('.box_alerts').slideUp();
                }, timeout);
                return true;
            }
            return false;
            /*
             
             $.noty.defaults = {
             layout: 'centerRight',
             theme: 'relax', // or 'relax'
             type: 'warning',
             // text: '', // can be html or string
             dismissQueue: true, // If you want to use queue feature set this true
             template: '<div class="noty_message ' + inDATA.type + '"><span class="noty_text"></span><div class="noty_close"></div></div>',
             animation: {
             open: {height: 'toggle'}, // or Animate.css class names like: 'animated bounceInLeft'
             close: {height: 'toggle'}, // or Animate.css class names like: 'animated bounceOutLeft'
             easing: 'swing',
             speed: 500 // opening & closing animation speed
             },
             timeout: false, // delay for closing event. Set false for sticky notifications
             force: false, // adds notification to the beginning of queue when set to true
             modal: false,
             maxVisible: 5, // you can set max visible notification for dismissQueue true option,
             killer: true, // for close all notifications before show
             closeWith: ['click'], // ['click', 'button', 'hover', 'backdrop'] // backdrop click will close all notifications
             callback: {
             onShow: function () {
             jQuery.core.play_sound(sound);
             },
             afterShow: function () {
             },
             onClose: function () {
             },
             afterClose: function () {
             },
             onCloseClick: function () {
             }
             },
             buttons: false // an array of buttons
             };
             var n = noty(inDATA);*/
        },
        download: function (file_id) {
            console.log({function: 'core.download', file_id: file_id});
            window.location = '/download?file_id=' + file_id;
        },
        search: function (inDATA) {
            console.log({function: 'core.search', inDATA: inDATA});
            if (
                    typeof inDATA !== 'object' ||
                    (
                            (typeof inDATA.search !== 'string' || inDATA.search.length < 1) &&
                            typeof inDATA.item_id !== 'number' &&
                            typeof inDATA.tag_id !== 'number' &&
                            typeof inDATA.category_id !== 'number'
                            )
                    ) {
                return false;
            }
            if (window.location.pathname !== '/' && typeof inDATA.item_id !== 'number') {
                console.error('wrong page');
                var url = 'http://' + window.location.hostname + '/';
                $.each(['search', 'item_id', 'tag_id', 'category_id'], function (i, v) {
                    if (typeof inDATA[v] === 'string' || typeof inDATA[v] === 'number') {
                        var d = '&';
                        if (url.indexOf('?') === -1) {
                            d = '?';
                        }
                        url += d + v + '=' + inDATA[v];
                    }
                });
                //window.location = url;
            }
            var text = '';
            if (typeof inDATA.tag_id === 'number') {
                if (typeof window.planDATA === 'object' && typeof window.planDATA.tags === 'object' && typeof window.planDATA.tags[inDATA.category_id] === 'object' && typeof window.planDATA.tags[inDATA.category_id].tag === 'string') {
                    text += 'tag:' + window.planDATA.tags[inDATA.tag_id].tag + ' ';
                } else {
                    text += 'tag_id:' + inDATA.tag_id + ' ';
                }
            }
            if (typeof inDATA.category_id === 'number') {
                if (typeof window.planDATA === 'object' && typeof window.planDATA.categories === 'object' && typeof window.planDATA.categories[inDATA.category_id] === 'object' && typeof window.planDATA.categories[inDATA.category_id].category === 'string') {
                    text += 'category:' + window.planDATA.categories[inDATA.category_id].category + ' ';
                } else {
                    text += 'category_id:' + inDATA.category_id + ' ';
                }
            }
            if (typeof inDATA.item_id === 'number') {
                text += 'item_id:' + inDATA.item_id + ' ';
            }
            if (typeof inDATA.search === 'string') {
                text += 'search:' + inDATA.search + ' ';
            }
            text = text.trim();
            $('#current_search').val(text);
            $('#logo_home').hide();
            if (typeof inDATA === 'object') {
                var data = {};
                var path = 'search';
                if (typeof inDATA.search === 'string') {
                    if (inDATA.search.indexOf('item_id:') !== -1) {
                        var item_id_start = inDATA.search.indexOf('item_id:') + 8;
                        var item_id_end = inDATA.search.length;
                        if (inDATA.search.indexOf(' ') !== -1) {
                            item_id_end = inDATA.search.indexOf(' ');
                        }
                        var item_id = parseInt(inDATA.search.substring(item_id_start, item_id_end));
                        inDATA.search = inDATA.search.replace('item_id:' + item_id, '');
                        inDATA.item_id = item_id;
                    }
                    data.search = inDATA.search;
                }
                if (typeof inDATA.category_id === 'number') {
                    data.category_id = inDATA.category_id;
                }
                if (typeof inDATA.tag_id === 'number') {
                    data.tag_id = inDATA.tag_id;
                }
                if (typeof inDATA.item_id === 'number') {
                    data.item_id = inDATA.item_id;
                    path = 'get_item_details';
                }
                $.ajax({
                    type: 'POST',
                    url: jQuery.core.baseURL + '/ajax/' + path,
                    data: data,
                    beforeSend: function (x) {
                        $('.ui-loader').loader('show');
                    },
                    complete: function () {
                        $('.ui-loader').loader('hide');
                    },
                    success: function (resp) {
                        //console.info({type: 'info', data: {function: 'success', resp: resp}});
                        if (typeof resp === 'object' && typeof resp.result === 'object' && typeof resp.status === 'string' && resp.status === 'success') {
                            if (path === 'search') {
                                $('#ul_results,#search_result_summary').html('');
                                jQuery.load.results(resp.result);
                                var summary = String(resp.qty + ' Results found.');
                                $('#search_result_summary').html(summary);
                                jQuery.core.navigate({page: '#home'});
                            } else if (path === 'get_item_details') {
                                jQuery.load.item_details(resp.result);
                            }
                            $('#panel_left').panel('close');
                        }
                    },
                    error: function (error) {
                        console.error({type: 'error', data: error});
                    }
                });
            } else {
                console.error({type: 'error', data: 'invalid search'});
            }
        },
        update_history: function (inDATA) {
            console.log({function: 'core.update_history', inDATA: inDATA});
            if (typeof inDATA === 'object' && typeof inDATA.path === 'string' && typeof inDATA.title === 'string') {
                var stateObj = {foo: "bar"};
                //history.replaceState(stateObj, inDATA.title, inDATA.path);
            }
            return false;
        },
        is_mobile: function () {
            console.log({function: 'is_mobile'});
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                return true;
            }
            return false;
        },
        object_size: function (obj) {
            var size = 0, key;
            for (key in obj) {
                if (obj.hasOwnProperty(key))
                    size++;
            }
            return size;
        },
        history: {0: {path: '/', hash: '#home'}, 1: {path: window.location.pathname, hash: window.location.hash}},
        history_current: 1,
        navigate: function (inDATA) {
            if (typeof inDATA === 'object' && typeof inDATA.page === 'string') {
                var page = inDATA.page;
                console.warn(page);
                if (inDATA.page === 'back') {
                    console.info({navigation: 'back'});
                    window.history.back();
                    return false;
                } else if (inDATA.page === 'forward') {
                    console.info({navigation: 'forward'});
                    window.history.forward();
                    return false;
                }

                console.info({navigation: inDATA.page});
                $(":mobile-pagecontainer").pagecontainer("change", inDATA.page);
            }


        },
        done_loading_files: function (inDATA) {
            console.log({function: 'done_loading_files', inDATA: inDATA});
        },
        reset: function (inDATA) {
            console.log({function: 'core.reset', inDATA: inDATA});
            var elem_start = '';
            if (typeof inDATA === 'object' && typeof inDATA.page === 'string') {
                elem_start += '#' + inDATA.page + ' ';
            }
            var resets = [
                {type: 'html', elem: elem_start + 'div.reset'},
                {type: 'val', elem: elem_start + 'textarea.reset'}
            ];
            console.info(resets);
            $('#overlay_bottom,#overlay_top').remove();
            $.each(resets, function (i, v) {
                var elem = $(v.elem);
                if (v.type === 'html') {
                    elem.html('');
                } else if (v.type === 'val') {
                    elem.val('');
                }
            });
        }
    },
    init: {
        db: function () {
            console.log({function: 'init.db'});
            var db = openDatabase('db', '1.0', 'lesson plan db', 2 * 1024 * 1024);
            //TABLE: tags
            db.transaction(function (tx) {
                tx.executeSql('CREATE TABLE IF NOT EXISTS tags (tag_id int unique, tag)');
            });
            if (typeof window.planDATA === 'object' && typeof window.planDATA.tags === 'object') {
                $.each(window.planDATA.tags, function (i, v) {
                    db.transaction(function (tx) {
                        tx.executeSql('INSERT INTO tags (tag_id, tag) VALUES ("' + v.tag_id + '", "' + v.tag + '")');
                    });
                });
            }
            //TABLE: categories
            db.transaction(function (tx) {
                tx.executeSql('CREATE TABLE IF NOT EXISTS categories (category_id int unique , category)');
            });
            if (typeof window.planDATA === 'object' && typeof window.planDATA.categories === 'object') {
                $.each(window.planDATA.categories, function (i, v) {
                    db.transaction(function (tx) {
                        tx.executeSql('INSERT INTO categories (category_id, category) VALUES ("' + v.category_id + '", "' + v.category + '")');
                    });
                });
            }
        },
        title: function () {
            console.log({function: 'init.title'});
            var opts = {
                'espresso': 'espresso',
                'dcsuniverse.com': 'DCS Universe',
                
            };
            $.each(opts, function (i, v) {
                if (window.location.host.indexOf(i) !== -1) {
                    $('title').html(v);
                    jQuery.core.baseURL = 'http://' + i;
                    return false;
                }
            });
        },
        page: function () {
            console.log({function: 'init.page', href: window.location.href});
            if (window.location.hash !== '') {
                console.log({info: "window.location !== '/'"});
                if (window.location.hash === '#my_unrated') {
                    jQuery.get.downloaded_items({load: 'downloads_ratings'});
                } else if (window.location.hash === '#my_plans') {
                    jQuery.get.downloaded_items({load: 'my_plans'});
                } else if (window.location.hash === '#my_unreviewed') {
                    jQuery.get.downloaded_items({load: 'downloads_reviews'});
                } else if (window.location.hash === '#my_downloads') {
                    jQuery.get.downloaded_items({load: 'my_downloads'});

                } else if (window.location.hash === '#search_results') {
                    //$(":mobile-pagecontainer").pagecontainer("change", "#home");
                }
                if (window.location.hash === '#item2') {
                    $(":mobile-pagecontainer").pagecontainer("change", "#home");
                }
            }
            $('.btn_close.btn_footer').on({
                mouseenter: function () {
                    //$('#' + this.id + ' img').attr({src: '/public/img/32/blue_arrow_left_2.png'});
                    // $('#' + this.id).css({'background':'#2a6da2'});
                },
                mouseleave: function () {
                    //$('#' + this.id + ' img').attr({src: '/public/img/32/blue_arrow_left_5.png'});
                }
            });
            if (window.location.pathname.indexOf('/my/plans/edit/') !== -1) {
                var item_id = parseInt(window.location.pathname.substring(15, 100));
                jQuery.get.item({load: 'edit_plan', navigate: 'edit_plan', item_id: item_id});
            }
            jQuery.init.listeners();
            jQuery.init.title();
            jQuery.init.panel_count_width();
            jQuery.init.db();
            //jQuery.get.device_info();
            jQuery.load.url_data();
            $('.input_search').focus();
        },
        listener_search: function () {
            console.log({function: 'init.listener_search'});
            $('.input_search').off('keyup').off('change').on({
                change: function () {
                    if ($(this).val() === '') {
                        $('#container_results').hide();
                        $('#search_result_summary').html('');
                    }
                },
                keyup: function (e) {
                    if (e.keyCode === 13) {
                        var search = $(this).val().trim();
                        if (search === '' || search.length === 0) {
                            $('#container_results').hide();
                            $('#search_result_summary').html('');
                        } else {
                            //e.preventDefault();
                            // jQuery.core.search({search: search});
                            jQuery.get.search_results({search: search, load: 'search_results'});
                        }
                    }
                }
            });
        },
        listener_tags: function () {
            console.log({function: 'init.listener_tags'});
            $('.tag').off('tap').on({
                tap: function () {
                    var data = {};
                    data.tag_id = $(this).data('tag_id');
                    console.info(data);
                    jQuery.core.search(data);
                }
            });
        },
        listeners: function () {
            console.log({function: 'init.listeners'});
            jQuery.init.listener_search();
            /*  
             if (window.history && window.history.pushState) {
             $(window).on('popstate', function () {
             console.info('page changed');
             });
             }
             */
            $('.mDiv').on({
               tap:function(){
                   console.info(this);
               } 
            });
            
            
            
            $(window).on({
                load: function (e) {
                    console.info(e);
                    if (window.location.hash === '#edit_plan') {
                        //   e.preventDefault();
                        //   $(":mobile-pagecontainer").pagecontainer("change", '#my_plans');
                    }
                },
                popstate: function (e) {
                    if (typeof e === 'object' && typeof e.originalEvent === 'object' && typeof e.originalEvent.state === 'object') {
                        var parameters = ['item_id', 'file_id'];
                        var data = {};
                        $.each(parameters, function (i, v) {
                            if (typeof e === 'object' && typeof e.originalEvent === 'object' && typeof e.originalEvent.state === 'object' && e.originalEvent.state !== null && typeof e.originalEvent.state[v] !== 'undefined') {
                                data[v] = e.originalEvent.state[v];
                            }
                        });
                        if (typeof data.item_id !== 'undefined' && window.location.hash === '#edit_plan') {
                            data.load = 'edit_plan';
                            jQuery.get.item(data);
                        }


                        console.warn({popstate: data});
                    }
                },
                hashchange: function (e) {
                    setTimeout(function () {
                        console.info({hash_change: window.location.hash});
                        var page = window.location.hash.replace('#', '');
                        if (typeof jQuery.pages[page] === 'object') {
                            console.info('page data exists');
                            var data = jQuery.pages[page];
                            console.info(data);
                            if (typeof data.get === 'string' && typeof jQuery.get[data.get] === 'function') {
                                console.info({get_found: data.get});
                                var pageDATA = {};
                                if (typeof data.load === 'string' && typeof jQuery.load[data.load] === 'function') {
                                    pageDATA['load'] = data.load;
                                }
                                jQuery.get[data.get](pageDATA);
                            } else
                            if (typeof data.load === 'string' && typeof jQuery.load[data.load] === 'function') {
                                console.info({load_found: data.load});
                                jQuery.load[data.load]();
                            }
                        }
                    }, 500);

                },
                statechange: function (e) {
                    console.info(e);
                    if (typeof e === 'object' && typeof e.state === 'object' && e.state.hash === 'string') {
                        var hash = e.state.hash;
                        console.info({hash: hash});
                        console.info({state: e.state});
                    }
                }
            });
            if (false) {
                $(window).on({
                    beforeunload: function (e) {
                        console.warn({beforeunload: e});
                        //return "This action will exit Uniting Teachers.";
                    },
                    beforeload: function (e) {
                        console.warn({beforeload: e});
                    },
                    load: function (e) {
                        console.warn({load: e});
                    },
                    loaddata: function (e) {
                        console.warn({loaddata: e});
                    },
                    loadmetadata: function (e) {
                        console.warn({loadmetadata: e});
                    },
                    stalled: function (e) {
                        console.warn({stalled: e});
                    },
                    drag: function (e) {
                        console.warn({drag: e});
                    },
                    blur: function (e) {
                        console.warn({blur: e});
                    },
                    click: function (e) {
                        console.warn({click: e});
                    },
                    change: function (e) {
                        console.warn({change: e});
                    },
                    focus: function (e) {
                        console.warn({focus: e});
                    },
                    contextmenu: function (e) {
                        console.warn({contextmenu: e});
                    },
                    dblclick: function (e) {
                        console.warn({dblclick: e});
                    },
                    error: function (e) {
                        console.warn({error: e});
                    },
                    hashchange: function (e) {
                        console.warn({hashchange: e});
                    },
                    input: function (e) {
                        console.warn({input: e});
                    },
                    invalid: function (e) {
                        console.warn({invalid: e});
                    },
                    loadstart: function (e) {
                        console.warn({loadstart: e});
                    },
                    devicemotion: function (e) {
                        console.warn({devicemotion: e});
                    },
                    deviceorientation: function (e) {
                        console.warn({deviceorientation: e});
                    },
                    dragend: function (e) {
                        console.warn({dragend: e});
                    },
                    dragenter: function (e) {
                        console.warn({dragenter: e});
                    },
                    dragleave: function (e) {
                        console.warn({dragleave: e});
                    },
                    dragover: function (e) {
                        console.warn({dragover: e});
                    },
                    dragstart: function (e) {
                        console.warn({dragstart: e});
                    },
                    scroll: function (e) {
                        console.warn({scroll: e});
                    },
                    filterchange: function (e) {
                        console.warn({filterchange: e});
                    },
                    //mouseup: function (e) {console.warn({mouseup: e});},
                    //mousedown: function (e) {console.warn({mousedown: e});},
                    //mousemove: function (e) {console.warn({mousemove: e});},
                    //mouseout: function (e) {console.warn({mouseout: e});},
                    //mouseover: function (e) {console.warn({mouseover: e});},
                    resize: function (e) {
                        console.warn({resize: e});
                    },
                    keydown: function (e) {
                        console.warn({keydown: e});
                    },
                    keypress: function (e) {
                        console.warn({keypress: e});
                    },
                    keyup: function (e) {
                        console.warn({keyup: e});
                    },
                    afterupdate: function (e) {
                        console.warn({afterupdate: e});
                    },
                    beforeupdate: function (e) {
                        console.warn({beforeupdate: e});
                    },
                    readystatechange: function (e) {
                        console.warn({readystatechange: e});
                    },
                    selectstart: function (e) {
                        console.warn({selectstart: e});
                    },
                    select: function (e) {
                        console.warn({select: e});
                    },
                    errorupdate: function (e) {
                        console.warn({errorupdate: e});
                    },
                    rowenter: function (e) {
                        console.warn({rowenter: e});
                    },
                    rowexit: function (e) {
                        console.warn({rowexit: e});
                    },
                    dataavailable: function (e) {
                        console.warn({dataavailable: e});
                    },
                    datasetchanged: function (e) {
                        console.warn({datasetchanged: e});
                    },
                    datasetcomplete: function (e) {
                        console.warn({datasetcomplete: e});
                    }
                });
            }

            /**
             * Buttons
             */
            $('.btn_panel_right').off('tap').on({
                tap: function (e) {
                    e.preventDefault();
                    window.location = jQuery.core.baseURL;
                }
            }).css({
                cursor: 'pointer'
            }).attr({
                title: 'Home'
            });
            $('a.home').off('tap').on({
                tap: function () {
                    window.location = '/';
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
                        jQuery.switch({page: data.page});
                    } else if (typeof data.content === 'string') {
                        jQuery.switch({page: data.content});
                    } else if (typeof data.post === 'string' && ['logout'].indexOf(data.post) !== -1) {
                        if (data.post === 'logout') {
                            jQuery.post.logout();
                        }
                    } else {
                        jQuery.core.search(data);
                    }
                },
                mousedown: function () {
                    //console.info('mousedown');
                    $(this).addClass('pressed');
                },
                mouseup: function () {
                    //console.info('mouseup');
                    $(this).removeClass('pressed');
                },
                mouseleave: function () {
                    //console.info('mouseleave');
                    $(this).removeClass('pressed');
                }
            });
            $('select').selectmenu().on({
                change: function () {
                    $(this).selectmenu('refresh');
                }
            });
            $('.btn_grp a').button();
            $('.btn_close').off('tap').on({
                tap: function (e) {
                    //e.preventDefault();
                    console.info(e);
                    jQuery.core.navigate({page: 'back'});
                    // $(":mobile-pagecontainer").pagecontainer("change", );
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
                    jQuery.get.signup({post: 'signup'});
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
            setTimeout(function () {
                $('.item_rating').slider({
                    stop: function (e) {
                        var data = {};
                        data.rating = parseInt($(this).val());
                        data.item_id = $(this).data('item_id');
                        jQuery.post.rating(data);
                    }
                }).off('blur').on({
                    blur: function (e) {
                        var data = {};
                        data.rating = parseInt($(this).val());
                        data.item_id = $(this).data('item_id');
                        jQuery.post.rating(data);
                    }
                });
            }, 10);
            $('#btn_my_settings_save').off('tap').on({
                tap: function () {
                    var data = {
                        first_name: $('#user_first_name').val(),
                        last_name: $('#user_last_name').val(),
                        email: $('#user_email').val(),
                        years: $('#user_years').val()
                    };
                    jQuery.post.settings(data);
                }
            });
            $('#btn_page_add_plan_save').off('tap').on({
                tap: function () {
                    console.info('Save plan');
                    var data = jQuery.get.new_plan({alerts: false});
                    if (data !== false) {
                        jQuery.post.plan(data);
                    } else {
                        console.error('data error');
                    }
                }
            });
            $('.btn_write_review').off('tap').on({
                tap: function () {
                    $('#panel_left').panel('close');
                    jQuery.core.reset({page: 'review_item'});
                    jQuery.get.item({item_id: $(this).data('item_id'), load: 'review_item_data'});
                }
            });
            $('.btn_my_unreviewed_navigate').off('tap').on({
                tap: function () {
                    jQuery.core.navigate({page: 'back'});
                }
            });
            $('.btn_my_unreviewed_save').off('tap').on({
                tap: function () {
                    var postDATA = {};
                    if (typeof $('#item_review').val() === 'string' && $('#item_review').val().length > 0) {
                        postDATA['review'] = $('#item_review').val();
                    }
                    if (typeof $('#info_item_id').val() === 'string' && $('#info_item_id').val().length > 0) {
                        postDATA['item_id'] = $('#info_item_id').val();
                    }
                    jQuery.post.review(postDATA);
                }
            });
            $("#btn_upload").click(function () {
                var input = $(document.createElement('input'));
                input.attr({"type": "file", id: 'inpt_file', name: 'files[]'});
                input.trigger('click'); // opening dialog
                input.css({display: 'none'});
                input.on({
                    change: function () {
                        jQuery.post.file();
                    }
                });
                $('#file-form').append(input);
                return false; // avoiding navigation
            });
            $('body').on({
                swipe: function (e) {
                    //console.log('swipe');
                },
                swiperight: function () {
                    console.info('open panel');
                    $("#panel_left").panel("open");
                },
                swipeleft: function () {
                    $("#panel_left").panel("close");
                },
                orientationchange: function (e) {
                    console.info({orientationchange: e.orientation});
                }
            });
            /**
             * Only run on page load
             */
            // if (jQuery.core.ready === false) {
            //  console.log('page load listeners');
            //jQuery.post.file();
            /**
             * Panels
             */
            $("#panel_left").panel({
                animate: true,
                swipeClose: true,
                positionFixed: true,
                display: "overlay"
            });
            $("#panel_right").panel({
                animate: true,
                swipeClose: true,
                positionFixed: true,
                display: "overlay"
            });
            $("body").on({
                mousemove: function (event) {
                    if (event.pageX < 5) {
                        $('#panel_left').panel('open');
                    }
                }
            });
            /**
             * CKEDITOR
             */
            if ($('#add_plan_description').length === 1) {
                CKEDITOR.replace('add_plan_description');
            }
            /**
             * window listeners
             */
            jQuery.core.ready = true;
            // }
            if ($('#file-form').length === 1) {
                var form = document.getElementById('file-form');
                form.onsubmit = function (event) {
                    event.preventDefault();
                };
                $('#upload-button').off('tap').on({
                    tap: function (e) {
                        e.preventDefault();
                        jQuery.post.file();
                    }
                });
            }
        },
        panel_count_width: function () {
            console.log({function: 'panel_count_width'});
            var highest = 0;
            $.each($('.ui-li-count'), function (i, v) {
                var width = parseInt($(v).css('width').replace('px', ''));
                if (width > highest) {
                    highest = width;
                }
            });
            $('.ui-li-count').css({width: highest + 'px'});
        }
    },
    get: {
        search_results: function (inDATA) {
            console.log({function: 'get.search_results', inDATA: inDATA});
            if (typeof inDATA === 'object' && typeof inDATA.search === 'string' && inDATA.search.length > 0) {
                var data = {};
                data.search = inDATA.search;
                $.ajax({
                    type: 'POST',
                    url: '/ajax/get_search_results',
                    data: data,
                    beforeSend: function () {
                        $('.ui-loader').loader('show');
                    },
                    complete: function () {
                        $('.ui-loader').loader('hide');
                    },
                    success: function (resp) {
                        console.info(resp);
                        if (typeof resp === 'object' && typeof resp.results === 'object' && typeof resp.status === 'string' && resp.status === 'success') {
                            if (typeof inDATA.load === 'string' && typeof jQuery.load[inDATA.load] === 'function') {
                                jQuery.load[inDATA.load]({results: resp.results});
                            }
                        }
                    },
                    error: function (error) {
                        console.error({type: 'error', data: error});
                    }
                });
            }
        },
        item: function (inDATA) {
            console.log({function: 'get.item', inDATA: inDATA});
            if (typeof inDATA === 'object' && typeof inDATA.item_id === 'number' && typeof inDATA.load === 'string') {
                var data = {item_id: inDATA.item_id};
                $.ajax({
                    type: 'POST',
                    url: jQuery.core.baseURL + '/ajax/get_item_details',
                    data: data,
                    beforeSend: function (x) {
                        $('.ui-loader').loader('show');
                    },
                    complete: function () {
                        $('.ui-loader').loader('hide');
                    },
                    success: function (resp) {
                        if (typeof resp === 'object' && typeof resp.result === 'object' && typeof resp.status === 'string' && resp.status === 'success') {
                            inDATA.results = resp.result;
                            if (typeof jQuery.load[inDATA.load] === 'function') {
                                jQuery.load[inDATA.load](inDATA);
                            } else {
                                console.error('jQuery.load.' + inDATA.load + ' !== "function"');
                            }
                        }
                    },
                    error: function (error) {
                        console.error({type: 'error', data: error});
                    }
                });
            }
            return false;
        },
        downloaded_items: function (inDATA) {
            console.log({function: 'get.downloaded_items'});
            $.ajax({
                url: jQuery.core.baseURL + '/ajax/get_my_downloads',
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
                    if (typeof resp === 'object' && typeof resp.results === 'object' && typeof resp.status === 'string' && resp.status === 'success') {
                        inDATA.results = resp.results;
                        if (typeof inDATA === 'object' && typeof inDATA.load === 'string' && typeof jQuery.load[inDATA.load] === 'function') {
                            jQuery.load[inDATA.load](inDATA);
                        } else {
                            console.error('No load');
                        }
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        },
        my_ratings: function (inDATA) {
            console.log({function: 'get.my_ratings'});
            $.ajax({
                url: jQuery.core.baseURL + '/ajax/get_my_ratings',
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
                    if (typeof resp === 'object' && typeof resp.results === 'object' && typeof resp.status === 'string' && resp.status === 'success') {
                        inDATA.results = resp.results;
                        if (typeof inDATA === 'object' && typeof inDATA.load === 'string' && typeof jQuery.load[inDATA.load] === 'function') {
                            jQuery.load[inDATA.load](inDATA);
                        } else {
                            console.error('jQuery.load.' + inDATA.load + ' !== "function"');
                        }
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        },
        signup: function (inDATA) {
            console.log({function: 'get.signup'});
            var fields = [
                {
                    id: 'first_name',
                    required: true
                }, {
                    id: 'last_name',
                    required: true
                }, {
                    id: 'email',
                    required: true
                }, {
                    id: 'years',
                    required: false
                }
            ];
            var data = {};
            var success = true;
            $.each(fields, function (i, v) {
                var val = $('#' + v.id).val();
                var fs = $('#' + v.id).closest('fieldset');
                if (val.length === 0 && v.required === true) {
                    $(fs).addClass('ui-state-error');
                    success = false;
                } else {
                    $(fs).removeClass('ui-state-error');
                }
                data[v.id] = val;
            });
            $('#form_signup input').on({
                change: function () {
                    jQuery.get.signup();
                }
            });
            if (success === true) {
                if (typeof inDATA.post === 'string' && typeof jQuery.post[inDATA.post] === 'function') {
                    jQuery.post[inDATA.post](data);
                }
                return data;
            } else {
                return false;
            }
        },
        new_plan: function (inDATA) {
            console.log({function: 'new_plan', inDATA: inDATA});
            var form = document.getElementById('file-form');
            var fileSelect = document.getElementById('file-select');
            var uploadButton = document.getElementById('upload-button');
            var data = {};
            var opts = {
                'add_plan_title': {required: true, name: 'title'},
                'add_plan_subtitle': {required: false, name: 'subtitle'},
                'add_plan_category': {required: true, name: 'category_id'},
                'add_plan_tags': {required: true, name: 'tags'}
            };
            var errors = [];
            $.each(opts, function (i, v) {
                $('#' + i).off('change').on({
                    change: function () {
                        jQuery.get.new_plan({alerts: false});
                    }
                });
                $('#' + i).closest('.ui-field-contain').removeClass('ui-state-error');
                var item = $('#' + i).val();
                if (typeof item === 'string' && item.length > 0) {
                    data[v.name] = item;
                } else if (typeof item === 'object' && item !== null && item.length > 0) {
                    data[v.name] = item;
                } else if (v.required === true) {
                    var msg = 'required parameter missing: ' + v.name;
                    if (typeof inDATA === 'object' && typeof inDATA.alerts === 'boolean' && inDATA.alerts === false) {
                    } else {
                        jQuery.core.alert({text: msg});
                    }
                    console.error(msg);
                    errors.push(msg);
                    $('#' + i).closest('.ui-field-contain').addClass('ui-state-error');
                }
            });
            var description = CKEDITOR.instances.add_plan_description.getData();
            if (typeof description === 'string' && description.length > 0) {
                data.description = description;
                $('#cke_add_plan_description').removeClass('ui-state-error');
            } else {
                var msg = 'required parameter missing: description';
                if (typeof inDATA === 'object' && typeof inDATA.alerts === 'boolean' && inDATA.alerts === false) {
                } else {
                    jQuery.core.alert({text: msg});
                }
                console.error(msg);
                errors.push(msg);
                $('#cke_add_plan_description').addClass('ui-state-error');
            }
            var files = [];
            $.each($('#tbl_add_plan_files tbody tr'), function (i, v) {
                files.push($(v).data());
            });
            if (files.length === 0) {
                var msg = 'required parameter missing: files';
                $('#tbl_add_plan_files').parent().addClass('ui-error');
                console.error(msg);
                errors.push(msg);
            } else {
                data.files = files;
                $('#tbl_add_plan_files').parent().removeClass('ui-error');
            }
            console.info(data);
            if (errors.length === 0) {
                return data;
            } else {
                return false;
            }
        },
        db: function (inDATA) {
            console.log({function: 'get.db', inDATA: inDATA});
            var db = openDatabase('db', '1.0', 'lesson plan db', 2 * 1024 * 1024);
            var returnDATA = {status: 'error', rows: 0, results: {}};
            db.transaction(function (tx) {
                tx.executeSql(inDATA.sql, [], function (tx, results) {
                    var len = results.rows.length, i;
                    for (i = 0; i < len; i++) {
                        returnDATA.status = 'success';
                        returnDATA.rows++;
                        returnDATA.results[i] = results.rows.item(i);
                    }
                }, null);
            });
            return returnDATA;
        },
        email_status: function () {
            console.log({function: 'get.email_status()'});
            var data = {email: $('#email').val()};
            $.ajax({
                type: 'POST',
                url: '/ajax/is_email_available',
                data: data,
                beforeSend: function (x) {
                    $('.ui-loader').loader('show');
                },
                complete: function () {
                    $('.ui-loader').loader('hide');
                },
                success: function (resp) {
                    console.info(resp);
                    if (typeof resp === 'object' && typeof resp.status === 'string' && typeof resp.message === 'string') {
                        if (resp.status === 'error') {
                            jQuery.core.alert({text: resp.message, type: resp.status});
                            var fs = $('#email').closest('fieldset');
                            $('#error_message_email').html('This email is already in use.');
                            $(fs).addClass('ui-state-error');
                        }
                    } else {
                        //jQuery.core.alert({text: resp.message, type: 'error'});
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        },
        url_parameter: function (inDATA) {
            console.log({function: 'get.url_parameter', inDATA: inDATA});
            if (typeof inDATA === 'string') {
                return decodeURIComponent((new RegExp('[?|&]' + inDATA + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null;
            } else if (typeof inDATA === 'object') {
                var returnDATA = {};
                $.each(inDATA, function (i, v) {
                    returnDATA[i] = decodeURIComponent((new RegExp('[?|&]' + i + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null;
                });
                return returnDATA;
            }
            return false;
        },
        device_info: function () {
            console.log({function: 'get.device_info'});
            var data = {};
            data.width = $(window).width();
            data.height = $(window).height();
            data.body_height = $('body').css('height');
            data.body_width = $('body').css('width');
            data.is_mobile = jQuery.core.is_mobile();
            data.platform = navigator.platform;
            data.appVersion = navigator.appVersion;
            data.language = navigator.language;
            data.maxTouchPoints = navigator.maxTouchPoints;
            data.mediaDevices = navigator.mediaDevices;
            data.vendor = navigator.vendor;
            return data;
        },
        item_widget: function (inDATA) {
            console.log({function: 'get.item_widget', inDATA: inDATA});

            var elem = $('<div data-role="collapsible" id="item' + inDATA.item_id + '"></div>');
            elem.append('<h3>' + inDATA.title + '</h3>');

            elem.append('<label class="label">Description</label>');
            elem.append('<div class="item_content">' + inDATA.description + '</div>');
            var split3 = String('');
            split3 += '<div class="split3">';
            split3 += '  <div class="left">';
            split3 += '    <label class="label">Files</label>';
            split3 += '    <table>';
            $.each(inDATA.files, function (i2, v2) {
                split3 += '  <tr>';
                split3 += '    <td>' + v2.file + '</td>';
                split3 += '    <td>' + v2.type + '</td>';
                split3 += '  </tr>';
            });
            split3 += '    </table>';
            split3 += '  </div>';
            split3 += '  <div class="center">';
            split3 += '    <label class="label">Veiws | Downloads</label>';
            split3 += '  </div>';
            split3 += '  <div class="right">';
            split3 += '    <label class="label">Tags</label>';
            split3 += '    <table>';
            $.each(inDATA.tags, function (i3, v3) {
                split3 += '  <tr>';
                split3 += '    <td>' + v3.tag + '</td>';
                split3 += '   </tr>';
            });
            split3 += '    </table>';
            split3 += '  </div>';
            split3 += '</div>';
            elem.append(split3);
            var rating = $('<div class="star_rating" id="rating_item_' + inDATA.item_id + '" data-rating="' + inDATA.rating + '"></div>');
            rating.rateYo({
                starWidth: '32px',
                normalFill: '#808080',
                ratedFill: '#F39C12',
                numStars: 5,
                maxValue: 5,
                precision: 1,
                rating: inDATA.rating,
                halfStar: false,
                fullStar: false,
                spacing: '0px',
                readOnly: false
            }).on({
                "rateyo.set": function (e, d) {
                    var data = {};
                    data.item_id = $(this).closest('.td_rating').data('item_id');
                    data.rating = d.rating;
                    jQuery.post.rating(data);
                }
            });
            elem.append(rating);


            /*
             html += '<table style="width:100%;">';
             html += '<thead>';
             html += '<tr>';
             html += '<td style="width:16.6%;"></td>';
             html += '<td style="width:16.6%;"></td>';
             html += '<td style="width:16.6%;"></td>';
             html += '<td style="width:16.6%;"></td>';
             html += '<td style="width:16.6%;"></td>';
             html += '<td style="width:16.6%;"></td>';
             html += '</tr>';
             html += '<tr>';
             html += '<td colspan="2">';
             html += '<label class="label">Downloaded</label>';
             html += inDATA.downloaded_on;
             html += '</td>';
             html += '<td colspan="2">' + '' + '</td>';
             html += '<td colspan="2">' + '<div class="star_rating" id="rating_item_' + inDATA.item_id + '" data-rating="' + inDATA.rating + '"></div>' + '</td>';
             html += '</tr>';
             html += '</tbody>';
             html += '<tfoot>';
             html += '<tr>';
             html += '<td colspan="6">';
             html += '<label class="label">Review</label>';
             html += '<textarea value="' + inDATA.review + '"></textarea>';
             html += '</td>';
             html += '</tr>';
             html += '</tfoot>';
             html += '</table>';
             html += '</p>';
             html += '</div>';
             $('#set_downloads').append(html);*/


            return elem;

        }
    },
    post: {
        review: function (inDATA) {
            console.log({function: 'post.review', inDATA: inDATA});
            //TODO: verify inDATA
            $.ajax({
                type: 'POST',
                url: jQuery.core.baseURL + '/ajax/post_review',
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
                        console.info(resp);
                        jQuery.core.alert({'text': 'Thank you. Your review was saved successfully.', type: 'success', timeout: 7000});
                        jQuery.get.downloaded_items({load: 'reviews_data'});
                    } else {
                        jQuery.core.alert({'text': 'Unable to process your request at this time. Please try again later.', type: 'error', timeout: 7000});
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        },
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
                    $('#ul_user .ui-first-child').html('guest');
                    $('li[data-page="my_unreviewed"],li[data-page="my_unrated"],li[data-page="my_plans"],li[data-page="my_settings"],li[data-page="add_plan"]').remove();
                    $('li[data-post="logout"]').html('login').data({post: '', 'page': 'login'}).attr({'data-post': '', 'data-page': 'login'});
                },
                error: function (error) {
                    console.error(error);
                }
            });
        },
        rating: function (inDATA) {
            console.log({function: 'post.rating', inDATA: inDATA});
            //TODO: verify inDATA
            $.ajax({
                url: jQuery.core.baseURL + '/ajax/post_rating',
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
                        jQuery.core.alert({text: 'Rating saved', timeout: 10000, type: 'info'});
                    }
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
                        window.location = '/';
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
        plan: function (inDATA) {
            console.log({function: 'post.plan', inDATA: inDATA});
            //TODO: verify inDATA
            $.ajax({
                type: 'POST',
                url: jQuery.core.baseURL + '/ajax/post_plan',
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
                        console.info('success');
                        $('input.reset,textarea.reset').val('');
                        $('select.reset').val('0').selectmenu('refresh');
                        $('tbody.reset').html('');
                        jQuery.core.alert({text: 'Plan saved'});
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        },
        file: function () {
            console.log({function: 'post.file'});
            if ($('#file-select').length === 1) {
                // var fileSelect = document.getElementById('file-select');
                var fileSelect = document.getElementById('inpt_file');
                var uploadButton = document.getElementById('upload-button');
                // Get the selected files from the input.
                var files = fileSelect.files;
                // Create a new FormData object.
                var formData = new FormData();
                // Loop through each of the selected files.
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    formData.append('files[]', file, file.name);
                    var xhr = new XMLHttpRequest();
                    // Open the connection.
                    xhr.open('POST', '/upload/file', true);
                    // Set up a handler for when the request finishes.
                    // xhr.responseType = "arraybuffer";
                    xhr.onload = function (e) {
                        if (xhr.status === 200) {
                            if (typeof e === 'object' && typeof e.currentTarget === 'object' && typeof e.currentTarget.response === 'string') {
                                var files = JSON.parse(e.currentTarget.response);
                                if (typeof files.files === 'object') {
                                    files = files.files;
                                }
                                console.info(files);
                                $.each(files, function (i, v) {
                                    console.info(v);
                                    var ext = v.name.substring(v.name.lastIndexOf('.') + 1, v.name.length);
                                    console.info(ext);
                                    var row = $('<tr><td><i class="fi flaticon-' + ext + '"></i>' + v.type + '</td><td>' + v.name + '</td></tr>');
                                    row.data(v);
                                    $('#inpt_file').remove();
                                    $('#tbl_add_plan_files').parent().removeClass('ui-error');
                                    $('#tbl_add_plan_files tbody').append(row);
                                    $('#tbl_add_plan_files tfoot').hide();
                                });
                                $('#file-select').val('');
                            }
                        } else {
                            alert('An error occurred!');
                        }
                    };
                    // Send the Data.
                    xhr.send(formData);
                }
            }
        },
        item_view: function (inDATA) {
            console.log({function: 'post.item_view', inDATA: inDATA});
            if (typeof inDATA === 'object' && typeof inDATA.item_id === 'number' && inDATA.item_id > 0) {
                var data = {};
                data.item_id = inDATA.item_id;
                $.ajax({
                    type: 'POST',
                    url: '/ajax/post_item_view',
                    data: data,
                    beforeSend: function () {
                        $('.ui-loader').loader('show');
                    },
                    complete: function () {
                        $('.ui-loader').loader('hide');
                    },
                    success: function (resp) {
                        console.info(resp);
                        if (typeof resp === 'object' && typeof resp.results === 'object' && typeof resp.status === 'string' && resp.status === 'success') {

                        }
                    },
                    error: function (error) {
                        console.error({type: 'error', data: error});
                    }
                });
            }
        }
    },
    load: {
        edit_plan: function (inDATA) {
            console.log({function: 'load.edit_plan', inDATA: inDATA});
            $('#edit_plan').data(inDATA.results);
            $('#edit_plan_title').val(inDATA.results.title).data({title: inDATA.results.title});
            $('#edit_plan_subtitle').val(inDATA.results.subtitle).data({subtitle: inDATA.results.subtitle});
            $('#edit_plan_category').val(inDATA.results.category_id).selectmenu('refresh');
            var tags = [];
            $.each(inDATA.results.tags, function (i, v) {
                tags.push(v.tag_id);
            });
            $('#edit_plan_tags').val(tags).selectmenu('refresh');
            $('#edit_plan_description').html(inDATA.results.description).data({description: inDATA.results.description}).textinput().textinput('refresh');
            $('#tbl_edit_plan_files tbody').html('');
            $.each(inDATA.results.files, function (i, v) {
                var ext = v.name.substring(v.name.lastIndexOf('.') + 1, v.name.length);
                var row = String('');
                row += '<tr data-file_id="' + v.file_id + '" ';
                row += 'data-created_on="' + v.created_on + '" ';
                row += 'data-size="' + v.size + '" ';
                row += 'data-name="' + v.name + '" ';
                row += 'data-type="' + v.type + '" ';
                row += '>';
                row += '<td>';
                row += '<i class="fi flaticon-' + ext + '"></i>';
                row += v.type;
                row += '</td>';
                row += '<td><a href="JAVASCRIPT:jQuery.core.download(' + v.file_id + ');">' + v.name + '</a></td>';
                row += '</tr>';
                $('#tbl_edit_plan_files tbody').append(row);
                $('#tbl_edit_plan_files tfoot').hide();
            });
            var page = '#edit_plan';
            $(":mobile-pagecontainer").pagecontainer("change", page);
            setTimeout(function () {
                var eventObj = {
                    item_id: inDATA.results.item_id
                };
                console.info({'replaceState': eventObj});
                //history.replaceState(eventObj, "", page);

            }, 1500);
        },
        my_downloads: function (inDATA) {
            console.log({function: 'load.my_downloads', inDATA: inDATA});
            $('#set_downloads').append('');
            $.each(inDATA.results, function (i, v) {
                //var item_widget = jQuery.get.item_widget(v);
                // $('#set_downloads').append(item_widget);
                var html = String('');
                html += '<div data-role="collapsible" id="item' + v.item_id + '"  data-category_id="' + v.category_id + '"  >';
                html += '<h3>' + '<img class="category_image" src="/public/img/' + v.category_image + '" /> ' + v.title + '</h3>';
                // html += '<p>';
                html += '<label class="label">Description</label>';
                html += '<div class="item_content">' + v.description + '</div>';
                html += '<div class="split3">';
                html += '<div class="left">';
                html += '<label class="label">Files</label>';
                html += '<table>';
                $.each(v.files, function (i2, v2) {
                    html += '<tr>';
                    html += '<td>' + v2.file + '</td>';
                    html += '<td>' + v2.type + '</td>';
                    html += '</tr>';
                });
                html += '</table>';
                html += '</div>';
                html += '<div class="center_left">';
                html += '<label class="label">Veiws</label>';
                html += '<div style="text-align:center;">' + v.views + '</div>';
                html += '</div>';

                html += '<div class="center_right">';
                html += '<label class="label"> Downloads</label>';
                html += '<div style="text-align:center;">' + v.downloads + '</div>';
                html += '</div>';

                html += '<div class="right">';
                html += '<label class="label">Tags</label>';
                html += '<table>';
                $.each(v.tags, function (i3, v3) {
                    html += '<tr>';
                    html += '<td>' + v3.tag + '</td>';
                    html += '</tr>';
                });
                html += '</table>';
                html += '</div>';
                html += '</div>';
                html += '<table style="width:100%;">';
                html += '<thead>';
                html += '<tr>';
                html += '<td style="width:16.6%;"></td>';
                html += '<td style="width:16.6%;"></td>';
                html += '<td style="width:16.6%;"></td>';
                html += '<td style="width:16.6%;"></td>';
                html += '<td style="width:16.6%;"></td>';
                html += '<td style="width:16.6%;"></td>';
                html += '</tr>';
                html += '</tr>';
                html += '<tr>';
                html += '<td colspan="2">'
                html += '<label class="label">Downloaded</label>';
                html += v.downloaded_on;
                html += '</td>';
                html += '<td colspan="2">' + '' + '</td>';
                html += '<td colspan="2">' + '<div class="star_rating" id="rating_item_' + v.item_id + '" data-rating="' + v.rating + '"></div>' + '</td>';
                html += '</tr>';
                html += '</tbody>';
                html += '<tfoot>';
                html += '<tr>';
                html += '<td colspan="6">';
                html += '<label class="label">Review</label>';
                html += '<textarea value="' + v.review + '"></textarea>';
                html += '</td>';
                html += '</tr>';
                html += '</tfoot>';
                html += '</table>';
                html += '</p>';
                html += '</div>';
                $('#set_downloads').append(html);
                $('#rating_item_' + v.item_id).rateYo({
                    starWidth: '32px',
                    normalFill: '#808080',
                    ratedFill: '#F39C12',
                    numStars: 5,
                    maxValue: 5,
                    precision: 1,
                    rating: v.rating,
                    halfStar: false,
                    fullStar: false,
                    spacing: '0px',
                    readOnly: false
                }).on({
                    "rateyo.set": function (e, d) {
                        var data = {};
                        data.item_id = $(this).closest('.td_rating').data('item_id');
                        data.rating = d.rating;
                        jQuery.post.rating(data);
                    }
                });
            });
            $("#set_downloads").collapsibleset({
                disabled: false,
                collapsedIcon: "arrow-r",
                expandedIcon: "arrow-d",
                inset: false,
                mini: true,
                corners: false
            });

        },
        search_results: function (inDATA) {
            console.log({function: 'load.search_results', inDATA: inDATA});
            $('#set_search_results').html('');
            var rating = 0;
            $.each(inDATA.results, function (i, v) {
                if (typeof v.item_id !== 'undefined') {
                    if(typeof v.ratings === 'object'){
                        var ratings_sum = 0;
                        var ratings_qty = 0;
                        $.each(v.ratings,function(i,v){
                            ratings_sum += parseFloat(v.rating);
                            ratings_qty++;
                        });
                        if(ratings_sum > -1 && ratings_qty > 0){
                            rating = ratings_sum / ratings_qty;
                        }
                    }
                    var html = String('');
                    html += '<div data-role="collapsible" id="item' + v.item_id + '"  data-category_id="' + v.category_id + '"  >';
                    html += '<h3>';
                    html += v.title;
                    html += '<div style="float:right;width:150px;">';
                    html += '<table class="tbl_item_stats" style="margin-top:-10px;">';
                    html += '<tr>';
                    html += '<td>' + '<img class="img_item_icon" src="/public/img/main/32/icon_eye.png">' + '</td>';
                    html += '<td>' + '<img class="img_item_icon" src="/public/img/main/32/icon_download.png">' + '</td>';
                    html += '<td>' + '<img class="img_item_icon" src="/public/img/main/32/icon_dollar.png">' + '</td>';
                    html += '</tr>';
                    html += '<tr>';
                    html += '<td>' + v.views + '</td>';
                    html += '<td>' + v.downloads + '</td>';
                    html += '<td>' + '$' + v.cost; + '</td>';
                    html += '</tr>';
                    html += '</table>';
                    html += '</div>';
                    html += '</h3>';
                    html += '<label class="label">Description</label>';
                    html += '<div class="item_content">' + v.description + '</div>';
                    html += '<div class="split3">';
                    html += '<div class="left">';
                    html += '<label class="label">Files</label>';
                    html += '<div class="container_files">';
                    html += '<table class="tbl_files">';
                    $.each(v.files, function (i2, v2) {
                        var ext = v2.file.substring(v2.file.lastIndexOf('.') + 1, v2.file.length);
                        var row = String('');
                        row += '<tr data-file_id="' + v2.file_id + '" ';
                        row += 'data-name="' + v2.file + '" ';
                        row += 'data-type="' + v2.type + '" ';
                        row += '>';
                        row += '<td>';
                        row += '<i class="fi flaticon-' + ext + '"></i>';
                        row += '</td>';
                        row += '<td><a href="JAVASCRIPT:jQuery.core.download(' + v2.file_id + ');">' + v2.file + '</a></td>';
                        row += '</tr>';
                        html += row;
                    });
                    html += '</table>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="center">';
                    html += '<div class="star_rating" id="rating_item_' + v.item_id + '" data-rating="' + rating + '"></div>';
                    html += '<div style="text-align:center;">';
                    html += rating.toFixed(2) + ' (' + ratings_qty + ')';
                    html += '</div>';
                   /*
                    html += '<table>';
                    $.each(v.ratings,function(i,v){
                       
                    html += '<tr data-rating_id="'+v.rating_id+'">';
                    html += '<td>' + v.created_on + '</td>';
                    html += '<td>' + v.rating + '</td>';
                    html += '</tr>';
                     
                    });
                    
                    html += '</table>';
                    */
                    html += '</div>';
                    html += '<div class="right">';
                    html += '<label class="label">Tags</label>';
                    html += '<div class="tags_container">';
                    $.each(v.tags, function (i3, v3) {
                        html += '<div data-tag_id="' + v3.tag_id + '" class="tag">' + v3.tag + '</div>';
                    });
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    //html += '<div class="star_rating" id="rating_item_' + v.item_id + '" data-rating="' + v.rating + '"></div>';
                    html += '<table style="width:100%;">';
                    html += '<thead>';
                    html += '<tr>';
                    html += '<td style="width:16.6%;"></td>';
                    html += '<td style="width:16.6%;"></td>';
                    html += '<td style="width:16.6%;"></td>';
                    html += '<td style="width:16.6%;"></td>';
                    html += '<td style="width:16.6%;"></td>';
                    html += '<td style="width:16.6%;"></td>';
                    html += '</tr>';
                    html += '</tr>';
                    html += '<tr>';
                    html += '<td colspan="2">';

                    html += '</td>';
                    html += '<td colspan="2">' + '' + '</td>';
                    //html += '<td colspan="2">' + '<div class="star_rating" id="rating_item_' + v.item_id + '" data-rating="' + v.rating + '"></div>' + '</td>';
                    html += '</tr>';
                    html += '</tbody>';
                    html += '<tfoot>';
                    html += '<tr>';
                    html += '<td colspan="6">';
                    //html += '<label class="label">Review</label>';
                    // html += '<textarea value="' + v.review + '"></textarea>';
                    html += '</td>';
                    html += '</tr>';
                    html += '</tfoot>';
                    html += '</table>';
                    html += '</p>';
                    html += '</div>';
                    $('#set_search_results').append(html);
                    $('#item' + v.item_id).collapsible({
                        collapsedIcon: v.category_image.replace('.png', ''),
                        expandedIcon: v.category_image.replace('.png', '')
                    }).data(v);
                   
                    
                    $('#rating_item_' + v.item_id).rateYo({
                        starWidth: '32px',
                        normalFill: '#808080',
                        ratedFill: '#F39C12',
                        numStars: 5,
                        maxValue: 5,
                        precision: 1,
                        rating: rating,
                        halfStar: false,
                        fullStar: false,
                        spacing: '0px',
                        readOnly: true
                    }).on({
                        "rateyo.set": function (e, d) {
                            var data = {};
                            data.item_id = $(this).closest('.td_rating').data('item_id');
                            data.rating = d.rating;
                            jQuery.post.rating(data);
                        }
                    });
                }
            });
             jQuery.init.listener_tags();
            $("#set_search_results").collapsibleset({
                disabled: false,
                collapsedIcon: "arrow-r",
                expandedIcon: "arrow-d",
                inset: false,
                mini: true,
                corners: false,
            });

            $('div[data-role="collapsible"]').collapsible({
                expand: function (event, ui) {
                    $('#overlay_top,#overlay_bottom').remove();
                    $('body').append('<div id="overlay_top"></div><div id="overlay_bottom"></div>');
                    var item_id = parseInt(this.id.replace('item', ''));
                    console.info(item_id);
                    jQuery.post.item_view({item_id: item_id});
                    var height_top = $(this).offset().top;
                    var height_bottom = $(document).height() - $(this).offset().top - parseInt($(this).css('height').replace('px', ''));
                    console.info({height_top: height_top, height_bottom: height_bottom});
$('#item' + item_id + ' a.ui-collapsible-heading-toggle').addClass('item_selected');
                    $('#overlay_top').css({
                        'width': $('body').css('width'),
                        'height': height_top,
                        'background-color': 'rgba(0,0,0,0.8)',
                        'z-index': '999',
                        'position': 'absolute',
                        'top': 0
                    });
                    $('#overlay_bottom').css({
                        'width': $('body').css('width'),
                        'height': height_bottom,
                        'background-color': 'rgba(0,0,0,0.8)',
                        'z-index': '999',
                        'position': 'absolute',
                        'top': $(this).offset().top + parseInt($(this).css('height').replace('px', ''))
                    });
                    $('#overlay_bottom,#overlay_top').on({
                        tap: function (e) {
                            $('#overlay_bottom,#overlay_top').remove();
                            $('.ui-collapsible').collapsible('collapse');
                             var x = e.pageX - e.target.offsetLeft;
                             var elem = document.elementFromPoint(e.pageX, e.pageY); // x, y
                             console.info({x:e.pageX,y:e.pageY});
                             console.info(elem);
                             $(elem).click();
                            
                        }
                    });

                },
                collapse: function () {
                    $('#overlay_top,#overlay_bottom').remove();
                    $('a.ui-collapsible-heading-toggle').removeClass('item_selected');
                }
            });
            jQuery.core.navigate({page: 'search_results'});
        },
        downloads_ratings: function (inDATA) {
            console.log({function: 'load.downloads_ratings', inDATA: inDATA});
            $('table.ratings tbody').html('');
            $.each(inDATA.results, function (i, v) {
                var rating = '';
                if (typeof v.rating === 'string') {
                    rating = v.rating;
                }
                var row = String('');
                row += '<tr>';
                row += '<td class="td_downloaded_on">' + v.downloaded_on + '</td>';
                row += '<td class="td_title">' + v.title + '</td>';
                row += '<td class="td_rating" data-rating="' + rating + '" data-item_id="' + v.item_id + '">';
                /*  row += '<div class="stars">';
                 row += '<div class="star1">';
                 row += '<img class="star_active" src="/public/img/star_32.png" alt=""/>';
                 row += '<img class="star_inactive" src="/public/img/star_32_inactive.png" alt=""/>';
                 row += '</div>';
                 row += '<div class="star2">';
                 row += '<img class="star_active" src="/public/img/star_32.png" alt=""/>';
                 row += '<img class="star_inactive" src="/public/img/star_32_inactive.png" alt=""/>';
                 row += '</div>';
                 row += '<div class="star3">';
                 row += '<img class="star_active" src="/public/img/star_32.png" alt=""/>';
                 row += '<img class="star_inactive" src="/public/img/star_32_inactive.png" alt=""/>';
                 row += '</div>';
                 row += '<div class="star4">';
                 row += '<img class="star_active" src="/public/img/star_32.png" alt=""/>';
                 row += '<img class="star_inactive" src="/public/img/star_32_inactive.png" alt=""/>';
                 row += '</div>';
                 row += '<div class="star5">';
                 row += '<img class="star_active" src="/public/img/star_32.png" alt=""/>';
                 row += '<img class="star_inactive" src="/public/img/star_32_inactive.png" alt=""/>';
                 row += '</div>';*/
                //row += '<input class="item_rating ui-body-inherit ui-shadow-inset ui-corner-all" value="' + rating + '" data-item_id="' + v.item_id + '" type="number" data-type="range" max="5" min="0">';
                row += '<div id="rating_item_' + v.item_id + '" data-rating="' + rating + '">';

                row += '</div>';
                row += '</td>';
                row += '</tr>';
                if (rating === '') {
                    $('#tbl_downloads_unrated tbody').append(row);
                    rating = 0;
                } else {
                    $('#tbl_downloads_rated tbody').append(row);
                }
                $('#rating_item_' + v.item_id).rateYo({
                    starWidth: '32px',
                    normalFill: '#808080',
                    ratedFill: '#F39C12',
                    numStars: 5,
                    maxValue: 5,
                    precision: 1,
                    rating: rating,
                    halfStar: false,
                    fullStar: false,
                    spacing: '0px',
                    readOnly: false,
                    onSet: function (rating, rateYoInstance) {
                        //console.warn(rating);
                    },
                    onChange: function (rating, rateYoInstance) {
                        //console.warn(rating);
                    }
                }).on({
                    "rateyo.change": function (e, data) {
                        //var rating = data.rating;
                        //console.info(data.rating);
                    },
                    "rateyo.set": function (e, d) {
                        var data = {};
                        data.item_id = $(this).closest('.td_rating').data('item_id');
                        data.rating = d.rating;
                        jQuery.post.rating(data);
                        //console.info(data);
                    }
                });

            });
            $('.item_rating').slider({
                stop: function (e) {
                    var data = {};
                    data.rating = parseInt($(this).val());
                    data.item_id = $(this).data('item_id');
                    jQuery.post.rating(data);
                }
            }).off('blur').on({
                blur: function (e) {
                    var data = {};
                    data.rating = parseInt($(this).val());
                    data.item_id = $(this).data('item_id');
                    console.info(typeof data.rating);
                    if (isNaN(data.rating) === false) {
                        jQuery.post.rating(data);
                    }
                }
            });
            if (typeof inDATA.navigate === 'string') {
                jQuery.core.navigate(inDATA);
            }
            //$(":mobile-pagecontainer").pagecontainer("change", '#');
        },
        downloads_reviews: function (inDATA) {
            console.log({function: 'load.downloads_reveiws', inDATA: inDATA});
            $('table.tbl_reviews  tbody').html('');
            $.each(inDATA.results, function (i, v) {
                var btn_text = 'Review Now';
                if (v.reviewed === 1) {
                    btn_text = 'Reviewed';
                }
                var row = String('');
                row += '<tr data-item_id="' + v.item_id + '">';
                row += '<td>' + v.downloaded_on + '</td>';
                row += '<td>' + v.title + '</td>';
                row += '<td>';
                row += '<a ';
                row += ' class="btn_write_review" ';
                row += ' data-item_id="' + v.item_id + '" ';
                row += '>';
                row += '</a>';
                row += '</td>';
                row += '</tr>';
                if (v.reviewed === 1) {
                    $('#tbl_reviewed tbody').append(row);
                } else {
                    $('#tbl_unreviewed tbody').append(row);
                }
            });
            $('.btn_write_review').button({
                icon: 'comment',
                iconpos: 'notext',
                wrapperClass: 'btn_action',
                inline: true,
                corners: false
            }).parent().on({
                tap: function () {
                    $('#panel_left').panel('close');
                    jQuery.core.reset({page: 'review_item'});
                    jQuery.get.item({item_id: $(this).closest('tr').data('item_id'), load: 'review_item_data'});
                }
            });
            if (typeof inDATA.navigate === 'string') {
                jQuery.core.navigate(inDATA);
            }
            //jQuery.core.navigate(inDATA);
            //$(":mobile-pagecontainer").pagecontainer("change", '#');
        },
        item_details: function (inDATA) {
            if (typeof inDATA === 'object' && typeof inDATA.results === 'object') {
                inDATA = inDATA.results;
            }
            console.log({function: 'load.item_details', inDATA: inDATA});
            var tbody = '';
            inDATA.rating = parseFloat(inDATA.rating).toFixed(2);
            window.inDATA = inDATA;
            if (inDATA.rating === 'NaN') {
                inDATA.rating = 'N/A';
            }
            if (typeof inDATA.subtitle !== 'string') {
                inDATA.subtitle = String('n/a');
            }
            tbody += '<div class="ui-grid-a ui-responsive">';
            tbody += '<div class="ui-block-a"><div class="ui-body ui-body-d">Name</div></div>';
            tbody += '<div class="ui-block-b"><div class="ui-body ui-body-d">' + inDATA.title + '</div></div>';
            tbody += '</div>';
            tbody += '<div class="ui-grid-a ui-responsive">';
            tbody += '<div class="ui-block-a"><div class="ui-body ui-body-d">Subtitle</div></div>';
            tbody += '<div class="ui-block-b"><div class="ui-body ui-body-d">' + inDATA.subtitle + '</div></div>';
            tbody += '</div>';
            tbody += '<div class="ui-grid-a ui-responsive">';
            tbody += '<div class="ui-block-a"><div class="ui-body ui-body-d">Category</div></div>';
            tbody += '<div class="ui-block-b"><div class="ui-body ui-body-d" onClick="JAVASCRIPT:jQuery.core.search({category_id:' + inDATA.category_id + '});">' + inDATA['category'] + '</div></div>';
            tbody += '</div>';
            //Tags
            tbody += '<div class="ui-grid-a ui-responsive">';
            tbody += '<div class="ui-block-a"><div class="ui-body ui-body-d">Tags</div></div>';
            tbody += '<div class="ui-block-b"><div class="ui-body ui-body-d">';
            $.each(inDATA.tags, function (i, v) {
                tbody += '<span class="tag" onClick="JAVASCRIPT:jQuery.core.search({tag_id:' + v.tag_id + '});" data-tag_id="' + v.tag_id + '">' + v.tag + '</span>';
            });
            tbody += '</div></div>';
            tbody += '</div>';
            tbody += '<div class="ui-grid-a ui-responsive">';
            tbody += '<div class="ui-block-a">';
            tbody += '<div class="ui-body ui-body-d row3">';
            tbody += '<div>Views</div>';
            tbody += '<div>Rating</div>';
            tbody += '<div>Downloads</div>';
            tbody += '</div>';
            tbody += '</div>';
            tbody += '<div class="ui-block-b">';
            tbody += '<div class="ui-body ui-body-d row3">';
            tbody += '<div>' + inDATA.views + '</div>';
            tbody += '<div>' + inDATA.rating + '</div>';
            tbody += '<div>' + inDATA.downloads + '</div>';
            tbody += '</div>';
            tbody += '</div>';
            tbody += '</div>';
            tbody += '<div class="ui-grid-a ui-responsive">';
            tbody += '<div class="ui-block-a">';
            tbody += '<div class="ui-body ui-body-d row2">';
            tbody += '<div>Created</div>';
            tbody += '<div>Creator</div>';
            tbody += '</div>';
            tbody += '</div>';
            tbody += '<div class="ui-block-b">';
            tbody += '<div class="ui-body ui-body-d row2">';
            tbody += '<div>' + inDATA.created_on + '</div>';
            tbody += '<div>' + inDATA.creator_username + '</div>';
            tbody += '</div>';
            tbody += '</div>';
            tbody += '</div>';
            tbody += '<div class="ui-grid-a ui-responsive">';
            tbody += '<div class="ui-block-a"><div class="ui-body ui-body-d">Description</div></div>';
            tbody += '<div class="ui-block-b"><div class="ui-body ui-body-d">' + inDATA.description + '</div></div>';
            tbody += '</div>';
            tbody += '<div class="ui-grid-a ui-responsive">';
            tbody += '<div class="ui-block-a"><div class="ui-body ui-body-d">Files</div></div>';
            tbody += '<div class="ui-block-b"><div class="ui-body ui-body-d">';
            tbody += '<table class="tbl_files" style="width:100%;">';
            $.each(inDATA.files, function (i, v) {
                var filename = decodeURIComponent(v.name);
                var ext = filename.substring(filename.lastIndexOf('.') + 1, filename.length);
                tbody += '<tr data-file_id="' + v.file_id + '">';
                tbody += '<td style="width:80%;">';
                tbody += '<i class="fi flaticon-' + ext + '"></i>';
                tbody += filename;
                tbody += '</td>';
                tbody += '<td style="width:20%">';
                tbody += '<a class="btn_download" style="float:right;">';
                //tbody += '<img src="/public/img/32/blue_download_1.png" />';
                // tbody += '<i class="ui-btn-img"></i>';
                tbody += '</a>';
                tbody += '</td>';
                tbody += '</tr>';
            });
            tbody += '</table>';
            tbody += '</div></div></div>';
            $('#container_item_data2').html(tbody);
            $('#container_item_data2').show();
            $('.container_item_data .data.item_title').html(inDATA.title);
            $('.container_item_data .data.item_subtitle').html(inDATA.subtitle);
            $('.container_item_data .data.item_category').html(inDATA.category);
            $('.container_item_data .data.item_description').html(inDATA.description);
            $('.btn_download').button({
                icon: 'download',
                inline: true,
                corners: false,
                iconpos: 'notext',
                wrapperClass: 'btn_action'
            }).parent().on({
                tap: function () {
                    var file_id = $(this).closest('tr').data('file_id');
                    if (typeof file_id === 'number') {
                        jQuery.core.download(file_id);
                    }
                }
            });
            var page = '#item';
            if (typeof inDATA.item_id === 'number') {
                $(page).data({item_id: inDATA.item_id});
            }
            //jQuery.core.navigate({page: page});
            $(":mobile-pagecontainer").pagecontainer("change", '#item');
        },
        results: function (inDATA) {
            console.log({function: 'load.results', inDATA: inDATA});
            var results = '';
            $('#ul_results li').remove();
            $.each(inDATA, function (i, v) {
                if (typeof v.description !== 'string') {
                    v.description = '<p class="italic">no description</p>';
                }
                results += '<li class="ui-shadow">';
                results += '<a class="result_a" href="JAVASCRIPT:jQuery.core.search({item_id:' + v.item_id + '});">';
                results += '<img class="img_category" src="/public/img/' + v.category_image + '" />';
                results += '<div class="results_main">';
                results += '<div class="result_title">' + v.title + '</div>';
                results += '<div class="result_description">' + v.description + '</div>';
                results += '</div>';
                results += '<div class="result_ratings ui-shadow ba_g1">';
                results += '<table class="result_stats">';
                results += '<thead>';
                results += '<tr>';
                results += '<td><img src="/public/img/icon_rating.png" /></td>';
                results += '<td><img src="/public/img/icon_download.png" /></td>';
                results += '<td><img src="/public/img/icon_eye.png" /></td>';
                results += '</tr>';
                results += '</thead>';
                results += '<tbody>';
                results += '<tr>';
                results += '<td>' + v.rating + '</td>';
                results += '<td>' + v.downloads + '</td>';
                results += '<td>' + v.views + '</td>';
                results += '</tr>';
                results += '</tbody>';
                results += '</table>';
                results += '</div>';
                results += '</a>';
                results += '</li>';
            });
            if (results !== '') {
                $('#ul_results').html(results).listview().listview("refresh");
            }
            $('#container_results').show();
        },
        page: function (inDATA) {
            console.error({function: 'load.page', inDATA: inDATA});
            var pages = {
                my_unreviewed: {
                    id: 'my_unreviewed',
                    data: 'todoDATA',
                    columns: [
                        {
                            id: 'downloaded_on',
                            title: 'Downloaded'
                        }, {
                            id: 'title',
                            title: 'Title'
                        }
                    ]
                },
                my_settings: {
                    id: 'my_settings',
                    data: ''
                },
                my_unrated: {
                    id: 'my_unrated',
                    data: 'todoDATA',
                    columns: [
                        {
                            id: 'downloaded_on',
                            title: 'Downloaded'
                        }, {
                            id: 'title',
                            title: 'Title'
                        }
                    ]
                },
                my_plans: {
                    id: 'my_plans',
                    data: '',
                    load: 'my_plans'
                },
                login: {
                    id: 'login'
                },
                signup: {
                    id: 'signup'
                },
                add_plan: {
                    id: 'add_plan'
                }
            };
            if (typeof inDATA === 'object' && typeof inDATA.page === 'string' && typeof pages[inDATA.page] === 'object') {
                var data = pages[inDATA.page];
                $('#panel_left').panel('close');
                if (typeof data.load === 'string' && typeof jQuery.load[data.load] === 'function') {
                    jQuery.load[data.load](inDATA);
                } else {
                    jQuery.core.navigate({page: '#' + pages[inDATA.page].id});
                }
                return true;
            }
            return false;
        },
        url_data: function () {
            console.log({function: 'load.url_data'});
            var options = {
                tag_id: {
                    int: true
                },
                category_id: {
                    int: true
                },
                item_id: {
                    int: true
                },
                search: {
                    url_decode: true
                }
            };
            var url_parameter = jQuery.get.url_parameter(options);
            $.each(options, function (i, v) {
                var value = false;
                if (typeof url_parameter === 'object' && (typeof url_parameter[i] === 'string' || typeof url_parameter[i] === 'number')) {
                    value = url_parameter[i];
                }
                if (value !== false) {
                    //console.warn({name: i, value: value});
                    var data = {};
                    data[i] = value;
                    if (typeof v.int === 'boolean' && v.int === true) {
                        data[i] = parseInt(value);
                    }
                    console.info(data);
                    if (typeof data.item_id === 'number') {
                        var pages = {
                            '#item2': {
                                set: 'core',
                                subset: false,
                                func: 'search'
                            },
                            '#review_item': {
                                set: 'get',
                                subset: 'data',
                                func: 'item',
                                data: {load: 'review_item_data'}
                            }
                        };
                        $.each(pages, function (i, v) {
                            console.info(window.location.hash.indexOf(i));
                            if (window.location.hash.indexOf(i) !== -1) {
                                if (typeof jQuery[v.set] === 'object') {
                                    console.info(2);
                                    if (typeof v.data === 'object') {
                                        $.each(v.data, function (i2, v2) {
                                            data[i2] = v2;
                                        });
                                    }
                                    console.info(data);
                                    if (typeof jQuery[v.set][v.func] === 'function') {
                                        jQuery[v.set][v.func](data);
                                    } else if (typeof v.subset === 'string' && typeof jQuery[v.set][v.subset] === 'object' && typeof jQuery[v.set][v.subset][v.func] === 'function') {
                                        jQuery[v.set][v.subset][v.func](data);
                                    }
                                }
                                return false;
                            }
                        });
                    } else {
                        jQuery.core.search(data);
                    }
                }
            });
        },
        reviews_data: function (inDATA) {
            console.log({function: 'load.reviews_data', inDATA: inDATA});
            var tbody_unreviewed = String('');
            var tbody_reviewed = String('');
            $.each(inDATA.results, function (i, v) {
                var btn_text = 'Review Now';
                if (v.reviewed === 1) {
                    btn_text = 'Reviewed';
                }
                var row = '<tr>';
                row += '<td>' + v.downloaded_on + '</td>';
                row += '<td>' + v.title + '</td>';
                row += '<td>';
                row += '<button ';
                row += ' data-mini="true" ';
                row += ' class="btn_write_review ui-btn ui-shadow ui-corner-all ui-mini" ';
                row += ' data-item_id="' + v.item_id + '" ';
                row += '>';
                row += btn_text;
                row += '</button>';
                row += '</td>';
                row += '</tr>';
                if (v.reviewed === 1) {
                    tbody_reviewed += row;
                } else {
                    tbody_unreviewed += row;
                }
            });
            $('#tbl_unreviewed tbody').html(tbody_unreviewed);
            $('#tbl_reviewed tbody').html(tbody_reviewed);
            $('.btn_write_review').on({
                tap: function () {
                    $('#panel_left').panel('close');
                    jQuery.core.reset({page: 'review_item'});
                    jQuery.get.item({item_id: $(this).data('item_id'), load: 'review_item_data'});
                }
            });
            jQuery.core.navigate({page: '#my_unreviewed'});
        },
        item_data: function (inDATA) {
            console.log({function: 'load.item_data', inDATA: inDATA});
            if (typeof inDATA === 'object' && typeof inDATA.item_id === 'number' && typeof inDATA.load === 'string') {
            }
        },
        review_item_data: function (inDATA) {
            console.log({function: 'load.review_item_data', inDATA: inDATA});
            jQuery.core.reset({page: 'review_item'});
            var items = {
                title: 'title',
                category: 'category',
                description: 'description',
                item_id: 'item_id',
                downloaded: 'downloaded_on'
            };
            $.each(items, function (i, v) {
                if (typeof inDATA.results[i] === 'string' || typeof inDATA.results[i] === 'number') {
                    $('#info_' + i).html(inDATA.results[i]);
                    $('#info_' + i).val(inDATA.results[i]);
                }
            });
            if (typeof inDATA === 'object' && typeof inDATA.results === 'object' && typeof inDATA.results.review === 'string') {
                $('#item_review').val(inDATA.results.review);
            }
            $('.readonly .ui-state-disabled').removeClass('ui-state-disabled');
            setTimeout(function () {
                $('.readonly .ui-state-disabled').removeClass('ui-state-disabled');
            }, 2000);
            var page = '#review_item';
            if (typeof inDATA.item_id === 'number') {
                $(page).data({item_id: inDATA.item_id});
            }
            jQuery.core.navigate({page: page});
        },
        my_plans: function () {
            console.log({function: 'load.my_plans'});
            if (typeof window.userDATA === 'object' && typeof window.userDATA.plans === 'number' && window.userDATA.plans > 0 && typeof window.userDATA.plans_data === 'object') {
                $('#tbl_my_plans tbody').html('');
                $.each(window.userDATA.plans_data, function (i, v) {
                    var row = String('');
                    row += '<tr class="item" data-item_id="' + v.item_id + '">';
                    row += '<td class="td_item_id">' + v.item_id + '</td>';
                    row += '<td class="td_title">' + v.title + '</td>';
                    row += '<td class="td_created_on">' + v.created_on + '</td>';
                    row += '<td class="td_actions">';
                    row += '<a class="btn_view_item " data-item_id="' + v.item_id + '"></a>';
                    row += '<a class="btn_edit_item  " data-item_id="' + v.item_id + '"></a>';
                    row += '</td>';
                    row += '</tr>';
                    $('#tbl_my_plans tbody').append(row);
                    $('#my_plans_no_plans').hide();
                    $('#tbl_my_plans').show();
                });
                $('.btn_view_item').button({
                    icon: 'eye',
                    inline: true,
                    corners: false,
                    iconpos: 'notext',
                    wrapperClass: 'btn_action'
                }).parent().on({
                    tap: function () {
                        var data = {
                            item_id: $(this).closest('tr').data('item_id'),
                            load: 'item_details'
                        };
                        if (typeof data.item_id === 'number') {
                            jQuery.get.item(data);
                        }
                    }
                });
                $('.btn_edit_item').button({
                    icon: 'edit',
                    inline: true,
                    corners: false,
                    iconpos: 'notext',
                    wrapperClass: 'btn_action'
                }).parent().on({
                    tap: function () {
                        var data = {
                            item_id: $(this).closest('tr').data('item_id'),
                            load: 'edit_plan'
                        };
                        if (typeof data.item_id === 'number') {
                            jQuery.get.item(data);
                        }
                    }
                });
                $(":mobile-pagecontainer").pagecontainer("change", '#my_plans');
                return true;
            }
            $(":mobile-pagecontainer").pagecontainer("change", '#home');
            console.error('load my_plans failed');
        }
    }
});
