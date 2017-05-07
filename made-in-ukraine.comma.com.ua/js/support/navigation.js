mainNav = $('.mainnav>ul>li');
	mainNav.find('ul').siblings().addClass('hasUl').append('<span class="hasDrop icon16 icomoon-icon-arrow-down-2"></span>');
	mainNavLink = mainNav.find('a').not('.sub a');
	mainNavLinkAll = mainNav.find('a');
	mainNavSubLink = mainNav.find('.sub a').not('.sub li .sub a');
	mainNavCurrent = mainNav.find('a.current');

	//remove current class if have
	mainNavCurrent.removeClass('current');
	//set the seleceted menu element
	if ($.cookie("newCurrentMenu")) {
		mainNavLinkAll.each(function(index) {
			if($(this).attr('href') == $.cookie("newCurrentMenu")) {
				//set new current class
				$(this).addClass('current');

				ulElem = $(this).closest('ul');
				if(ulElem.hasClass('sub')) {
					//its a part of sub menu need to expand this menu
					aElem = ulElem.prev('a.hasUl').addClass('drop');
					ulElem.addClass('expand');
				} 
				//create new cookie
				$.cookie("currentPage",$(this).attr('href'));
			}
		});
	}	
	
	//hover magic add blue color to icons when hover - remove or change the class if not you like.
	mainNavLinkAll.hover(
	  function () {
	    $(this).find('span.icon16').addClass('blue');
	  }, 
	  function () {
	    $(this).find('span.icon16').removeClass('blue');
	  }
	);

	//click magic
	mainNavLink.click(function(event) {
		$this = $(this);
		
		if($this.hasClass('hasUl')) {
			event.preventDefault();
			if($this.hasClass('drop')) {
				$(this).siblings('ul.sub').slideUp(500).siblings().removeClass('drop');
			} else {
				$(this).siblings('ul.sub').slideDown(500).siblings().addClass('drop');
			}			
		} else {
			//has no ul so store a cookie for change class.
			$.cookie("newCurrentMenu",$this.attr('href') ,{expires: null});
		}
	});
	mainNavSubLink.click(function(event) {
		$this = $(this);
		
		if($this.hasClass('hasUl')) {
			event.preventDefault();
			if($this.hasClass('drop')) {
				$(this).siblings('ul.sub').slideUp(500).siblings().removeClass('drop');
			} else {
				$(this).siblings('ul.sub').slideDown(250).siblings().addClass('drop');
			}			
		} else {
			//has no ul so store a cookie for change class.
			$.cookie("newCurrentMenu",$this.attr('href') ,{expires: null});
		}
	});

	//responsive buttons
	$('.resBtn>a').click(function(event) {
		$this = $(this);
		if($this.hasClass('drop')) {
			$('#sidebar>.shortcuts').slideUp(500).addClass('hided');
			$('#sidebar>.sidenav').slideUp(500).addClass('hided');
			$('#sidebar-right>.shortcuts').slideUp(500).addClass('hided');
			$('#sidebar-right>.sidenav').slideUp(500).addClass('hided');
			$this.removeClass('drop');
		} else {
			if($('#sidebar').length) {
				$('#sidebar').css('display', 'block');
				if($('#sidebar-right').length) {
					$('#sidebar-right').css({'display' : 'block', 'margin-top' : '0'});
				}
			}
			if($('#sidebar-right').length) {
				$('#sidebar-right').css('display', 'block');
			}
			$('#sidebar>.shortcuts').slideDown(250);
			$('#sidebar>.sidenav').slideDown(250);
			$('#sidebar-right>.shortcuts').slideDown(250);
			$('#sidebar-right>.sidenav').slideDown(250);
			$this.addClass('drop');
		}
	});
	$('.resBtnSearch>a').click(function(event) {
		$this = $(this);
		if($this.hasClass('drop')) {
			$('.search').slideUp(500);
			$this.removeClass('drop');
		} else {
			$('.search').slideDown(250);
			$this.addClass('drop');
		}
	});
	
	//Hide and show sidebar btn

	$(function () {
		//var pages = ['grid.html','charts.html'];
		var pages = [];
	
		for ( var i = 0, j = pages.length; i < j; i++ ) {

		    if($.cookie("currentPage") == pages[i]) {
				var cBtn = $('.collapseBtn.leftbar');
				cBtn.children('a').attr('title','Show Left Sidebar');
				cBtn.addClass('shadow hide');
				cBtn.css({'top': '20px', 'left':'200px'});
				$('#sidebarbg').css('margin-left','-299'+'px');
				$('#sidebar').css('margin-left','-299'+'px');
				if($('#content').length) {
					$('#content').css('margin-left', '0');
				}
				if($('#content-two').length) {
					$('#content-two').css('margin-left', '0');
				}
		    }

		}
		
	});

	$( '.collapseBtn' ).bind( 'click', function(){
		$this = $(this);

		//left sidbar clicked
		if ($this.hasClass('leftbar')) {
			
			if($(this).hasClass('hide')) {
				//show sidebar
				$('#sidebarbg').css('margin-left','0');
				$('#content').css('margin-left', '213'+'px');
				$('#content-two').css('margin-left', '213'+'px');
				$('#sidebar').css({'left' : '0', 'margin-left' : '0'});

				$this.removeClass('hide');
				$('.collapseBtn.leftbar').css('top', '120'+'px').css('left', '170'+'px').removeClass('shadow');
				$this.children('a').attr('title','Hide Left Sidebar');

			} else {
				//hide sidebar
				$('#sidebarbg').css('margin-left','-299'+'px');
				$('#sidebar').css('margin-left','-299'+'px');
				$('.collapseBtn.leftbar').animate({ //use .hide() if you experience heavy animation :)
				    left: '200',
				    top: '20'
				  }, 500, 'easeInExpo', function() {
				    // Animation complete.
				  
				}).addClass('shadow');
				//expand content
				$this.addClass('hide');
				$this.children('a').attr('title','Show Left Sidebar');
				if($('#content').length) {
					$('#content').css('margin-left', '0');
				}
				if($('#content-two').length) {
					$('#content-two').css('margin-left', '0');
				}
							
			}

		}

		//right sidebar clicked
		if ($this.hasClass('rightbar')) {
			
			if($(this).hasClass('hide')) {
				//show sidebar
				$('#sidebarbg-right').css('margin-right','0');
				$('#sidebar-right').css({'right' : '0', 'margin-right' : '0'});
				if($('#content').length) {
					$('#content').css('margin-left', '213'+'px');
				}
				if($('#content-one').length) {
					$('#content-one').css('margin-right', '212'+'px');
				}
				if($('#content-two').length) {
					$('#content-two').css({'margin-right' : '212' + 'px'});
				}			
				/*if($('#sidebar').length) {
					$('#sidebar').css({'left' : '0', 'margin-left' : '0'});
				}*/
				$this.removeClass('hide');
				$('.collapseBtn.rightbar').css('top', '120'+'px').css('right', '18'+'px').removeClass('shadow');
				$this.children('a').attr('title','Hide Right Sidebar');
				
			} else {
				//hide sidebar
				$('#sidebarbg-right').css('margin-right','-299'+'px');			
				$('#sidebar-right').css('margin-right','-299'+'px');
				if($('#content').length) {
					$('#content').css('margin-right', '0');
				}
				if($('#content-one').length) {
					$('#content-one').css({'margin-left': '0', 'margin-right' : '0'});
				}
				if($('#content-two').length) {
					$('#content-two').css({'margin-right' : '0'});
				}	
				$('.collapseBtn.rightbar').animate({ //use .hide() if you experience heavy animation :)
				    right: '10',
				    top: '78'
				  }, 500, 'easeInExpo', function() {
				    // Animation complete.
				  
				}).addClass('shadow');
				//expand content
				$this.addClass('hide');
				$this.children('a').attr('title','Show Right Sidebar')
			}

		}
	});