/* 
 * Copyright © DCS Universe (dscuniverse.com) - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 */
$('document').ready(function () {
    //init_layout();
    //get_pageDATA();
  /*  $('#btn_add').button({
        label: 'Add Lesson Plan'
    }).on({
        click: function () {
            window.attached_files = [];
            init_dialog_item_details();
        }
    });
    $('#input_search').on({
        keydown: function (e) {
            if (e.keyCode === 13) {
                //console.info(e);
                do_search();
            }
        }
    });
*/
    //handle_url_data();
    //load_session_btns();
   // $('#input_search').focus();
});
function add_tag(inID) {
    var tag_id = $('#tag_autocomplete').attr('data-tag_id');
    if (typeof inID === 'string') {
        tag_id = inID;
    }
    //console.info(tag_id);
    if (typeof window.tags_by_id === 'object' && typeof window.tags_by_id[tag_id] === 'string') {
        var tag_name = window.tags_by_id[tag_id];
        var btn = $('<a></a>');
        $(btn).attr({id: 'btn_tag_' + tag_id, 'data-tag_id': tag_id, title: 'Click to remove.', class: 'addedTag'});
        $(btn).button({
            label: tag_name
        }).on({
            click: function () {
                remove_tag(tag_id);
            }
        });
        $('#tags_added').append(btn);
        $('#tag_autocomplete').val('').attr({'data-tag_id': ''});
        init_autocomplete_tags();
        validate_form_item_details({removeErrorOnly: true});
    } else {
        //console.error({error: 'tag_id not found', tag_id: tag_id});
    }
}
function do_search(inDATA) {
    var data = {};
    if ($('#input_search').val() !== '') {
        data['search'] = $('#input_search').val();
    }
    if (typeof inDATA === 'object' && typeof inDATA.category_id !== 'undefined') {
        data['category_id'] = inDATA.category_id;
    }
    if (typeof inDATA === 'object' && typeof inDATA.tag_id !== 'undefined') {
        data['tag_id'] = inDATA.tag_id;
    }
    if (typeof inDATA === 'object' && typeof inDATA.search !== 'undefined') {
        data['search'] = inDATA.search;
    }
    $('#input_search').val('');
    $.ajax({
        type: 'POST',
        url: '/lessonplans/Ajax/search',
        data: data,
        success: function (resp) {
            //console.info(resp);
            if (typeof window.pageDATA !== 'object') {
                window.pageDATA = {};
            }
            window.pageDATA.results = resp.result;
            //TODO:update address bar
            update_address_bar(data);
            load_results(0);
        },
        error: function (error) {
            //console.error(error);
        }
    });
}
function update_address_bar(inDATA) {
    console.info(inDATA);
}
function get_url_parameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null;
}
function get_file_name() {
    var files = [];
    $.each($('table[role="presentation"] p.name a'), function (i, v) {
        var name = $(v).attr('href').replace('/lessonplans/assets/uploads/files/', '');
        files.push(name);
    });
    console.info(files);

}
function get_options_categories() {
    var options = String('<option value="0">Select...</option>');
    $.each(window.pageDATA.categories, function (i, v) {
        options += '<option value="' + v.category_id + '">' + v.category + '</option>';
    });
    return options;
}
function get_options_tags() {
    var options = String('<option value="0">Select...</option>');
    $.each(window.pageDATA.tags, function (i, v) {
        options += '<option value="' + v.tag_id + '">' + v.tag + '</option>';
    });
    return options;
}
function get_pageDATA() {
    $.ajax({
        url: '/lessonplans/ajax/get_pageDATA',
        data: {},
        success: function (resp) {
            if (typeof resp === 'object' && typeof resp.status === 'string' && typeof resp.pageDATA === 'object' && resp.status === 'success') {
                window.pageDATA = resp.pageDATA;
                load_pageDATA(resp.pageDATA);
            } else {
                //console.error(error);
            }
        },
        error: function () {
            //console.error(error);
        }
    });
}
function handle_url_data() {
    var options = {
        'tag_id': 'do_search',
        'category_id': 'do_search',
        'item_id': '',
        'search': ''
    };
    $.each(options, function (i, v) {
        var value = get_url_parameter(i);
        if (typeof value === 'string') {
            console.info({name: i, value: value});
            var data = {};
            data[i] = value;
            do_search(data);
        }
    });
}
function init_autocomplete_tags() {
    var tags = [];
    var tags_by_id = {};
    var omits = [];
    $.each($('#tags_added a'), function (i, v) {
        var id = $(v).attr('data-tag_id');
        omits.push(id);
    });
    $.each(window.pageDATA.tags, function (i, v) {
        tags_by_id[v.tag_id] = v.tag;
        if (omits.indexOf(v.tag_id) === -1) {
            var tag = {value: v.tag, data: v.tag_id};
            tags.push(tag);
        }
    });
    window.tags_by_id = tags_by_id;
    $('#tag_autocomplete').autocomplete('dispose');
    $('#tag_autocomplete').off().on({
        keydown: function (e) {
            if (e.keyCode === 13) {
                if (parseInt($('#tag_autocomplete').attr('data-tag_id')) > 0) {
                    add_tag();
                }
            }
        }
    }).autocomplete({
        lookup: tags,
        onSelect: function (suggestion) {
            $('#tag_autocomplete').attr({'data-tag_id': suggestion.data}).focus();
            add_tag();
        },
        onSearchStart: function () {
            $('#tag_autocomplete').attr({'data-tag_id': ''});
        }
    });
}
function init_dialog_item_details() {
    $('#tbl_attached_files tbody tr').remove();
    $('#form_item_details p').html('');
    $('#form_item_details input,#form_item_details textarea,#form_item_details select').prop({'disabled': false}).removeClass('disabled');
    $('#tag_autocomplete').show();
    $('.dialog_item_details input,.dialog_item_details textarea').val('');
    $('form#form_item_details fieldset').removeClass('form-error');
    $('.dialog_item_details select').val(0);
    $('.dialog_item_details #tags_added').html('');
    $('#form_item_details input,#form_item_details textarea,#form_item_details select').off().on({
        change: function () {
            validate_form_item_details({removeErrorOnly: true});
        }
    });
    $('#category_select').html(get_options_categories());
    init_autocomplete_tags();
    $('#btn_upload_file').button({
        label: 'upload'
    }).on({
        click: function () {
            init_dialog_upload();
        }
    });

    $('#dialog_item_details').dialog({
        title: 'Add Lesson Plan',
        width: 800,
        dialogClass: 'dialog_item_details add',
        modal: true,
        buttons: [
            {
                text: "Cancel",
                icons: {
                    primary: "ui-icon-cancel"
                },
                click: function () {
                    $(this).dialog("close");
                }
            }, {
                text: "Save",
                icons: {
                    primary: "ui-icon-plus"
                },
                click: function () {
                    validate_form_item_details({save: true});
                }
            }
        ]
    });
}
function init_dialog_login() {
    $('#dialog_login').dialog('destroy').remove();
    $('#container_dialogs').append('<div id="dialog_login"></div>');
    var form = '<form id="form_login">';
    form += '<fieldset>';
    form += '<label>username</label>';
    form += '<input id="dl_username" name="username" type="text" />';
    form += '</fieldset>';
    form += '<fieldset>';
    form += '<label>password</label>';
    form += '<input id="dl_password" name="password" type="password" />';
    form += '</fieldset>';
    form += '</form>';
    $('#dialog_login').html(form).dialog({
        title: 'Login',
        width: 400,
        modal: true,
        buttons: [
            {
                text: "Cancel",
                icons: {
                    primary: "ui-icon-cancel"
                },
                click: function () {
                    $(this).dialog("close");
                }
            }, {
                text: "Login",
                icons: {
                    primary: "ui-icon-plus"
                },
                click: function () {
                    validate_form_login({login: true});
                }
            }
        ]
    });
    $('#dl_username,dl_password').on({
        keydown: function (e) {
            if (e.keyCode === 13) {
                validate_form_login({login: true});
            }
        }
    });
}
function init_dialog_logout() {
    $('#dialog_logout').dialog('destroy').remove();
    $('#container_dialogs').append('<div id="dialog_logout"></div>');
    var html = 'Are you sure you want to logout?';
    $('#dialog_logout').html(html).dialog({
        title: 'Logout',
        width: 400,
        modal: true,
        dialogClass: 'dialog_logout',
        buttons: [
            {
                text: "Cancel",
                icons: {primary: ""},
                click: function () {
                    $(this).dialog("close");
                }
            }, {
                text: "Logout",
                icons: {primary: ""},
                class: 'logout',
                click: function () {
                    post_logout();
                }
            }
        ]
    });
    $('.dialog_logout button.logout').focus();
}
function init_dialog_upload() {
    $('#dialog_upload').dialog({
        title: 'Upload Files',
        width: 800,
        modal: true,
    });

}
function init_dialog_user() {
    console.info({function: 'init_dialog_user'});
    $.ajax({
        type: 'POST',
        url: '/lessonplans/Auth/user_info',
        data: {},
        success: function (resp) {
            console.info(resp);
        },
        error: function (error) {
            console.error(error);
        }
    });
}
function init_layout() {
    $('body').layout({
        applyDefaultStyles: true,
        defaults: {
            fxName: "slide",
            fxSpeed: "slow",
            spacing_closed: 8,
            togglerLength_closed: "100%"
        },
        north: {
            minSize: '200px'
        },
        west: {
        },
        center: {
        },
        east: {
            initClosed: true,
            spacing_closed: 1
        },
        south: {
            initClosed: true,
            spacing_closed: 1
        }
    });
}
function load_results2(start) {
    if (typeof start !== 'number') {
        start = 0;
    }
    if (typeof window.pageDATA === 'object' && typeof window.pageDATA.results === 'object') {
        $('#tbl_results').remove();
        //console.info(window.pageDATA.results);
        var data = window.pageDATA.results;
        var perPage = 100;
        if (data.length < perPage) {
            perPage = data.length;
        }
        var results = '<div id="tbl_results">';
        // var results = '<table id="tbl_results">';
        //  for (i = 0; i < perPage; i++) {
        $.each(window.pageDATA.results, function (i3, v3) {
            var created = '';
            if (typeof v3.created_on === 'string') {
                created = v3.created_on.substring(0, 10);
            }
            //  results += '<tr data-item_id="' + data[i].item_id + '" class="result-item">';
            //  results += '  <td>';
            results += '    <div data-item_id="' + v3.item_id + '" class="result-item item-container">';
            results += '      <div class="item-title">';
            results += '        <div class="item-title-img">';
            results += '          <img src="/lessonplans/public/img/' + v3.category_image + '"/>';
            results += '        </div>';
            results += '        <div class="item-title-text">' + v3.title + '</div>';
            results += '      </div>';
            results += '      <div class="item-details">';
            results += '        <table class="tbl_details">';
            // results += '          <tr class="item-details-author"><td>Author</td><td>' + pageDATA.results[i].item_creator + '</td></tr>';
            //results += '          <tr class="item-details-category"><td>Category</td><td>' + pageDATA.results[i].category + '</td></tr>';
            //results += '          <tr class="item-details-tags"><td>Tags</td><td>' + '' + '</td></tr>';
            //results += '          <tr class="item-details-created"><td>Created</td><td>' + created + '</td></tr>';
            //results += '          <tr class="item-details-views"><td>Views</td><td>' + pageDATA.results[i].views + '</td></tr>';
            // results += '          <tr class="item-details-downloads"><td>Downloads</td><td>' + pageDATA.results[i].downloads + '</td></tr>';
            //results += '          <tr class="item-details-reviews"><td>reviews</td><td>' + 0 + '</td></tr>';
            var item = [
                {title: 'id', icon: 'icon_item_id.png', data: v3.item_id, class: 'item_id'},
                {title: 'Author', icon: 'icon_author.png', data: v3.item_creator, class: 'author'},
                {title: 'Category', icon: 'icon_category.png', data: v3.category, class: 'category'},
                {title: 'Tags', icon: 'icon_tag.png', data: '', class: 'tags'},
                {title: 'Created', icon: 'icon_date.png', data: created, class: 'created'},
                {title: v3.views + ' Views', icon: 'icon_eye.png', data: v3.views, class: 'views'},
                {title: v3.downloads + ' Downloads', icon: 'icon_download.png', data: v3.downloads, class: 'downloads'},
                {title: v3.reviews + ' Reviews', icon: 'icon_reviews.png', data: v3.reviews, class: 'reviews'},
                {title: 'Rating', icon: 'icon_rating.png', data: v3.rating, class: 'ratings'},
            ];
            $.each(item, function (i, v) {
                var value = v.data;
                if (v.title === 'Tags') {
                    var tags = '';
                    console.info();
                    $.each(v3.tags, function (i2, v2) {
                        tags += '<a class="tag" data-tag_id="' + v2.tag_id + '" href="#">' + v2.tag + '</a> ';
                    });
                    value = tags.trim();
                }
                results += '<tr title="' + v.title + '" class="item-details-' + v.class + '"><td><img src="/lessonplans/public/img/' + v.icon + '" /></td><td>' + value + '</td></tr>';
            });
            results += '      </table>';
            results += '      <div class="item-details-right">';
            results += '      <div class="item-details-subtitle">' + v3.subtitle + '</div>';
            results += '      <div class="item-details-description">' + v3.description + '</div>';
            results += '    </div>';
            results += '    </div>';
            results += '  </div>';
            // results += '</td>';
            // results += '<tr>';
        });
        // results += '</table>';
        results += '</div>';
        if (data.length === 0) {
            results = 'No results';
        }
        $('.ui-layout-pane-center').html(results);
        $('.item-details-right').on({
            click: function (e) {
                var item_id = $($(this).closest('div.result-item.item-container')).attr('data-item_id');
                load_item_details({item_id: item_id});
                console.info(item_id);
            }
        }).css({cursor: 'pointer'});
    }
    $('.tbl_details a.tag').button();
}
function load_results(start) {
    if (typeof start !== 'number') {
        start = 0;
    }
    if (typeof window.pageDATA === 'object' && typeof window.pageDATA.results === 'object') {
        $('#tbl_results').remove();
        //console.info(window.pageDATA.results);
        var data = window.pageDATA.results;
        var perPage = 100;
        if (data.length < perPage) {
            perPage = data.length;
        }
        var results = '<div id="results">';
        $.each(window.pageDATA.results, function (i3, v3) {
            var created = '';
            if (typeof v3.created_on === 'string') {
                created = v3.created_on.substring(0, 10);
            }
            results += '<div data-item_id="' + v3.item_id + '" class="result-item item-container">';
            results += '  <table>';
            results += '    <tr>';
            results += '        <td class="item-title-img">';
            results += '          <img src="/lessonplans/public/img/' + v3.category_image + '"/>';
            results += '        </td>';
            results += '        <td class="item-title-text">' + v3.title + '</td>';
            results += '      </tr>';
            results += '      </table>';
            /* results += '      <table class="tbl_item_stats">';
             
             
             var item = [
             {title: 'id', icon: 'icon_item_id.png', data: v3.item_id, class: 'item_id'},
             {title: 'Author', icon: 'icon_author.png', data: v3.item_creator, class: 'author'},
             {title: 'Category', icon: 'icon_category.png', data: v3.category, class: 'category'},
             // {title: 'Tags', icon: 'icon_tag.png', data: '', class: 'tags'},
             {title: 'Created', icon: 'icon_date.png', data: created, class: 'created'},
             // {title: v3.views + ' Views', icon: 'icon_eye.png', data: v3.views, class: 'views'},
             // {title: v3.downloads + ' Downloads', icon: 'icon_download.png', data: v3.downloads, class: 'downloads'},
             //  {title: v3.reviews + ' Reviews', icon: 'icon_reviews.png', data: v3.reviews, class: 'reviews'},
             // {title: 'Rating', icon: 'icon_rating.png', data: v3.rating, class: 'ratings'},
             ];
             results += '<tr>';
             $.each(item, function (i, v) {
             results += '<td class="td_icon"><img src="/lessonplans/public/img/' + v.icon + '" /></td>';
             });
             results += '</tr>';
             results += '<tr>';
             $.each(item, function (i, v) {
             results += '<td>'+v.data + '</td>';
             });
             results += '</tr>';
             
             results += '      </table>';
             //results += '      <div class="item-details-right">';
             //results += '      <div class="item-details-subtitle">' + v3.subtitle + '</div>';
             //results += '      <div class="item-details-description">' + v3.description + '</div>';
             //results += '    </div>';
             */

            var item = [
                //  {title: 'id', icon: 'icon_item_id.png', data: v3.item_id, class: 'item_id'},
                {title: 'cost', icon: 'icon_item_cost.png', data: v3.cost, class: 'item_cost'},
                {title: 'Author', icon: 'icon_author.png', data: v3.item_creator, class: 'author'},
                {title: 'Category', icon: 'icon_category.png', data: v3.category, class: 'category'},
                // {title: 'Tags', icon: 'icon_tag.png', data: '', class: 'tags'},
                {title: 'Created', icon: 'icon_date.png', data: created, class: 'created'},
            ];
            results += '<table class="tbl_item_detail">';
            $.each(item, function (i, v) {
                results += '<tr>';
                results += '<td class="td_icon"><img src="/lessonplans/public/img/' + v.icon + '" /></td>';
                results += '<td>' + v.data + '</td>';
                results += '</tr>';
            });
            results += '</table>';

            var item_stats = [
                {title: v3.views + ' Views', icon: 'icon_eye.png', data: v3.views, class: 'item_views'},
                {title: v3.downloads + ' Downloads', icon: 'icon_download.png', data: v3.downloads, class: 'item_downloads'},
                {title: v3.reviews + ' Reviews', icon: 'icon_reviews.png', data: v3.reviews, class: 'item_reviews'},
                {title: 'Rating', icon: 'icon_rating.png', data: v3.rating, class: 'item_ratings'},
            ];

            results += '<table class="tbl_item_stats">';
            $.each(item_stats, function (i, v) {
                results += '<tr>';
                results += '<td class="td_icon ' + v.class + '_icon"><img src="/lessonplans/public/img/' + v.icon + '" /></td>';
                results += '<td class="' + v.class + '">' + v.data + '</td>';
                results += '</tr>';
            });
            results += '</table>';
            results += '<div class="clear"></div>';
            results += '<button class="btn_buy">Buy</button>';
            results += '<button class="btn_item_detail">Details</button>';
            results += '  </div>';

        });
        results += '</div>';
        if (data.length === 0) {
            results = 'No results';
        }
        $('.ui-layout-pane-center').html(results);
        $('.item_views_icon,.item_views').on({
            click: function (e) {
                var item_id = $($(this).closest('div.result-item.item-container')).attr('data-item_id');
                load_item_details({item_id: item_id});
                console.info(item_id);
            }
        }).css({cursor: 'pointer'});
        $('.btn_buy').on({
            click: function (e) {
                var item_id = $($(this).closest('div.result-item.item-container')).attr('data-item_id');
                init_dialog_purchase({item_id: item_id});
            }
        }).css({cursor: 'pointer'});
        $('.btn_item_detail').on({
            click: function (e) {
                var item_id = $($(this).closest('div.result-item.item-container')).attr('data-item_id');
                load_item_details({item_id: item_id});
            }
        }).css({cursor: 'pointer'});
    }
    $('.tbl_details a.tag').button();
}
function init_dialog_purchase(inDATA) {
    console.info(inDATA);
    if (typeof inDATA === 'object' && typeof inDATA.item_id === 'string' && typeof window.pageDATA === 'object' && typeof window.pageDATA.results === 'object' && typeof window.pageDATA.results[inDATA.item_id] === 'object') {
        var itemDATA = window.pageDATA.results[inDATA.item_id];
        var dialog = '<div id="dialog_purchase">';
        dialog += 'Do you wish to purchase ' + itemDATA.title + ' for $' + itemDATA.cost + '?';
        dialog += '</div>';
        $('body').append(dialog);
        $('#dialog_purchase').dialog({
            title: 'Purchase',
            modal: true,
            buttons: [
                {
                    text: "Cancel",
                    icons: {
                        primary: "ui-icon-plus"
                    },
                    click: function () {
                       $(this).dialog('close');
                    }
                },{
                    text: "Purchase",
                    icons: {
                        primary: "ui-icon-plus"
                    },
                    click: function () {
                         purchase({item_id: inDATA.item_id,cost:inDATA.cost});
                    }
                }
                
            ]
        });
    }
}
function purchase(inDATA){
    console.info(inDATA);
    $.ajax({
        type:'POST',
        data:inDATA,
        url:'/lessonplans/ajax/purchase',
        success:function(resp){
         console.info(resp);   
        },
        error:function(error){
         console.error(error);   
        }
    });
}
function load_item_details(inDATA) {
    $('#form_item_details p').html('');
    $('#form_item_details input,#form_item_details textarea,#form_item_details select').prop({'disabled': false}).removeClass('disabled');
    $('#tag_autocomplete').show();
    $('.dialog_item_details input,.dialog_item_details textarea').val('');
    $('form#form_item_details fieldset').removeClass('form-error');
    $('.dialog_item_details select').val(0);
    $('.dialog_item_details #tags_added').html('');
    $.ajax({
        type: 'POST',
        url: '/lessonplans/ajax/get_item_details',
        data: {item_id: inDATA.item_id},
        success: function (resp) {
            if (typeof resp === 'object' && typeof resp.status === 'string' && resp.status === 'success') {
                window.item_details = resp.result;
                init_autocomplete_tags();
                $('#creator_user_id').val(resp.result.creator_user_id);
                $('#item_id').val(resp.result.item_id);
                $('#view_title').html(resp.result.title);
                $('#subtitle').val(resp.result.subtitle);
                $('#view_subtitle').html(resp.result.subtitle);
                $('#description').val(resp.result.description);
                $('#view_description').html(resp.result.description);
                $('#category_select').html(get_options_categories());
                $('#category_select').val(resp.result.category_id);
                $('#view_category').html(resp.result.category);
                $('#img_category').attr({src: '/lessonplans/public/img/' + resp.result.category_image});
                //console.warn(resp.result.category);
                $.each(resp.result.tags, function (i, v) {
                    add_tag(v.tag_id);
                });
                //$('#view_filename').html(resp.result.file_name);
                var files = [];
                if (typeof resp.result.files === 'object') {
                    $.each(resp.result.files, function (i, v) {
                        files.push(v);
                    });
                }
                window.attached_files = files;
                update_attached_items_table();
                var buttons = [
                    {
                        text: "Close",
                        icons: {
                            primary: "ui-icon-cancel"
                        },
                        click: function () {
                            $(this).dialog("close");
                        }
                    }
                ];
                if (typeof inDATA.editable === 'boolean' && inDATA.editable === true) {
                    buttons.push({
                        text: "Save",
                        icons: {
                            primary: "ui-icon-plus"
                        },
                        click: function () {
                            validate_form_item_details({save: true});
                        }
                    });
                } else {
                    $('#form_item_details input,#form_item_details textarea,#form_item_details select').prop({'disabled': true}).addClass('disabled');
                    $('#tag_autocomplete').hide();
                }

                $('#tbl_attached_files tr').on({
                    click: function () {
                        var filename = $(this).attr('data-filename');
                        var path = 'http://dcsuniverse.com/lessonplans/assets/uploads/files/';
                        var fullname = path + filename;
                        //  window.location=fullname;
                        console.info(fullname);
                        $.ajax({
                            type: 'GET',
                            url: '/lessonplans/download',
                            data: {filename: filename}
                        });
                    }
                });
                $('#dialog_item_details').dialog({
                    title: 'Lesson Plan Details',
                    width: 800,
                    dialogClass: 'dialog_item_details disabled',
                    modal: true,
                    buttons: buttons
                });
                $('.ui-dialog-buttonset button,.btn_download_file').blur();
            } else {
                //console.error(error);
            }
        },
        error: function () {
            //console.error(error);
        }
    });
}
function load_pageDATA(data) {
    if (typeof data === 'object' && typeof data.tagCounts === 'object') {
        var tbody = '';
        $.each(data.tagCounts, function (i, v) {
            tbody += '<p class="tag" data-id="' + v.tag_id + '" data-name="' + v.tag + '">' + v.tag + ' <span class="tag_total">(' + v.qty + ')<span>' + '</p>';
        });
        $('#container-top_tags div.data').html(tbody);
        $('p.tag').on({
            click: function (e) {
                var id = $(this).attr('data-id');
                //console.info(e);
                do_search({'tag_id': id});
            }
        });
        var tbody = '';
        $.each(data.categoryCounts, function (i, v) {
            tbody += '<p class="category" data-id="' + v.category_id + '" data-name="' + v.category + '">' + v.category + ' <span class="category_total">(' + v.qty + ')<span>' + '</p>';
        });
        $('#container-top_categories div.data').html(tbody);
        $('p.category').on({
            click: function (e) {
                var id = $(this).attr('data-id');
                //console.info(e);
                do_search({'category_id': id});
            }
        });
    }
}
function parse_upload_results(inDATA) {
    console.info(inDATA);
    var files = [];
    if (typeof window.attached_files === 'object') {
        files = window.attached_files;
    }
    if (typeof inDATA === 'object') {
        $.each(inDATA, function (i, v) {
            var file = {
                name: v.name,
                name_safe: v.url.replace('/lessonplans/assets/uploads/files/', ''),
                size: v.size,
                type: v.type,
                file_id: v.file_id
            };
            files.push(file);
        });
    }
    window.attached_files = files;
    update_attached_items_table();
}
function post_item_data(inDATA) {
    console.info(inDATA);
    $.ajax({
        type: 'POST',
        url: '/lessonplans/Ajax/post_item_details',
        data: inDATA,
        success: function (resp) {
            console.info(resp);
            if (typeof resp === 'object' && typeof resp.status === 'string' && resp.status === 'success') {
                $('#dialog_item_details').dialog('close');
                alert_message('Lesson plan saved successfully.');
            }
        },
        error: function (error) {
            console.error(error);
            alert_message(error);
        }
    });

}
function load_session_btns() {
    $('#btn_user,#btn_logout,#btn_login').remove();
    $('#btn_grp_session').html('');
    var state = $('body').attr('data-state');
    var username = $('body').attr('data-username');
    var btns = '<a id="btn_login" href="JAVASCRIPT:init_dialog_login();">Login</a>';
    if (state === 'valid') {
        btns = '<a id="btn_user" href="JAVASCRIPT:init_dialog_user();">My Profile</a>';
        btns += "<a id='btn_logout' href='JAVASCRIPT:init_dialog_logout();'>Logout</a>";
    }
    $('#btn_grp_session').html(btns);
    $('#btn_user').button({
        label: 'My Profile'
    });
    $('#btn_logout').button({
        label: 'Logout'
    });
    $('#btn_login').button({
        label: 'Login'
    });
}
function post_login() {
    var data = {
        username: $('#dl_username').val(),
        password: md5($('#dl_password').val())
    };
    $.ajax({
        type: 'POST',
        url: '/lessonplans/Auth/login',
        data: data,
        success: function (resp) {
            console.info(resp);
            if (typeof resp === 'object' && typeof resp.status === 'string' && resp.status === 'success') {
                $('#dialog_login').dialog("close");
                $('body').attr({'data-state': 'valid', 'data-username': resp.username, 'data-user_id': resp.user_id});
                load_session_btns();
            }
        },
        error: function (error) {
            console.error(error);
        }
    });

}
function post_logout() {
    $.ajax({
        type: 'POST',
        url: '/lessonplans/Auth/logout',
        data: {},
        success: function (resp) {
            console.info(resp);
            console.info({0: typeof resp, 1: typeof resp.status});
            if (typeof resp === 'object' && typeof resp.status === 'string' && resp.status === 'success') {
                $('#dialog_logout').dialog("close");
                $('body').attr({'data-state': '', 'data-username': '', 'data-user_id': ''});
                load_session_btns();
            }

        },
        error: function (error) {
            console.error(error);
        }
    });

}
function remove_tag(tag_id) {
    if ($('.dialog_item_details.disabled').length === 0) {
        $('#tags_added a[data-tag_id="' + tag_id + '"]').remove();
        init_autocomplete_tags();
    }
}
function remove_file(file_id) {
    console.info({function: 'remove_file', file_id: file_id});
    $('#tbl_attached_files tr[data-file_id="' + file_id + '"]').remove();
    var files = [];
    $.each(window.attached_files, function (i, v) {
        console.info(v);
        if (v.file_id !== file_id) {
            files.push(v);
        }
    });
    window.attached_files = files;

}
function update_attached_items_table() {
    var tbody = '';
    $.each(window.attached_files, function (i, v) {
        tbody += '<tr data-file_id="' + v.file_id + '" data-filename="' + v.name + '">';
        tbody += '  <td>' + v.name + '</td>';
        tbody += '  <td>' + v.type + '</td>';
        tbody += '  <td>';
        tbody += '    <a class="btn_remove_file"></a>';
        tbody += '    <a class="btn_download_file" href="/lessonplans/download?filename=' + v.name + '&file_id=' + v.file_id + '"></a>';
        tbody += '  </td>';
        tbody += '</tr>';
    });
    $('#tbl_attached_files tbody').html(tbody);
    $('a.btn_remove_file').button({
        label: 'Remove'
    }).on({
        click: function () {
            console.info(this);
            var file_id = $($(this).closest('tr')).attr('data-file_id');
            remove_file(file_id);
        }
    });
    $('a.btn_download_file').button({
        label: 'Download'
    }).on({
        click: function () {
            console.info(this);
            var file_id = $($(this).closest('tr')).attr('data-file_id');
        }
    });
}
function validate_form_item_details(inDATA) {
    //console.info({function: 'validate_form_item_details'});
    var requireds = [
        {
            id: 'title',
            name: 'title',
            min: 5,
            type: 'input'
        }, {
            id: 'subtitle',
            name: 'subtitle',
            min: 10,
            type: 'input'
        }, {
            id: 'description',
            name: 'description',
            min: 20,
            type: 'textarea'
        }, {
            id: 'category_select',
            name: 'category_id',
            min: 1,
            type: 'select'
        }
    ];
    var data = {};
    var errors = [];
    $.each(requireds, function (i, v) {
        var value = '';
        if (['input', 'textarea', 'select'].indexOf(v.type) !== -1) {
            value = $('#' + v.id).val();
            if (value === '0') {
                value = '';
            }
        }
        //console.info(value);
        if (value.length >= v.min) {
            data[v.name] = value;
            $($('#' + v.id).closest('fieldset')).removeClass('form-error');
        } else {
            if (typeof inDATA === 'undefined' || typeof inDATA.removeErrorOnly === 'undefined' || inDATA.removeErrorOnly !== true) {
                $($('#' + v.id).closest('fieldset')).addClass('form-error');
                errors.push({error: 'min not met.', id: v.id});
            }
        }
    });
    var tags = [];
    $.each($('#tags_added a'), function (i, v) {
        var id = $(v).attr('data-tag_id');
        tags.push(id);
    });
    if (tags.length > 0) {
        data['tags'] = tags;
        $($('#btn_add_tag').closest('fieldset')).removeClass('form-error');
    } else {
        if (typeof inDATA === 'undefined' || typeof inDATA.removeErrorOnly === 'undefined' || inDATA.removeErrorOnly !== true) {
            $($('#btn_add_tag').closest('fieldset')).addClass('form-error');
            errors.push({error: 'min not met.', id: 'tags_added'});
        }
    }
    if (typeof window.attached_files === 'object') {
        var ids = [];
        $.each(window.attached_files, function (i, v) {
            ids.push(v.file_id)
        });
        data['files'] = ids;
    }
    if (errors.length === 0) {
        //console.info('form is valid');
        if (typeof inDATA === 'object' && typeof inDATA.save === 'boolean' && inDATA.save === true) {
            post_item_data(data);
        }

    } else {
        //console.error(errors);
    }
    //console.info(data);
}
function validate_form_login(inDATA) {
    console.info({function: 'validate_form_login'});

    if (typeof inDATA === 'object' && typeof inDATA.login === 'boolean' && inDATA.login === true) {
        post_login();
    }
}

function alert_message(text) {
    $.noty.defaults = {
        layout: 'centerRight',
        theme: 'relax', // or 'relax'
        type: 'warning',
        // text: '', // can be html or string
        dismissQueue: true, // If you want to use queue feature set this true
        template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
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
        killer: false, // for close all notifications before show
        closeWith: ['click'], // ['click', 'button', 'hover', 'backdrop'] // backdrop click will close all notifications
        callback: {
            onShow: function () {
                playSound('alert');
            },
            afterShow: function () {
            },
            onClose: function () {
            },
            afterClose: function () {
            },
            onCloseClick: function () {
            },
        },
        buttons: false // an array of buttons
    };
    var n = noty({text: text, });
}
function playSound(name) {
    console.info(name);
    var mp3s = {
        'alert': '/lessonplans/public/sounds/sounds-913-served.mp3',
    };
    if (typeof mp3s[name] !== 'undefined') {
        var audio = new Audio(mp3s[name]);
        audio.play();
    }
}