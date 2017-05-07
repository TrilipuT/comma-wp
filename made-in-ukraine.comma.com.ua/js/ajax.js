jQuery.fn.exists = function() {
    return $(this).length;
}
function go2Page(url) {

    document.location = url;
    return true;
}

function isEmailCorrect(string) {

    var re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    if(re.test(string))
        return true;


    return false;
}





function getMessage(key){

    var value = '';
    for(var i=0; i<transferArray.length; i++){

        //console.log(transferArray[i].name, key);
        if(transferArray[i].name == key){
            value = transferArray[i].value;
            break;
        }
    }

    //console.log(value);
    return value;
}

function unserialize (data) {
    // http://kevin.vanzonneveld.net
    // +     original by: Arpad Ray (mailto:arpad@php.net)
    // +     improved by: Pedro Tainha (http://www.pedrotainha.com)
    // +     bugfixed by: dptr1988
    // +      revised by: d3x
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +        input by: Brett Zamir (http://brett-zamir.me)
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: Chris
    // +     improved by: James
    // +        input by: Martin (http://www.erlenwiese.de/)
    // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: Le Torbi
    // +     input by: kilops
    // +     bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Jaroslaw Czarniak
    // +     improved by: Eli Skeggs
    // %            note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %            note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *       example 1: unserialize('a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}');
    // *       returns 1: ['Kevin', 'van', 'Zonneveld']
    // *       example 2: unserialize('a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}');
    // *       returns 2: {firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'}
    var that = this,
        utf8Overhead = function (chr) {
            // http://phpjs.org/functions/unserialize:571#comment_95906
            var code = chr.charCodeAt(0);
            if (code < 0x0080) {
                return 0;
            }
            if (code < 0x0800) {
                return 1;
            }
            return 2;
        },
        error = function (type, msg, filename, line) {
            throw new that.window[type](msg, filename, line);
        },
        read_until = function (data, offset, stopchr) {
            var i = 2, buf = [], chr = data.slice(offset, offset + 1);

            while (chr != stopchr) {
                if ((i + offset) > data.length) {
                    error('Error', 'Invalid');
                }
                buf.push(chr);
                chr = data.slice(offset + (i - 1), offset + i);
                i += 1;
            }
            return [buf.length, buf.join('')];
        },
        read_chrs = function (data, offset, length) {
            var i, chr, buf;

            buf = [];
            for (i = 0; i < length; i++) {
                chr = data.slice(offset + (i - 1), offset + i);
                buf.push(chr);
                length -= utf8Overhead(chr);
            }
            return [buf.length, buf.join('')];
        },
        _unserialize = function (data, offset) {
            var dtype, dataoffset, keyandchrs, keys, contig,
                length, array, readdata, readData, ccount,
                stringlength, i, key, kprops, kchrs, vprops,
                vchrs, value, chrs = 0,
                typeconvert = function (x) {
                    return x;
                };

            if (!offset) {
                offset = 0;
            }
            dtype = (data.slice(offset, offset + 1)).toLowerCase();

            dataoffset = offset + 2;

            switch (dtype) {
                case 'i':
                    typeconvert = function (x) {
                        return parseInt(x, 10);
                    };
                    readData = read_until(data, dataoffset, ';');
                    chrs = readData[0];
                    readdata = readData[1];
                    dataoffset += chrs + 1;
                    break;
                case 'b':
                    typeconvert = function (x) {
                        return parseInt(x, 10) !== 0;
                    };
                    readData = read_until(data, dataoffset, ';');
                    chrs = readData[0];
                    readdata = readData[1];
                    dataoffset += chrs + 1;
                    break;
                case 'd':
                    typeconvert = function (x) {
                        return parseFloat(x);
                    };
                    readData = read_until(data, dataoffset, ';');
                    chrs = readData[0];
                    readdata = readData[1];
                    dataoffset += chrs + 1;
                    break;
                case 'n':
                    readdata = null;
                    break;
                case 's':
                    ccount = read_until(data, dataoffset, ':');
                    chrs = ccount[0];
                    stringlength = ccount[1];
                    dataoffset += chrs + 2;

                    readData = read_chrs(data, dataoffset + 1, parseInt(stringlength, 10));
                    chrs = readData[0];
                    readdata = readData[1];
                    dataoffset += chrs + 2;
                    if (chrs != parseInt(stringlength, 10) && chrs != readdata.length) {
                        error('SyntaxError', 'String length mismatch');
                    }
                    break;
                case 'a':
                    readdata = {};

                    keyandchrs = read_until(data, dataoffset, ':');
                    chrs = keyandchrs[0];
                    keys = keyandchrs[1];
                    dataoffset += chrs + 2;

                    length = parseInt(keys, 10);
                    contig = true;

                    for (i = 0; i < length; i++) {
                        kprops = _unserialize(data, dataoffset);
                        kchrs = kprops[1];
                        key = kprops[2];
                        dataoffset += kchrs;

                        vprops = _unserialize(data, dataoffset);
                        vchrs = vprops[1];
                        value = vprops[2];
                        dataoffset += vchrs;

                        if (key !== i)
                            contig = false;

                        readdata[key] = value;
                    }

                    if (contig) {
                        array = new Array(length);
                        for (i = 0; i < length; i++)
                            array[i] = readdata[i];
                        readdata = array;
                    }

                    dataoffset += 1;
                    break;
                default:
                    error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype);
                    break;
            }
            return [dtype, dataoffset - offset, typeconvert(readdata)];
        }
        ;

    return _unserialize((data + ''), 0)[2];
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------
function resizeHeaderText(){
    var block = $('.header-name');
    if(block.length){

        var parentDiv = $('#name-parent');

        if(block.width() > parentDiv.width()-40){

            var fontSize = parseInt(parentDiv.css('font-size'));
            parentDiv.css({'font-size': (fontSize-2) });

            console.log(parseInt(parentDiv.css('font-size')), block.width(), parentDiv.width());
            return resizeHeaderText();

        } else {

            return false;
        }



    }
}

$(document).ready(function(){
    resizeHeaderText();
    setTimeout(resizeHeaderText(),2000);

    //liks
    /*
     if($('.like')){
     $('.like').socialButton();
     //$.scrollToButton('hash', 1000);
     }*/


    return true;
});
//-------------------
//popups

function popupClose(){
    $('.popup').removeClass('opened');
    $('.popup').find('#user_login').hide();
    $('.popup').find('#message').hide();
}

function popupLoginOpen(){
    $('.popup').addClass('opened');
    $('.popup').find('#user_login').show();
    $('.popup').find('#message').hide();
}

function popupMessageOpen(text){
    $('.popup').addClass('opened');
    $('.popup').find('#user_login').hide();
    $('.popup').find('#message').text(text).show();
}

// комментарии   ----------------------------------------------------------------------------------------------------------------

function changeRate(type, thiz){

    var data_id = $(thiz).attr('data-id')*1;

    if(data_id <= 0) return false;

    $.ajax({
        type    : "POST",
        dataType: 'json',
        url     : "/ajax/changeRate/",
        data: ({lang        : lang,
            type        : type,
            data_id     : data_id
        }),
        success: function(data){ //rating_negative

            if(data.success == 1){

                $(thiz).closest('.item-rating').find('span').text(data.data);
                var item = $(thiz).closest('.item-inner');
                if(data.data <= -15){
                    if(item.hasClass('positive-text')){
                        item.removeClass('positive-text').addClass('negative-text');
                    }
                } else {
                    if(item.hasClass('negative-text')){
                        item.removeClass('negative-text').addClass('positive-text');
                    }
                }
                //-------------------------------------------------
                if(data.data < 0){
                    if(item.hasClass('positive')){
                        item.removeClass('positive').addClass('negative');
                    }
                } else {
                    if(item.hasClass('negative')){
                        item.removeClass('negative').addClass('positive');
                    }
                }


                //console.log(data.data > -15);

            } else if(data.message == 'user_not_login'){
                popupLoginOpen();
            }
        }
    }); // end ajax
}

$(document).on('click', '.comment-answer', function(){

    $('.comments-container .item.item_add').remove();

    var html = $('.comments-content .item.item_add').html();
    var id   = $(this).attr('data-id');

    var item = $(this).closest('.item');
    item.after('<div class="item item_add">'+html+'</div>');

    var comment_area_btn = item.next().find('.add-comment');
    comment_area_btn.attr('parent', id);
    comment_area_btn.addClass('anwser');
    comment_area_btn.prev().focus();

    return false;
});


function toCommentBlock(thiz){

    var addcommentform = $('.addcommentform');

    if(addcommentform.length){

        var fown_position  = addcommentform.offset();
        var this_position  = thiz.offset();

        addcommentform.find('.add-comment').attr('parent', thiz.attr('data-id'));
        $('body,html').animate({scrollTop: fown_position.top-300}, 400);

        addcommentform.find('textarea').focus();

    } else {
        popupLoginOpen();
    }
}

$(document).on('click', '.add-comment', function(){

    var thiz    = $(this);
    var parent  = thiz.attr('parent');
    var type    = thiz.attr('type');
    var data_id = thiz.attr('data-id');

    var text    = thiz.closest('.item-form').find('textarea').val();
    text = $.trim(text);

    if(text == ""){
        return false;
    }

    $.ajax({
        type    : "POST",
        dataType: 'json',
        url     : "/ajax/addComment/",
        data: ({lang        : lang,
            parent      : parent,
            text        : text,
            type        : type,
            data_id     : data_id
        }),
        success: function(data){

            if(data.success == 1){
                $('.item-form').find('textarea').val('');
                $('.comments-content .comments-container').html(data.html);

                $('.text_count_comments').html(data.count_comments);
            }

            if(data.message == 'user_not_login') {

                popupLoginOpen();
                //popupMessageOpen(data.message);
            }

            if(thiz.hasClass('.anwser')){
                thiz.closest('.item.item_add').remove();
            }

        }
    }); // end ajax
});
//end comment

$(document).on('click', '.login_popup',function(){
    popupLoginOpen();
    return false;
});

$(document).on('click', '.popup-close',function(){

    var item = $(this).closest('popup');
    if(item.hasClass('user_deactive')){
        item.remove();
    }

    popupClose();
    return false;
});

//закрытие по области в не попапа
$(document).on('click.popup', ".popup.opened", function(e) {

    if($(e.target).hasClass('popup-center')){

        var item = $(e.target).closest('popup');
        if(item.hasClass('user_deactive')){
            item.remove();
        }

        popupClose();
        return false;
    }
});

//закрытие по области в не попапа2
$(document).on('click.popup2', ".popup.user_deactive", function(e) {

    if($(e.target).hasClass('popup-center')){
        var item = $(e.target).closest('.popup');
        if(item.hasClass('user_deactive')){
            item.remove();
        }
    }
    return false;
});

function userLogOut(){
    $.ajax({
        type    : "POST",
        dataType: 'json',
        url     : '/'+lang+"/ajax/userLogOut/",
        success: function(data){

            if(data.success == 1){
                location.reload();
            }  else {
                popupMessageOpen(data.message);
            }

        }
    }); // end ajax
    return false;
}
//-----------------------------------
$(document).on('click', '.js-video-pagination-more a', function(){

    var paginator = $(this).closest('.pagination');

    var href      = $(this).attr('href');
    var page      = $(this).attr('page')*1;
                    $(this).attr('page', page+1);
    var offset    = $(this).attr('offset')*1;




    $.ajax({
        //  type    : "POST",
        dataType: 'json',
        url     : href,
        data    : ({page  : page+1,
            offset: offset}),
        success: function(data){

            if(data.success == 1){
                paginator.closest('.videos').find('.articles.container_for_grid').append(data.html);
            }

            if(data.remains <= 0){
                paginator.remove();
            }

            if($('.videos').length == 1){
                $('.pagination-pages a').removeClass('selected');
                $('.pagination-pages a').eq(page+1).addClass('selected');
            }
        }
    }); // end ajax
    return false;
});
//------------------------------------
$(document).on('click', '.js-pagination-more a', function(){

    var self = $(this);
    var type = '';
    var page = self.parent().attr('page')*1;
    //var page = $(this).closest('.pagination').find('.pagination-pages .selected').text()*1;
    var flip = 0;

    var container = $('.articles.articles_wide');
    if(!container.length){ // новости
        container = $('.news.news_center');
        if(!container.length){
            container = $('.photos');
            type = 'photos';
        } else {
            type = 'news';
        }
    } else {
        type = 'article';
        flip = container.find('.item:last-child').attr('data-flip');
    }
 
    $.ajax({
        //  type    : "POST",
        dataType: 'json',
        url     : document.location.origin + document.location.pathname,
        data    : ({page : page+1, flip : flip}),
        success: function(data){
            if(data.success == 1){

                if(type == 'article' || type == 'photos'){
                    container.append(data.html);
                } else if(type == 'news') {

                    var last_item = container.find('.news-block:last-child');
                    if(last_item.length){
                        var news_data = last_item.attr('data');

                        //console.log(news_data);
                        var tmp = $('#news_hide_block').html(data.html);
                        var last_item2 = tmp.find('.news-block:first-child');
                        if(last_item2.length){
                            var data2 = last_item2.attr('data');
                            //console.log(data2);

                            if(news_data == data2){
                                var items = last_item2.find('.item');
                                last_item.append(items);
                            }
                            last_item2.remove();

                            container.append($('#news_hide_block .news-block'));
                            $('#news_hide_block').html();
                        }
                    }
                }
            }

            if(data.remains <= 0){
                $('.pagination-more').remove();
            }

            $('.pagination-pages a').removeClass('selected');
            var current_page = $('.pagination-pages a').not('.next, .prev').eq(page);
            if(current_page.text() == (page + 1)){
                current_page.addClass('selected');
            }

            self.parent().attr('page', (page + 1));
        }
    }); // end ajax
    return false;
});


$(document).on('click', '.js-main-remains a', function(){

    var paginator = $(this).closest('.pagination .pagination-more');


    var container = $('.grid_3of4.js-main-rows-block');
    var r_num     = container.find('.clearfix:last-child').attr('data-id')*1;
    if(r_num == 0){
        r_num = 1;
    } else {
        r_num = 0;
    }

    var href      = $(this).attr('href');
    var page      = $(this).closest('.pagination').find('.pagination-pages .selected').text()*1;
    var rows      = container.find('.clearfix').length;

    $.ajax({
        //  type    : "POST",
        dataType: 'json',
        url   : href,
        data  : ({page  : page+1,
        r_num : r_num,
        rows  : rows}),
        success: function(data){

            if(data.success == 1){
                container.append(data.html_center);
                container.closest('.articles').find('.grid_1of4.grid_last').append(data.html_right);

                if(data.vidoes.length){
                    $.each(data.vidoes, function(){

                        var block =$('.js-video-main-container .'+this.cat);
                        block.find('img').attr('src',this.video);
                        block.find('.item-title').text(this.title);
                        block.find('.item-text').text(this.text);
                    });
                }

            }

            if(data.remains <= 0){
                paginator.remove();
            }

            $('.pagination-pages a').removeClass('selected');
            $('.pagination-pages a').eq(page+1).addClass('selected');

        }
    }); // end ajax
    return false;
});
