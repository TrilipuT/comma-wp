<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js " lang="en"> <!--<![endif]-->
	<head>
		<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="application-name" content="admin" />
		<!-- Mobile Specific Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
		<meta http-equiv="content-script-type" content="text/javascript">


		<link href="/css/support/bootstrap/bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="/css/support/icons.css" rel="stylesheet" type="text/css" />
		<link href="/plugins/forms/uniform/uniform.default.css" type="text/css" rel="stylesheet" />
		<link href="/plugins/forms/ibutton/jquery.ibutton.css" type="text/css" rel="stylesheet">
		<!-- Main stylesheets -->
		<link href="/css/support/main.css" rel="stylesheet" type="text/css" />
		<link href="/css/support/custom.css" rel="stylesheet" type="text/css" />
		<link href="<?=Yii::app()->baseUrl?>/css/support/chosen.css" rel="stylesheet" type="text/css" />


		<!-- colorpicker -->
		<link href="<?=Yii::app()->baseUrl?>/plugins/forms/color-picker/color-picker.css" rel="stylesheet" type="text/css" />

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="/js/html5shiv.js"></script>
		<![endif]-->

		<!-- Le styles -->
		<!-- Use new way for google web fonts
		http://www.smashingmagazine.com/2012/07/11/avoiding-faux-weights-styles-google-web-fonts -->
		<!-- Headings -->
		<!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css' />  -->
		<!-- Text -->
		<!-- <link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css' /> -->
		<!--[if lt IE 9]>
			<link href="http://fonts.googleapis.com/css?family=Open+Sans:400" rel="stylesheet" type="text/css" />
			<link href="http://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet" type="text/css" />
			<link href="http://fonts.googleapis.com/css?family=Droid+Sans:400" rel="stylesheet" type="text/css" />
			<link href="http://fonts.googleapis.com/css?family=Droid+Sans:700" rel="stylesheet" type="text/css" />
		<![endif]-->


		<!-- Le fav and touch icons -->
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/apple-touch-icon-144-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png" />



		<script type="text/javascript">
			//adding load class to body and hide page
			document.documentElement.className += 'loadstate';

			var model = '<?=$this->activeModule;?>';
		</script>

		<!--<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-42946878-1', 'babych.com.ua');
		  ga('send', 'pageview');

		</script>  -->


		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/plugins.js"></script>

  		<link href="<?=Yii::app()->baseUrl?>/js/support/jcrop/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />
	    <script src="<?=Yii::app()->baseUrl?>/js/support/jcrop/js/jquery.Jcrop.js" type="text/javascript"></script>
	    <script type="text/javascript" src="<?=Yii::app()->baseUrl?>/plugins/forms/color-picker/colorpicker.js"></script>

		<!-- Load TinyMCE -->
		<script type="text/javascript" src="/js/support/tiny_mce/jquery.tinymce.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			$('textarea.tiny_mce').tinymce({
				// Location of TinyMCE script
				convert_urls: false,
				relative_urls: false,
				remove_script_host: false,

				width: 1111,
				script_url: '/js/support/tiny_mce/tiny_mce.js',
				content_css: "/css/main.css",
				editor_selector: "tiny_mce",
				language: 'en',
				gecko_spellcheck: true,
				// General options
				theme: "advanced",
				plugins: "clear_html, spellchecker, youtube,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,images",
				extended_valid_elements: 'b,img[*],button[*],ul[*],li[*],a[*],p[*],i[*],span[*],div[*],figure[*],figcaption[*],section[*],article[*], embed[*]',
				// Theme options
				theme_advanced_buttons1: "clear_html, bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,insertdate,forecolor,backcolor",
				theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl, spellchecker",
				theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,|,inserttime,preview,|,fullscreen,code,|,youtube,|,images",
				theme_advanced_toolbar_location: "top",
				theme_advanced_toolbar_align: "left",
				theme_advanced_statusbar_location: "bottom",
				theme_advanced_resizing: true,

				template_templates: [

					{
						title: "Готовый шаблон (Sziget member soundcloud)",
						src: "/js/support/tiny_mce/templates/sziget_member_soundcloud.html",
						description: "Готовый шаблон"
					},
					{
						title: "Готовый шаблон (Sziget member youtube)",
						src: "/js/support/tiny_mce/templates/sziget_member_youtube.html",
						description: "Готовый шаблон"
					},

					{
						title: "3 колонки (О проекте)",
						src: "/js/support/tiny_mce/templates/made_in_ukraine_about.html",
						description: "используется на главной Made in Ukraine"

					},
					{
						title: "Новая цитата (Made in Ukraine)",
						src: "/js/support/tiny_mce/templates/made_in_ukraine_quote.html",
						description: "используется в статях Made in Ukraine"

					},
					/*{
					 title: "Главный элемент (text_center)",
					 src: "/js/support/tiny_mce/templates/text_center.html",
					 description: "это конечный элемент, перед его использованием можно применять такие шаблоны как (article_question, article_text)"
					 },*/

					/*
					 {
					 title: "Внутренний элемент (article_question)",
					 src: "/js/support/tiny_mce/templates/article_question.html",
					 description: "это внутренний элемент"
					 },
					 {
					 title: "Внутренний элемент (article_text)",
					 src: "/js/support/tiny_mce/templates/article_text.html",
					 description: "это внутренний элемент"
					 },
					 //-------------------------------------------------------
					 {
					 title: "Главный элемент (text_left)",
					 src: "/js/support/tiny_mce/templates/text_left.html",
					 description: "это конечный элемент, перед его использованием можно применять такие шаблоны как (quote_right, article_question, article_text, )"
					 },
					 {
					 title: "Внутренний элемент (quote_right)",
					 src: "/js/support/tiny_mce/templates/quote_right.html",
					 description: "это внутренний элемент"
					 },
					 //-------------------------------------------------------
					 {
					 title: "Главный элемент (text_right)",
					 src: "/js/support/tiny_mce/templates/text_right.html",
					 description: "это конечный элемент, перед его использованием можно применять такие шаблоны как (article_question, article_text, img_left)"
					 },
					 {
					 title: "Внутренний элемент (img_left)",
					 src: "/js/support/tiny_mce/templates/img_left.html",
					 description: "это внутренний элемент, но он еще является родителем для (img_text)"
					 },
					 {
					 title: "Внутренний элемент (img_text)",
					 src: "/js/support/tiny_mce/templates/img_text.html",
					 description: "это внутренний элемент"
					 },
					 //-------------------------------------------------------
					 {
					 title: "Главный элемент (article_video)",
					 src: "/js/support/tiny_mce/templates/article_video.html",
					 description: "это конечный элемент, перед его использованием можно применять такие шаблоны как (article_video_text)"
					 },
					 {
					 title: "Внутренний элемент (article_video_text)",
					 src: "/js/support/tiny_mce/templates/article_video_text.html",
					 description: "это внутренний элемент"
					 },
					 //-------------------------------------------------------
					 {
					 title: "Главный элемент (img_center)",
					 src: "/js/support/tiny_mce/templates/img_center.html",
					 description: "это конечный элемент, перед его использованием можно применять такие шаблоны как (img_text)"
					 },
					 //-------------------------------------------------------
					 {
					 title: "Главный элемент (tracks_clearfix)",
					 src: "/js/support/tiny_mce/templates/tracks_clearfix.html",
					 description: "это конечный элемент, перед его использованием можно применять такие шаблоны как (tracks_links, tracks_rating)"
					 },
					 {
					 title: "Внутренний элемент (tracks_links)",
					 src: "/js/support/tiny_mce/templates/tracks_links.html",
					 description: "это внутренний элемент, но он еще является родителем для (tracks_title)"
					 },
					 {
					 title: "Внутренний элемент (tracks_rating)",
					 src: "/js/support/tiny_mce/templates/tracks_rating.html",
					 description: "это внутренний элемент, но он еще является родителем для (tracks_title)"
					 },
					 {
					 title: "Внутренний элемент (tracks_title)",
					 src: "/js/support/tiny_mce/templates/tracks_title.html",
					 description: "это внутренний элемент"
					 },

					 */

					{
						title: "Готовый шаблон (цитата справа)",
						src: "/js/support/tiny_mce/templates/quote_right(2).html",
						description: "Готовый шаблон"
					},
					{
						title: "Готовый шаблон (Картинка по центру)",
						src: "/js/support/tiny_mce/templates/img_center(2).html",
						description: "Готовый шаблон"
					},
					{
						title: "Готовый шаблон (tracks)",
						src: "/js/support/tiny_mce/templates/tracks.html",
						description: "Готовый шаблон"
					},
					{
						title: "Готовый шаблон (text_right (с картинкой слева))",
						src: "/js/support/tiny_mce/templates/text_right(2).html",
						description: "Готовый шаблон"
					},
					{
						title: "Готовый шаблон (article_video (с текстом слева))",
						src: "/js/support/tiny_mce/templates/article_video(2).html",
						description: "Готовый шаблон"
					},
				],

				// Example content CSS (should be your site CSS)
				//content_css : "/css/main.css",

				// Drop lists for link/image/media/template dialogs
				/*
				 template_external_list_url : "lists/template_list.js",
				 external_link_list_url : "lists/link_list.js",
				 external_image_list_url : "lists/image_list.js",
				 media_external_list_url : "lists/media_list.js",
				 */
				// Replace values for the template plugin
				template_replace_values: {
					username: "Some User",
					staffid: "991234"
				},
				// Spellchecker
				spellchecker_languages: "+Russian=ru,Ukrainian=uk,English=en",
				spellchecker_rpc_url: "http://speller.yandex.net/services/tinyspell",
				spellchecker_word_separator_chars: '\\s!"#$%&()*+,./:;<=>?@[\]^_{|}\xa7\xa9\xab\xae\xb1\xb6\xb7\xb8\xbb\xbc\xbd\xbe\u00bf\xd7\xf7\xa4\u201d\u201c',
				/*
				 // begin
				 setup : function(ed) {

				 var changeQuotes = function(text){
				 var el = document.createElement("DIV");



				 el.innerHTML = text;
				 for(var i=0, l=el.childNodes.length; i<l; i++){

				 el.childNodes[i].textContent = el.childNodes[i].textContent.replace('-', '—');

				 if (el.childNodes[i].hasChildNodes() && el.childNodes.length>1){
				 el.childNodes[i].innerHTML = changeQuotes(el.childNodes[i].innerHTML);
				 }
				 else{

				 el.childNodes[i].textContent = el.childNodes[i].textContent.replace(/\x27/g, '\x22').replace(/(\w)\x22(\w)/g, '$1\x27$2').replace(/(^)\x22(\s)/g, '$1»$2').replace(/(^|\s|\()"/g, "$1«").replace(/"(\;|\!|\?|\:|\.|\,|$|\)|\s)/g, "»$1");
				 }
				 }

				 return el.innerHTML;
				 }

				 var changeChar=function () {

				 content = tinyMCE.activeEditor.getContent({format : 'raw'});
				 content = changeQuotes(content);
				 //console.log(content);

				 tinyMCE.activeEditor.setContent(content);
				 };


				 //ed.onKeyUp.add(changeChar);
				 //ed.onChange.add(changeChar);
				 ed.onInit.add(changeChar);
				 }
				 // end
				 */
			});
		});
		</script>
		<!-- /TinyMCE -->

		<link rel="stylesheet" type="text/css" href="/js/support/uploadify/uploadify.css">

	</head>
	<body>
		<?php if(Yii::app()->user->isGuest==false):?>

			<!-- loading animation -->
			<div id="qLoverlay"></div>
			<div id="qLbar"></div>
			<div id="header" class="span12">

				<div class="navbar">
					<div class="navbar-inner">
						<div class="container-fluid">
							<a href="/support/"><span class="brand"  ><?=Yii::app()->name ?></span> </a>
							<div class="nav-no-collapse">

							<?php if($this->role != 'editor'): ?>

								<ul class="nav">
									<li class="dropdown">

										<a href="#" class="dropdown-toggle" data-toggle="dropdown">
											<span class="icon16 icomoon-icon-cog"></span> Settings
											<b class="caret"></b>
										</a>
										<ul class="dropdown-menu">
											<li class="menu">
												<ul>
													<li>
														<a href="<?=$this->createUrl('support/settings')?>"><span class="icon16 icomoon-icon-equalizer"></span>Site config</a>
													</li>
													<li>
														<a href="<?=$this->createUrl('support/language')?>"><span class="icon16 icomoon-icon-chess"></span>Language</a>
													</li>
													<!--
													<li>                                                    
														<a href="<?=$this->createUrl('support/socials')?>"><span class="icon16 icomoon-icon-shocked-2"></span>Соц. сети</a>
													</li> -->
												</ul>
											</li>
										</ul>
									</li>
								</ul>


								<ul class="nav pull-left usernav">

									<li class="dropdown">
										<a href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
											<?php

											///var_dump(Yii::app()->getAuthManager());

												$userImg = '/img/support/default-ava.jpg';

											?>

											<img src="<?=$userImg;?>" alt="" class="image" />
											<span class="txt"><?=Yii::app()->user->email?></span>
											<b class="caret"></b>
										</a>
										<ul class="dropdown-menu">
											<li class="menu">
												<ul>
													<li>
														<a href="<?=$this->createUrl('support/update/editors/'.Yii::app()->user->id)?>"><span class="icon16 icomoon-icon-user-3"></span>Edit profile</a>
													</li>
													<li>
														<a href="<?=$this->createUrl('support/editors/')?>"><span class="icon16 brocco-icon-pencil"></span>Edit users</a>
													</li>
													<li>
														<a href="<?=$this->createUrl('support/create/editors/')?>"><span class="icon16 icomoon-icon-plus-2"></span>Add user</a>
													</li>
												</ul>
											</li>
										</ul>
									</li>
									<!--
									<li>
										<a href="/support/send_mail">
		                                	<span class="icon16 icomoon-icon-mail-3"></span>
		                                	<span class="txt">Messages</span>
		                            	</a>
		                            </li>
		                            -->
									<li><a href="/support/logout"><span class="icon16 icomoon-icon-exit"></span> Logout</a></li>
								</ul>
							<?php else:?>

							 	<ul class="nav pull-left usernav">
									<li><a href="/support/logout"><span class="icon16 icomoon-icon-exit"></span> Logout</a></li>
								</ul>
							<?php endif;?>
							</div><!-- /.nav-collapse -->
						</div>
					</div><!-- /navbar-inner -->
				</div><!-- /navbar -->
			</div><!-- End #header -->




			<div id="wrapper">

				<!--Responsive navigation button-->
				<div class="resBtn">
					<a href="#"><span class="icon16 minia-icon-list-3"></span></a>
				</div>

				<!--Left Sidebar collapse button-->   <!--
				<div class="collapseBtn leftbar">
					 <a href="#" class="tipR" oldtitle="Hide Left Sidebar" title="" data-hasqtip="true" aria-describedby="qtip-20"><span class="icon12 minia-icon-layout"></span></a>
				</div> -->


				<div id="sidebarbg"></div>


				<div id="sidebar">

					<div class="sidenav">

						<div class="sidebar-widget" style="margin: -1px 0 0 0;">
							<h5 class="title" style="margin-bottom:0">Navigation</h5>
						</div><!-- End .sidenav-widget -->

						<div class="mainnav">
							<ul>

								<?php //if($this->role != 'editor'): ?>

									<li <?php if($this->activeModule == 'section') echo 'class="active"' ?>>
										<a href="<?=$this->createUrl('support/section')?>">
										<span class="icon16 icomoon-icon-list-view-2"></span>
										Разделы
										</a>
									</li>

								<?php //endif;?>

								<li <?php if($this->activeModule == 'news') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/news')?>">
									<span class="icon16 icomoon-icon-newspaper"></span>
									Новости
									</a>
								</li>

								<li <?php if($this->activeModule == 'constants') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/constants')?>">
									<span class="icon16 icomoon-icon-wand"></span>
									Константы
									</a>
								</li>

								<li <?php if($this->activeModule == 'gallery') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/gallery')?>">
									<span class="icon16 icomoon-icon-pictures"></span>
									Галерея
									</a>
								</li>

								<li <?php if($this->activeModule == 'tags') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/tags')?>">
									<span class="icon16 icomoon-icon-tag-2"></span>
									Теги
									</a>
								</li>

								<li <?php if($this->activeModule == 'videoCats') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/videoCats')?>">
									<span class="icon16 icomoon-icon-printer-3"></span>
									Видео категории
									</a>
								</li>

								<li <?php if($this->activeModule == 'videos') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/videos')?>">
									<span class="icon16 icomoon-icon-movie-3"></span>
									Видео
									</a>
								</li>



								<li <?php if($this->activeModule == 'authors') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/authors')?>">
									<span class="icon16 entypo-icon-users"></span>
									Авторы
									</a>
								</li>

								<li <?php if($this->activeModule == 'blogers') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/blogers')?>">
									<span class="icon16 cut-icon-user"></span>
									Блогеры
									</a>
								</li>


								<li <?php if($this->activeModule == 'rubrics') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/rubrics')?>">
									<span class="icon16 icomoon-icon-clipboard-3"></span>
									Рубрики
									</a>
								</li>


								<li <?php if($this->activeModule == 'article') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/article')?>">
									<span class="icon16 iconic-icon-article"></span>
										Публикации
									</a>
								</li>

								<li <?php if($this->activeModule == 'blogs') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/blogs')?>">
									<span class="icon16 iconic-icon-article"></span>
										Блоги
									</a>
								</li>

								<li <?php if($this->activeModule == 'banners') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/banners')?>">
									<span class="icon16 brocco-icon-picture"></span>
										Баннеры
									</a>
								</li>

								<li <?php if($this->activeModule == 'comment') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/comment')?>">
									<span class="icon16 icomoon-icon-comments-7"></span>
										Комменты
									</a>
								</li>


								<li <?php if($this->activeModule == 'users') echo 'class="active"' ?>>
									<a href="<?=$this->createUrl('support/users')?>">
										<span class="icon16 icomoon-icon-users-2"></span>
										Пользователи
									</a>
								</li>


								<li>
									<a href="#" class="hasUl <?php if($this->activeModule == 'members' || $this->activeModule == 'jury') echo 'drop' ?>">
										<span class="icon16 icomoon-icon-equalizer-2"></span>
											Sziget<span class="hasDrop icon16 icomoon-icon-arrow-down-2"></span>
										</a>
										<ul class="sub expand"  <?php if($this->activeModule != 'members' && $this->activeModule != 'jury') echo 'style="display:none;"' ?>>
											<li <?php if($this->activeModule == 'jury') echo 'class="active"' ?>>
												<a href="<?=$this->createUrl('support/jury')?>">
													<span class="icon16 icomoon-icon-users"></span>
													Жюри
												</a>
											</li>

											<li <?php if($this->activeModule == 'members') echo 'class="active"' ?>>
												<a href="<?=$this->createUrl('support/members')?>">
													<span class="icon16 icomoon-icon-user-5"></span>
													Учасники
												</a>
											</li>
										</ul>
								</li>


								<!--
								<li>
		                            <a href="#" class="hasUl"><span class="icon16 icomoon-icon-equalizer-2"></span>UI Elements<span class="hasDrop icon16 icomoon-icon-arrow-down-2"></span></a>
		                            <ul class="sub expand" style="display: none;">
		                                <li><a href="icons.html"><span class="icon16 icomoon-icon-rocket"></span>Icons</a></li>
		                                <li><a href="buttons.html" class="current"><span class="icon16 icomoon-icon-file"></span>Buttons</a></li>
		                                <li><a href="elements.html"><span class="icon16 icomoon-icon-cogs"></span>Elements</a></li>
		                            </ul>
		                        </li>
								-->


							</ul>
						</div>
					</div>
				</div>


				<div id="content" class="clearfix">
					<div class="contentwrapper">
						<div class="heading">
							<h3><?=$this->moduleName?></h3>
							<div class="resBtnSearch">
								<a href="#"><span class="icon16 icomoon-icon-search-3"></span></a>
							</div> <!--
							<div class="search">
								<form id="searchform" action="search.html">
									<input type="text" id="tipue_search_input" class="top-search uniform-input text" placeholder="Search here ...">
									<input type="submit" id="tipue_search_button" class="search-btn nostyle" value="">
								</form>
							</div> End search -->

							<?php if(isset($this->breadcrumbs)):?>
								<?php $this->widget('zii.widgets.CBreadcrumbs', array('homeLink' => '<a href="/support/" class="crumbs-l">'.Yii::t('app','main_page').'</a>',
								                                                        'tagName' => 'ul',
								                                                        'htmlOptions' => array('class'=>'breadcrumb'),
								                                                        'activeLinkTemplate' => '<a class="crumbs-l" href="{url}">{label}</a>',
								                                                        'inactiveLinkTemplate'=>'<span class="crumbs-l crumbs-l_current">{label}</span>',
								                                                        'separator' => ' ',
								                                                        'links' => $this->breadcrumbs
								                                                    )); ?>
							<?php endif;?>
							<!--
							<ul class="breadcrumb">
								<li>You are here:</li>
								<li>
									<a href="#" class="tip" oldtitle="back to dashboard" title="" data-hasqtip="true">
										<span class="icon16 icomoon-icon-screen-2"></span>
									</a>
									<span class="divider">
										<span class="icon16 icomoon-icon-arrow-right-3"></span>
									</span>
								</li>
								<li class="active">Static tables</li>
							</ul>
							-->
						</div>


						 <div class="row-fluid">

							<div class="span12">
								<!--
								<div class="page-header">
									<h4>Default table styles</h4>
								</div>
								<div class="responsive"><table class="table">
									<thead>
									  <tr>
										<th>#</th>
										<th>First Name</th>
										<th>Last Name</th>
										<th>Username</th>
									  </tr>
									</thead>
									<tbody>
									  <tr>
										<td>1</td>
										<td>Mark</td>
										<td>Otto</td>
										<td>@mdo</td>
									  </tr>
									  <tr>
										<td>2</td>
										<td>Jacob</td>
										<td>Thornton</td>
										<td>@fat</td>
									  </tr>
									  <tr>
										<td>3</td>
										<td>Larry</td>
										<td>the Bird</td>
										<td>@twitter</td>
									  </tr>
									</tbody>
								</table></div>
								-->
								<?=$content ?>
							</div>

						</div>
					</div>
				</div>
			</div>

		<?php else:?>

			<div class="container-fluid">

				<?=$content ?>

			</div><!-- End .container-fluid -->

		<?php endif;?>

		<script type="text/javascript" src="/js/support/jquery.cookie.js"></script>
		<script type="text/javascript" src="/js/support/bootstrap/bootstrap.js"></script>


		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/plugins/forms/validate/jquery.validate.min.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/plugins/forms/uniform/jquery.uniform.min.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/plugins/forms/ibutton/jquery.ibutton.min.js"></script>



		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/plugins/misc/totop/jquery.ui.totop.min.js"></script>

		<!--<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script type="text/javascript" src="/js/support/jquery-ui-1.10.3.js"></script>   -->


		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/chosen.jquery.js"></script>
		<script type="text/javascript">
		  $(document).ready(function(){

	       		$(".chzn-select").chosen({
		      		no_results_text: "Нажмите enter чтобы добавить новый элемент",
					allow_add_by_enter: true,
					//allow_add_by_space: true
				});


		        $(".chzn-select-deselect").chosen({allow_single_deselect:true});

		  });

		</script>




		<script src="<?=Yii::app()->baseUrl?>/js/support/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>


		<script src="<?=Yii::app()->baseUrl?>/js/support/jquery.autocomplete.js" type="text/javascript"></script>
		<script>
		    // rename the local copy of $.fn.autocomplete
		    $.fn.ourautocomplete = $.fn.autocomplete;
		    delete $.fn.autocomplete;
		</script>


		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/script.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/navigation.js"></script>




		<link href="<?=Yii::app()->baseUrl?>/css/support/jquery-ui/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css" />

		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/ui-timepicker/jquery-ui.min.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/ui-timepicker/jquery.ui.timepicker.ru.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/ui-timepicker/jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/support/ui-timepicker/jquery-ui-sliderAccess.js"></script>

		<script type="text/javascript">
			$('#datetime').datetimepicker({
				dateFormat: "yy-mm-dd",
				timeFormat: 'HH:mm',
					stepHour: 2,
					stepMinute: 10
				});

		</script>
	</body>
</html>