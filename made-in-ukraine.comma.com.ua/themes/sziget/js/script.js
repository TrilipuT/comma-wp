$(document).ready(function() {

    $(window).resize(function() {
        var depth;
        depth = $(window).height();
        $('.video_block').css('height', depth);

        var video;
        video = $(window).height();
        $('.mbYTP_wrapper').css('height', video);

    });

    var depth;
    depth = $(window).height();
    $('.video_block').css('height', depth);

    var video;
    video = $(window).height();
    $('.mbYTP_wrapper').css('height', video);

    if ($('.fancybox-media').length) {
        $('.fancybox-media').fancybox({
            openEffect  : 'none',
            closeEffect : 'none',
            minWidth: 853,
            minHeight: 480,
            maxWidth: 853,
            maxHeight: 480,
            helpers : {
                media : {}
            }
        });
    }

    $('.close_popup a').click(function() {
        $('.juri_description').hide();
        $('.juri_content').fadeIn(400);

        $('.juri_description .img_popup img').attr('src', '/images/blank.gif');
        $('.juri_description .name_juri').html('');
        $('.juri_description .popup_des').html('');


        return false;
    });

    if ($('.fancybox').length) {
        $('.fancybox').fancybox({});
    }

    if ($('.fancy_shur').length) {
        $('.fancy_shur').fancybox({
            maxWidth: 500,
            minWidth: 500,
            scrolling: 'no'
        });
    }

    /*
    if ($(".scroll_popup").length) {
        $(".scroll_popup").mCustomScrollbar({});
    }
    */

    /*
    $('.memb_click a').click(function(e) {
        e.stopPropagation();
    });
    */

    $('.hover_bottom a').click(function() {
        $('html, body').animate({ scrollTop: $('.about_block').offset().top }, 400);
        return false;
    });

    if ($('.fancy_gallery').length) {
        var slider;
        $('.fancy_gallery').fancybox({
            openEffect: 'none',
            closeEffect: 'none',
            afterLoad: function() {
                setTimeout(function() {
                    slider = $('#bxslider').bxSlider({
                        pager: false
                    });
                    $('.slider_popup ul li img').click(function() {
                        slider.goToNextSlide();
                    });
                }, 100);
            },
            beforeClose: function() {
                slider.destroySlider();
            }
        });
    }

});

$(window).load(function() {


});

function vk_init(){
    if(!$('.vk_share_button').length){
        return;
    }

    $.each($('.vk_share_button'), function(){
        var item = $(this);

        item.html(VK.Share.button(item.data('href'), {type: 'button', text : item.data('text')}));

        //$('#vk_share_button').html(VK.Share.button(location.origin + href, {type: 'button', text: 'Подiлитися'}));
    });
}

$(document).on('click', '.memb_click', function(e) {
    if($(e.target).closest('.sharing_memb').length){
        return;
    }

    var href = $(this).data('href');

    $(".scroll_popup").mCustomScrollbar("destroy");

    $.ajax({
        dataType: 'json',
        url: href,
        success: function(data) {
            $('#fancy_memb .des_memb').html(data.html);

            $.fancybox({
                maxWidth: 800,
                minWidth: 500,
                href: '#fancy_memb'
            });

            try{
                FB.XFBML.parse();
            }catch(ex){ console.log(ex);}

            vk_init();

            $(".scroll_popup").mCustomScrollbar({});
        }
    }); // end ajax
});


function getBodyScrollTop(){
    return self.pageYOffset || (document.documentElement && document.documentElement.scrollTop) || (document.body && document.body.scrollTop);
}

function getBodyScrollLeft(){
    return self.pageXOffset || (document.documentElement && document.documentElement.scrollLeft) || (document.body && document.body.scrollLeft);
}

function clientWidth(){ // Ширина окна просмотра
    return document.documentElement.clientWidth == 0 ? document.body.clientWidth : document.documentElement.clientWidth;
}

function clientHeight(){ // Высота окна просмотра
    return window.innerHeight;
}

$( window ).scroll(function() {

    if($('.participants_box').length){
        var c_top     = $('.participants_box').offset().top;
        var c_height  = $('.participants_box').height();
        var loader    = $('.more.load_content');

        if (loader.length && !loader.hasClass('active') && (getBodyScrollTop()+clientHeight()) > (c_top+c_height)){
            var page = loader.find('.more_items').attr('page')*1;

            loader.addClass('active');

            $.ajax({
                dataType: 'json',
                url     : '/members/',
                data: ({page : page + 1}),
                success: function(data){

                    if(data.success == 1){

                        $('.more.load_content .more_items').attr('page', page+1);

                        if(data.remains <= 0){
                            $('.more.load_content').remove();
                        }

                         $('.participants_box .members_list').append(data.html);

                        try{
                            FB.XFBML.parse();
                        }catch(ex){ console.log(ex);}

                        vk_init();

                        loader.removeClass('active');
                    }
                }
            }); // end ajax
            return false;
        }
    }
});

$(document).on('click', '.add_vote_submit', function(){
    var self = $(this);
    self.attr('disabled', 'disabled');

    var member_view = self.hasClass('member_view');
    var member_id = self.data('id');

    if(!member_id){
        self.removeAttr('disabled');
        return false;
    }

    var test = true;

    if(test == false && self.hasClass('login') && $('#popup_vote').length){
        if(member_view == false){
            $('#popup_vote').attr('data-id', member_id);

            $.fancybox({
                maxWidth: 800,
                minWidth: 500,
                href: '#popup_vote'
            });
        } else {
            $('.sharing_popup .main').hide();
            $('.sharing_popup .message').hide();
            $('.sharing_popup .login_btns').show();
            $('.sharing_popup .share_btns').hide();
        }

        self.removeAttr('disabled');
        return false;
    }

    $.ajax({
        dataType: 'json',
        url     : '/members/set_vote/' + member_id + '/',
        success: function(data){
            if(data.success == 1){
                if(member_view == false){
                    if(data.vote_status == -1){
                        $('#popup_vote_message .part_bottom').text('Ви вже проголосували');
                    } else if(data.vote_status == 1){
                        $('#popup_vote_message .part_bottom').text('Дякуємо, що проголосували');
                    }

                    $.fancybox({
                        maxWidth: 800,
                        minWidth: 500,
                        href: '#popup_vote_message'
                    });
                } else {
                    if(data.vote_status == -1){
                        $('.sharing_popup .message .text').text('Ви вже проголосували');
                    } else if(data.vote_status == 1){
                        $('.sharing_popup .message .text').text('Дякуємо, що проголосували');
                    }

                    $('.sharing_popup .main').hide();
                    $('.sharing_popup .message').show();
                    $('.sharing_popup .login_btns').hide();
                    $('.sharing_popup .share_btns').hide();
                }

                self.closest('.vote_area').find('.votes_count_block').text(data.count_votes.count);
                //self.closest('.vote_area').prepend('<div class="add_vote_already">Ви вже проголосували</div>');
                //self.remove();

                $('.vote_area').prepend('<div class="add_vote_already">Ви вже проголосували</div>');
                $('.add_vote_submit').remove();
            } else if (data.message != ''){
                if(member_view == false){
                    $('#popup_vote_message .part_bottom').text(data.message);

                    $.fancybox({
                        maxWidth: 800,
                        minWidth: 500,
                        href: '#popup_vote_message'
                    });
                } else {
                    $('.sharing_popup .message .text').text(data.message);

                    $('.sharing_popup .main').hide();
                    $('.sharing_popup .message').show();
                    $('.sharing_popup .login_btns').hide();
                    $('.sharing_popup .share_btns').hide();
                }
            } else if (data.success == -1) {
                if(member_view == false){
                    $('#popup_vote').attr('data-id', member_id);

                    $.fancybox({
                        maxWidth: 800,
                        minWidth: 500,
                        href: '#popup_vote'
                    });
                } else {
                    $('.sharing_popup .main').hide();
                    $('.sharing_popup .message').hide();
                    $('.sharing_popup .login_btns').show();
                    $('.sharing_popup .share_btns').hide();
                }
            }

            self.removeAttr('disabled');
        }
    }); // end ajax

    return false;
});

function check_member(){
    var href = $(this).attr('href');

    var member_id = 0;

    if($('#member_view_socs_block').length){
        member_id = $('#member_view_socs_block').attr('data-id');
    } else {
        member_id = $('#popup_vote').attr('data-id');
    }

    if(member_id == 0){
        return false;
    }

    $.ajax({
        dataType: 'json',
        async : false,
        url     : '/members/set_pre_vote/' + member_id + '/',
        success: function(data){
            //window.location.href = href;
            return true;
        }
    }); // end ajax

    return true;
}

$(document).on('click', '.open_soc_block', function(){
    $('.sharing_popup .main').hide();
    $('.sharing_popup .message').hide();
    $('.sharing_popup .login_btns').hide();
    $('.sharing_popup .share_btns').show();

    return true;
});

$(document).on('click', '.sharing_popup .close', function(){
    $('.sharing_popup .main').show();
    $('.sharing_popup .message').hide();
    $('.sharing_popup .login_btns').hide();
    $('.sharing_popup .share_btns').hide();

    return true;
});

$(document).on('click', '.unlogin-btn', function(){

    $.ajax({
        url: '/members/unlogin/',
        success: function(data){
            console.log(1);
            location.reload();
        }
    }); // end ajax

   return false;
});
