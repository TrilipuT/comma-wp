(function($) {
    $(document).ready(function() {

    /* IF YOU WANT TO APPLY SOME BASIC JQUERY TO REMOVE THE VIDEO BACKGROUND ON A SPECIFIC VIEWPORT MANUALLY

     var is_mobile = false;

    if( $('.player').css('display')=='none') {
        is_mobile = true;       
    }
    if (is_mobile == true) {
        //Conditional script here
        $('.big-background, .small-background-section').addClass('big-background-default-image');
    }else{
        $(".player").mb_YTPlayer(); 
    }

    });

*/
    /*  IF YOU WANT TO USE DEVICE.JS TO DETECT THE VIEWPORT AND MANIPULATE THE OUTPUT  */

        //Device.js will check if it is Tablet or Mobile - http://matthewhudson.me/projects/device.js/
        if (!device.tablet() && !device.mobile()) {
            $(".player").mb_YTPlayer();
        } else {
            //jQuery will add the default background to the preferred class 
            $('.big-background, .small-background-section').addClass(
                'big-background-default-image');
        }
    });

    Share = {
        vkontakte: function(purl) {

           // var ptitle = $("meta[property='og:title']").attr('content');
           // var text = $("meta[property='og:description']").attr('content');
           // var pimg = $("meta[property='og:image']").attr('content');

            url  = 'http://vkontakte.ru/share.php?';
            url += 'url='          + encodeURIComponent(purl);
           // url += '&title='       + encodeURIComponent(ptitle);
           // url += '&description=' + encodeURIComponent(text);
           // url += '&image='       + encodeURIComponent(pimg);
            url += '&noparse=true';
            Share.popup(url);
        },
        odnoklassniki: function(purl, text) {
            url  = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1';
            url += '&st.comments=' + encodeURIComponent(text);
            url += '&st._surl='    + encodeURIComponent(purl);
            Share.popup(url);
        },
        facebook: function(purl) {

           // var ptitle = $("meta[property='og:title']").attr('content');
           // var text = $("meta[property='og:description']").attr('content');
            var pimg = $("meta[property='og:image']").attr('content');

            url  = 'http://www.facebook.com/sharer.php?s=100';
           // url += '&p[title]='     + encodeURIComponent(ptitle);
           // url += '&p[summary]='   + encodeURIComponent(text);
            url += '&p[url]='       + encodeURIComponent(purl);
            //url += '&p[images][0]=' + encodeURIComponent(pimg);
            Share.popup(url);
        },
        twitter: function(purl, ptitle) {
            url  = 'http://twitter.com/share?';
            url += 'text='      + encodeURIComponent(ptitle);
            url += '&url='      + encodeURIComponent(purl);
            url += '&counturl=' + encodeURIComponent(purl);
            Share.popup(url);
        },
        mailru: function(purl, ptitle, pimg, text) {
            url  = 'http://connect.mail.ru/share?';
            url += 'url='          + encodeURIComponent(purl);
            url += '&title='       + encodeURIComponent(ptitle);
            url += '&description=' + encodeURIComponent(text);
            url += '&imageurl='    + encodeURIComponent(pimg);
            Share.popup(url)
        },

        popup: function(url) {
            window.open(url,'','toolbar=0,status=0,width=626,height=436');
        }
    };
})(jQuery);