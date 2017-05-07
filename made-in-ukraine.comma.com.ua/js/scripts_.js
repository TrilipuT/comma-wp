(function($){

    var $window;

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
                moveFloater(1);
                $('body').one('mouseup', function() {
                    clearTimeout(repeatTimeout);
                    repeat = false;
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
            $next.mousedown(function(e) {
                e.preventDefault();
                repeat = true;
                moveFloater(1);
                $('body').one('mouseup', function() {
                    clearTimeout(repeatTimeout);
                    repeat = false;
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
        //return;
        $('.scroll_switcher').each( function() {
            var $self = $(this),
                $parent = $self.offsetParent(),
                $items = $self.find('.scroll_switcher-item'),
                $selected = $items.eq(0);
            // set new active item in sidebar
            function setActive(n) {
                console.log('setActive ' + n);
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
                $items.slice(n+1).removeClass('selected up').css('top', $window.height()+100);
            }
            // checks if there a need in fix or not
            function noNeedInFix() {
                if ($self.offset().top > $window.scrollTop()) {
                    // prevent from fixing if higher
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

                var part = ($parent.outerHeight()-$window.height())/$items.length,
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
                $items.css('top', $window.height()+100);
            }
            $window.on('resize', function() {
                onResize();
            });
            $window.on('scroll touchmove resize', function() {
                update();
            });
            $selected.addClass('selected');
            setTimeout(function() {$self.addClass('initialized');}, 500);
            update();
            onResize();
        });
    }

	$(document).ready(function() {
        $window = $(window);
        initSearch();
        initSlider();
        initImages();
        initSwitch();
        initScrollSwitcher();
	});

	$(window).load(function(){

	});
	
})(jQuery);