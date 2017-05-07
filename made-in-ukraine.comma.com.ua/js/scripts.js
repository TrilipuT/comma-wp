(function($){

    var $window,
        windowWidth,
        $frameBg,
        $frameBgImage,
        frameImgageRatio = 0;


    function initSearch() {
        var $header = $('.header');
        var $headerSearch = $('.header-search');
        var $inp = $headerSearch.find('.inp');
        $('.header-nav .search').on('click', function() {
            if ($headerSearch.hasClass('always_shown')) {
                $inp.focus();
                return;
            }
            $header.toggleClass('search_shown');
        });
    }

    function initSlider() {
        $('.slider').each(function() {
            var $self = $(this),
                $prev = $self.find('.prev'),
                $next = $self.find('.next'),
                $content = $self.find('.slider-content'),
                itemWidth = $self.find('.item').outerWidth(true),
                repeat = false,
                repeatTimeout;

            var sly = new Sly( $content, {
                horizontal: 1,
                smart: 1,
                activateOn: 'click',
                //mouseDragging: 1,
                touchDragging: 1,
                releaseSwing: 1,
                speed: 200,
                elasticBounds: 1
            });
            sly.init();
            function moveFloater(direction) {
                sly.slideTo(sly.pos.cur + direction * itemWidth);
                if (repeat) {
                    clearTimeout(repeatTimeout);
                    repeatTimeout = setTimeout(function(){moveFloater(direction)}, 500);
                }
            }
            $next.mousedown(function(e) {
                e.preventDefault();
                repeat = true;
                moveFloater(4);
                $('body').one('mouseup', function() {
                    clearTimeout(repeatTimeout);
                    repeat = false;
                })
            });
            $prev.mousedown(function(e) {
                e.preventDefault();
                repeat = true;
                moveFloater(-4);
                $('body').one('mouseup', function() {
                    clearTimeout(repeatTimeout);
                    repeat = false;
                })
            });
        });
    } 
 

    function initImages() {

        $('.images').each(function() {
            var $self = $(this),
                $prev = $self.find('.prev'),
                $next = $self.find('.next'),
                $num = $self.find('.images-num'),
                $items = $self.find('.item'),
                $imgs = $self.find('.item img'),
                itemsLength = $self.find('.item').length,
                selected = 0,
                repeat = false,
                repeatTimeout; 

            var blockW = $self.width();
            var imagesContent = $self.find('.images-content');  

            if($self.find('img').length){
                $.each($self.find('img'), function(i){

                    if(this.getAttribute('width') > blockW){

                        if(i == 0){
                            imagesContent.css({height : 0});
                        }

                        var toWigth     = $(this).width();
                        var k           = blockW/this.getAttribute('width');
                        var newHeight   = this.getAttribute('height')*k;
                        var newWigth    = this.getAttribute('width')*k;

                        if(imagesContent.height() < newHeight){
                            imagesContent.css({height : newHeight});
                        }

                        $(this).css({height : newHeight, width : newWigth});
                        //console.log(Math.floor(newHeight), $(this).height(), this.getAttribute('height'));
                    }  
                });
            }
            //высота изображения * 800 / ширина изображения


            function loadImage(n) {
                var $img = $imgs.eq(n);
                if (!$img.length || !$img.attr('data-src')) return;
                $img.attr('src', $img.attr('data-src'));
                $img.attr('data-src', ''); 

            }
            function selectImage(n) {
                selected = n >= itemsLength ? 0 : n < 0 ? itemsLength-1 : n;
                $num.html((selected+1) + ' / ' + itemsLength);
                loadImage(selected-1);
                loadImage(selected);
                loadImage(selected+1);
                $items.removeClass('selected');
                $items.eq(selected).addClass('selected');
            }
            function moveFloater(direction) {
                selectImage(selected+direction);
                if (repeat) {
                    clearTimeout(repeatTimeout);
                    repeatTimeout = setTimeout(function(){moveFloater(direction)}, 500);
                }
            }

            $imgs.click(function(e) {

                //e.preventDefault();
                //repeat = true;
                moveFloater(1);
                /*
                $('body').one('mouseup', function() {
                    clearTimeout(repeatTimeout);
                    repeat = false;
                })
                */
            });


            $next.mousedown(function(e) {
                e.preventDefault();
                repeat = true;
                moveFloater(1);
                $('body').one('mouseup', function() {
                    clearTimeout(repeatTimeout);
                    repeat = false;
                    return false;
                })
            });

            $prev.mousedown(function(e) {
                e.preventDefault();
                repeat = true;
                moveFloater(-1);
                $('body').one('mouseup', function() {
                    clearTimeout(repeatTimeout);
                    repeat = false;
                })
            });

            selectImage(selected);
            if ($items.length < 2) {
                $self.addClass('images_single');
            }
        });
    }

    function initSwitch() {
        $('.switch').each(function() {
            var $self = $(this),
                $linksLi = $self.find('.switch-links li'),
                $links = $linksLi.find('a'),
                $content = $self.find('.switch-content'),
                $items = $content.find('.item');
            $links.each(function(n) {
                $(this).data('n', n);
                $(this).data('parentLi', $(this).parents('li'));
            });
            $links.on('click touchstart', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $li = $(this).data('parentLi'),
                    n = $(this).data('n');
                if ($li.hasClass('selected')) return;
                $linksLi.removeClass('selected');
                $li.addClass('selected');
                $content.height($items.eq(n).height());
                $items.removeClass('selected');
                $items.eq(n).addClass('selected');
            });
            $content.height($items.filter('.selected').height());
            $self.addClass('initialized');
        });
    }

    function initScrollSwitcher() {
        if (Modernizr.touch) return;

        $('.scroll_switcher').each( function() {
            var $self = $(this),
                $parent = $self.offsetParent(),
                $items = $self.find('.scroll_switcher-item'),
                $selected = $items.eq(0),
                windowHeight = $window.height(),
                minHeight = 0;
            // set new active item in sidebar
            function setActive(n) {
                if ($items.eq(n).hasClass('selected')) return;
                if (n >= $items.length) n = $items.length - 1;
                if (n < 0) {
                    $self.addClass('show_all');
                }
                else {
                    $self.removeClass('show_all');
                }
                if ($items.eq(n).hasClass('selected')) return;
                $selected = $items.eq(n);
                $items.slice(0,n).removeClass('selected').addClass('up');
                $selected.removeClass('up').addClass('selected');
                $items.slice(n+1).removeClass('selected up').css('top', windowHeight > minHeight ? windowHeight+100 : minHeight + 100);
                if (minHeight < $selected.height()) {
                    minHeight = $selected.height();
                    $self.css('min-height', minHeight);
                }
            }
            // checks if there a need in fix or not
            function noNeedInFix() {
                if ($self.offset().top > $window.scrollTop() || $parent.height() <= $self.height()) {
                    // prevent from fixing if higher or content is smaller
                    $self.removeClass('fixed reached_bottom');
                    setActive(0);
                    return false;
                }
                else if ($selected[0].getBoundingClientRect().bottom >= $parent[0].getBoundingClientRect().bottom-20 && $parent[0].getBoundingClientRect().bottom-$selected[0].getBoundingClientRect().height<=20) {
                    // prevent from scrolling below container
                    $self.addClass('reached_bottom');
                    return true;
                }
                return false;
            }
            // update position and styles
            function update() {
                if (noNeedInFix()) return;

                $self.removeClass('reached_bottom');
                $self.addClass('fixed');

                var part = ($parent.outerHeight()-windowHeight)/$items.length,
                    range = 0,
                    elementN = 0,
                    top = $window.scrollTop()-$self.offset().top;
                for (var i=0; i<$items.length; i++) {
                    range = (i < 1) ? part*(i+1)-150 : (i === 1) ? part*(i+1)+150 : part*(i+1);
                    if (top < range) {
                        break;
                    }
                    elementN = i+1;
                    //parts[i] = part*(i+1);
                }
                //setActive(Math.floor(($window.scrollTop()-$self.offset().top)/part));
                setActive(elementN);

                if (noNeedInFix()) return;
            }
            function onResize() {
                // move all items lower then window
                windowHeight = $window.height();
                $items.css('top', windowHeight > minHeight ? windowHeight+100 : minHeight + 100);
            }
            $window.on('resize', function() {
                onResize();
            });
            $window.on('scroll touchmove resize', function() {
                update();
            });
            $window.load(function(){
                $self.css('min-height', $selected.height());
            });
            $selected.addClass('selected');
            setTimeout(function() {$self.addClass('initialized');}, 500);
            update();
            onResize();
        });
    }

    function initFrame() {
        if (!$('.frame').length) return;
        $frameBgImage.load(function() {
            if ($frameBgImage.prop('naturalWidth') == undefined) {
                /**
                 * IMPORTANT FOR IE8
                 * set natural dimensions of image
                 */
                if ($('.block404').length) {
                    $frameBgImage.prop('naturalWidth', 1560);
                    $frameBgImage.prop('naturalHeight', 860);
                }
                else {
                    $frameBgImage.prop('naturalWidth', 1350);
                    $frameBgImage.prop('naturalHeight', 707);
                }
            }
            frameImgageRatio = $frameBgImage.prop('naturalWidth') / $frameBgImage.prop('naturalHeight');
            resizeFrameImage();
        }).each(function() {
            if (this.complete) $(this).load();
        });
    }

    function resizeFrameImage() {
        if (!$frameBg) return;
        if (frameImgageRatio > $frameBg.width() / $frameBg.height()) {
            $frameBg.removeClass('fill_v').addClass('fill_h');
        }
        else {
            $frameBg.removeClass('fill_h').addClass('fill_v');
        }
    }

    $(document).ready(function() {
        $window          = $(window);
        $frameBg         = $('.frame-bg');
        $frameBgImage    = $frameBg.find('img');

        initSearch();
        initSlider();
        initImages();
        initSwitch();
        initScrollSwitcher();

        initFrame();

        $window.on('resize', function() {
            windowWidth = $window.width();
            resizeFrameImage();
        });


        if($('.article-question').length > 0){
            $.each($('.article-question'), function(){ 
                $(this).html('<p>'+$(this).text()+'</p>');
            });
        }
 

        if($('.article-content').length > 0){
            if($('.article-content img').length > 0){ 

                var myRe = /graphics\/tiny_mce\//g;

                $.each($('.article-content img'), function(){  
                    //console.log($(this), $(this).attr('src'));
                    var myArray = myRe.exec($(this).attr('src'));
                    if(myArray){
                        //console.log($(this), $(this).attr('src'), myArray); 
                        if($(this).attr('height')){
                        	$(this).attr('height', '');
                        }
                        
                    } 
                    //$(this).html('<p>'+$(this).text()+'</p>');
                });
            } 
        }

        fix_padding();
    });

    $(window).load(function(){

    });

    function fix_padding(){
        var articles_block = $('.articles.container_for_grid');
        if(articles_block.length){
            var cols = articles_block.find('.grid_1of4');


            $.each(cols, function(i){
                var col = $(this);
                var col_height = col.height();
                var col_items = col.find('.item');

                if(col_items.length == 0){
                    return;
                }

                var parrent_height = col.parent().height();
                var panding = 70;



                switch (i){
                    case 0:
                    case 1:
                        var big_block_height = col.parent().find('.item_big').height();
                        parrent_height = parrent_height - (big_block_height+panding);

                        break;
                    case 2:

                        break;
                    case 3:
                        var banner_height = col.find('.banner').height();
                        break;
                    default :
                        return;
                }


                var value = (parrent_height-col_height);

                if(value <= 1){
                    return;
                }

                var item_marging = value/col_items.length;

                switch(col_items.length){
                    case 1:

                        break;
                    case 2:
                        //console.log(item_marging);
                        col_items.eq(0).css({"margin-bottom" : (item_marging + 50)});
                        //col_items.eq(0).css({"margin-bottom" : (item_marging + 50)});
                        //col_items.eq(1).css({"margin-bottom" : (item_marging + 50)});
                        //col_items.eq(0).css({"margin-bottom" : ((item_marging*2) + 50)});
                        //col_items.eq(1).css({"margin-bottom" : 0});
                        break;
                    case 3:
                        col_items.eq(0).css({"margin-top" : (item_marging/2) + 'px'});
                        col_items.eq(1).css('margin', (item_marging + 50) + 'px 0');
                        col_items.eq(2).css({"position" : "absolute", "bottom" : "0", "margin-bottom" : (item_marging + 50)});
                        break;
                    case 4:

                        break;
                    default:
                        return;
                }


                var item_height = $(this).height();

                //$(this).css('margin', (item_marging + 50) + 'px 0');

                //console.log(item_height, item_height+50, item_height+panding, $(this));

                //console.log(value-item_height-20 );

                //console.log(col_height, parrent_height, col_items, (parrent_height/col_items.length)-panding);
            });

        }
    }

})(jQuery);
