<!DOCTYPE html>
<html lang="<?=Yii::app()->language?>">
<head>
	<link href="/favicon_sziget.ico" rel="shortcut icon" type="image/x-icon" />
    <meta name="robots" content="index, follow">
    <meta charset=utf-8 />
    <title><?=addslashes(CHtml::encode($this->pageTitle)); ?></title>

	<meta property="og:url" content="<?=(!empty($this->og_url) ? $this->og_url : 'http://' . $_SERVER['HTTP_HOST'] . Yii::app()->request->url);?>" />

    <meta property="og:title" content="<?=addslashes(CHtml::encode($this->og_title)); ?>" />
    <?php if($this->og_image != ''): ?>
        <meta property="og:image" content="<?=$this->og_image?>" />
    <?php endif;?>
    <meta property="og:type" content="website" />
    <meta property="og:description" content="<?=addslashes(CHtml::encode($this->og_desc)); ?>" />

	<link rel="stylesheet" href="<?=Yii::app()->theme->baseUrl?>/css/jquery.fancybox<?=(Yii::app()->params['css_version'] ? '.v.'.Yii::app()->params['css_version'] : '')?>.css"/>
	<link rel="stylesheet" href="<?=Yii::app()->theme->baseUrl?>/css/jquery.bxslider<?=(Yii::app()->params['css_version'] ? '.v.'.Yii::app()->params['css_version'] : '')?>.css"/>
	<link rel="stylesheet" href="<?=Yii::app()->theme->baseUrl?>/css/jquery.mCustomScrollbar<?=(Yii::app()->params['css_version'] ? '.v.'.Yii::app()->params['css_version'] : '')?>.css"/>
	<link rel="stylesheet" href="<?=Yii::app()->theme->baseUrl?>/css/style<?=(Yii::app()->params['css_version'] ? '.v.'.Yii::app()->params['css_version'] : '')?>.css"/>

    <script type="text/javascript">
        var lang    = '<?=Yii::app()->language?>';
        var oldPath = '<?=( $this->Section != null ? $this->Section->getUrl() : "");?>';
    </script>

	<script type="text/javascript" src="//vk.com/js/api/openapi.js?113"></script>
	<script type="text/javascript" src="http://vk.com/js/api/share.js?90"></script>
    <script type="text/javascript">
        VK.init({apiId: 4786964, onlyWidgets: true});
    </script>

	<script>
		window.fbAsyncInit = function() {
			FB.init({
				appId      : '814539821959880',
				xfbml      : true,
				version    : 'v2.2'
			});
		};

		(function(d, s, id){
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/uk_UA/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?=Yii::app()->theme->baseUrl?>/js/jquery-1.11.1.min.js"><\/script>')</script>
</head>
<body>
<header class="<?=(Yii::app()->request->url == '/' ? 'head_main' : 'head_inher')?>">
	<div class="header_content clearfix">
		<div class="wrapper clearfix">
			<?php if(Yii::app()->request->url != '/'):?>
				<div class="header_logo"><a href="/"></a></div>
			<?php endif;?>
			<?php $this->widget('application.components.widgets.SzigetTopMenu'); ?>
			<div class="header_social">
				<ul>
					<li class="fb"><a target="_blank" href="http://facebook.com/SzigetUA"></a></li>
					<li class="vk"><a target="_blank" href="http://vk.com/szigetua"></a></li>
				</ul>
			</div>
		</div>
		<div class="back_site"><a target="_blank" href="http://szigetfestival.com.ua/">на сайт sziget</a></div>
	</div>
</header>
<section class="main">
	<?=$content;?>
	<section class="video_about"> <?php // https://www.youtube.com/embed/k_JqgPOJBpA?rel=0&amp;controls=0&amp;showinfo=0?>
		<a class="fancybox-media" href="https://vimeo.com/114012466">
			<span class="hover_video">
				<span class="play"></span>
			</span>
			<span class="va_text">
				&mdash;<br>
				Sziget 2014 <br>
				aftermovie<br>
				&mdash;
			</span>
		</a>
	</section>
</section>
<footer>
	<div class="wrapper">
		<div class="footer_top">
			<div class="footer_logo"><a href="/"></a></div>
		</div>
		<div class="footer_bottom clearfix">
			<?php $this->widget('application.components.widgets.SzigetFooterMenu'); ?>
			<div class="social_footer">
				<ul>
					<li class="fb"><a target="_blank" href="http://facebook.com/SzigetUA"></a></li>
					<li class="vk"><a target="_blank" href="http://vk.com/szigetua"></a></li>
				</ul>
			</div>
			<div class="developer">
				<a href="http://comma.com.ua" target="_blank"><img src="/images/develop_icon.png" alt="comma"></a>
			</div>
		</div>
	</div>
</footer>

<div data-id="0" id="popup_vote" class="popup votes_popup" data-id="">
	<div class="title part_top">Голосування</div>

	<div class="part_bottom">Щоб проголосувати, залогіньтеся через VK або FB</div>
	<div style="display: none">
		<?php $this->widget('application.extensions.eauth.EAuthWidget', array( 'returnUrl' => Yii::app()->request->url)); ?>
	</div>
	<div class="login-btns">
		<a onclick="return check_member();" href="/login?service=vkontakte&returnUrl=http://<?=$_SERVER['HTTP_HOST'] . Yii::app()->request->url?>" class="vk vkontakte sziget-soc-login"></a>
		<a onclick="return check_member();" href="/login?service=facebook&returnUrl=http://<?=$_SERVER['HTTP_HOST'] . Yii::app()->request->url?>" class="fb facebook sziget-soc-login"></a>
	</div>
</div>

<div id="popup_vote_message" class="popup votes_popup">
	<div class="title part_top">Голосування</div>
	<div class="part_bottom">Дякуємо, що проголосували</div>
</div>

<div id="popup_shuring" class="popup">
	<div class="shur_content">
		Тисни «Поділитись у FB» або «Поділитись у VK» та розміщуй посилання на офіційній сторінці свого гурту. Тепер ти на крок ближче до Sziget Festival!
	</div>
	<div class="link_shur">
		<ul class="clearfix">
			<li class="sh_fb"><a onclick="Share.facebook('http://sziget.comma.com.ua');" href="javascript:void(0)"></a></li>
			<li class="sh_vk"><a onclick="Share.vkontakte('http://sziget.comma.com.ua');" href="javascript:void(0)"></a></li>
		</ul>
	</div>
</div>


<div id="fancy_memb" class="popup">
	<div class="des_memb scroll_popup">
	</div>
</div>

<div id="fancy_gallery" class="popup">
	<div class="slider_popup">
		<ul id="bxslider">
			<li><img src="/content/gal/1.jpg" /></li>
			<li><img src="/content/gal/2.jpg" /></li>
			<li><img src="/content/gal/3.jpg" /></li>
			<li><img src="/content/gal/4.jpg" /></li>
			<li><img src="/content/gal/5.jpg" /></li>
			<li><img src="/content/gal/6.jpg" /></li>
			<li><img src="/content/gal/7.jpg" /></li>
			<li><img src="/content/gal/8.jpg" /></li>
			<li><img src="/content/gal/9.jpg" /></li>
			<li><img src="/content/gal/10.jpg" /></li>
			<li><img src="/content/gal/11.jpg" /></li>
			<li><img src="/content/gal/12.jpg" /></li>
			<li><img src="/content/gal/13.jpg" /></li>
			<li><img src="/content/gal/14.jpg" /></li>
			<li><img src="/content/gal/16.jpg" /></li>
			<li><img src="/content/gal/17.jpg" /></li>
			<li><img src="/content/gal/18.jpg" /></li>
			<li><img src="/content/gal/19.jpg" /></li>
			<li><img src="/content/gal/20.jpg" /></li>
			<li><img src="/content/gal/21.jpg" /></li>
			<li><img src="/content/gal/22.jpg" /></li>
			<li><img src="/content/gal/23.jpg" /></li>
		</ul>
	</div>
</div>

<script src="<?=Yii::app()->theme->baseUrl?>/js/device.min.js"></script>
<script src="<?=Yii::app()->theme->baseUrl?>/js/jquery.mb.YTPlayer.js"></script>
<script src="<?=Yii::app()->theme->baseUrl?>/js/custom<?=(Yii::app()->params['js_version'] ? '.v.'.Yii::app()->params['js_version'] : '')?>.js"></script>
<script src="<?=Yii::app()->theme->baseUrl?>/js/jquery.fancybox.pack.js"></script>
<script src="<?=Yii::app()->theme->baseUrl?>/js/jquery.fancybox-media.js"></script>
<script src="<?=Yii::app()->theme->baseUrl?>/js/jquery.bxslider.min.js"></script>
<script src="<?=Yii::app()->theme->baseUrl?>/js/jquery.mCustomScrollbar.js"></script>
<script src="<?=Yii::app()->theme->baseUrl?>/js/script<?=(Yii::app()->params['js_version'] ? '.v.'.Yii::app()->params['js_version'] : '')?>.js"></script>

</body>
</html>

