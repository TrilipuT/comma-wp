<!DOCTYPE html>
<html lang="<?=Yii::app()->language?>">
<head>
	<html lang="<?=Yii::app()->language?>">
	<meta name="robots" content="index, follow">
    <meta charset="utf-8" />
    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <meta name="description" content="<?php echo CHtml::encode($this->metaDescription); ?>">
    <meta name="keywords" content="<?php echo CHtml::encode($this->metaKeywords); ?>">

    <meta property="og:title" content="<?php echo CHtml::encode($this->og_title); ?>" />
    <meta property="og:image" content="<?=($this->og_image != '' ? $this->og_image : "http://comma.com.ua/img/share.png")?>" />

    <meta property="og:type" content="website" />
    <meta property="og:description" content="<?php echo CHtml::encode($this->og_desc); ?>" />

    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">

    <script type="text/javascript">
        var lang = '<?=Yii::app()->language?>';
    </script>

    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon">


    <link href="/css/main.css?6" rel="stylesheet" />
    <link href="/css/social-likes_birman.css" rel="stylesheet" />


	<script src="/js/cast_sender.js"></script>

    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="/js/css_browser_selector.js"></script>
    <script src="/js/modernizr.js"></script>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="/js/sly.min.js"></script>
    <script src="/js/scripts.js?1"></script>

    <!--<script src="/js/buttons.js"></script> -->
    <script src="/js/social-likes.min.js"></script>
    <script src="/js/ajax.js?1"></script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-49401815-1', 'comma.com.ua');

        ga('require', 'displayfeatures');
        ga('send', 'pageview');
    </script>
</head>

<body <?=(!empty($this->background_style) ? 'class="bg_banner"' : '')?> style="<?=$this->background_style?>">
	<?php if($this->main_page):?>
		<!-- (C)2000-2015 Gemius SA - gemiusAudience / comma.com.ua / Glavnaja stranica sajta -->
		<script type="text/javascript">
			<!--//--><![CDATA[//><!--
			var pp_gemius_identifier = 'bQqV74re5R0lvrExvD2l.Md.XfZw3a_BdO1wMAic4_H.G7';
			// lines below shouldn't be edited
			function gemius_pending(i) { window[i] = window[i] || function() {var x = window[i+'_pdata'] = window[i+'_pdata'] || []; x[x.length]=arguments;};};
			gemius_pending('gemius_hit'); gemius_pending('gemius_event'); gemius_pending('pp_gemius_hit'); gemius_pending('pp_gemius_event');
			(function(d,t) {try {var gt=d.createElement(t),s=d.getElementsByTagName(t)[0],l='http'+((location.protocol=='https:')?'s':''); gt.setAttribute('async','async');
				gt.setAttribute('defer','defer'); gt.src=l+'://ua.hit.gemius.pl/xgemius.js'; s.parentNode.insertBefore(gt,s);} catch (e) {}})(document,'script');
			//--><!]]>
		</script>
	<?php else: ?>
		<!-- (C)2000-2015 Gemius SA - gemiusAudience / comma.com.ua / Pages -->
		<script type="text/javascript">
			<!--//--><![CDATA[//><!--
			var pp_gemius_identifier = 'dvY7KQNbMFyaLdoq._GIw9T4XqvNdq_17_eQ6.kuo7b.m7';
			// lines below shouldn't be edited
			function gemius_pending(i) { window[i] = window[i] || function() {var x = window[i+'_pdata'] = window[i+'_pdata'] || []; x[x.length]=arguments;};};
			gemius_pending('gemius_hit'); gemius_pending('gemius_event'); gemius_pending('pp_gemius_hit'); gemius_pending('pp_gemius_event');
			(function(d,t) {try {var gt=d.createElement(t),s=d.getElementsByTagName(t)[0],l='http'+((location.protocol=='https:')?'s':''); gt.setAttribute('async','async');
				gt.setAttribute('defer','defer'); gt.src=l+'://ua.hit.gemius.pl/xgemius.js'; s.parentNode.insertBefore(gt,s);} catch (e) {}})(document,'script');
			//--><!]]>
		</script>
	<?php endif;?>
	<?php if($this->background_link):?>
		<a class="bg_link" href="<?=$this->background_link;?>" target="_blank">
			<img src="/img/1x1.gif" border="0" width="100%" height="100%" />
		</a>
	<?php endif;?>
    <div class="height100">
    <?php //var_dump($this->topBanner); ?>
    <?php if ( $this->topBanner  
            && ( ($this->topBanner->source == 1 
                   && !empty($this->topBanner->file_banner)  
                   && file_exists($_SERVER['DOCUMENT_ROOT'].Banners::PATH_BANNER.$this->topBanner->file_banner) ) ) || 
                 $this->topBanner->source == 2 ):?>
    <?php $topB = $this->topBanner;
        $ext = explode('.', $topB->file_banner);
        $ext = $ext[count($ext)-1];

    ?>

        <div class="top_banner">
         <?php if ( $this->topBanner->source == 2 ): ?>
             <?php echo $this->topBanner->htmlcode; ?>
         <?php else: ?>
            <?php if ($ext == 'swf'): ?>
            <a href="#" class="">
                    <object
                            classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
                            codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0"
                            width="<?=$this->topBanner->width?>"
                            height="<?=$this->topBanner->height?>"
                            id="<?=$this->topBanner->file_banner?>"
                            align="middle">

                    <param name="allowScriptAccess" value="sameDomain" />
                    <param name="allowFullScreen" value="false" />
                    <param name="movie" value="<?=Banners::PATH_BANNER.$this->topBanner->file_banner?><?=($this->topBanner->target_url != null ? '?link1='.urlencode($this->topBanner->target_url) : '')?>" />
                    <param name="quality" value="high" />
                    <embed
                        src="<?=Banners::PATH_BANNER.$this->topBanner->file_banner?><?=($this->topBanner->target_url != null ? '?link1='.urlencode($this->topBanner->target_url) : '')?>"
                        quality="high"
                        width="<?=$this->topBanner->width?>"
                        height="<?=$this->topBanner->height?>"
                        name="<?=$this->topBanner->file_banner?>"
                        align="middle"
                        allowScriptAccess="sameDomain"
                        allowFullScreen="false"
                        type="application/x-shockwave-flash"
                        pluginspage="http://www.adobe.com/go/getflashplayer" />
                    </object>
            </a>
            <?php else: ?>
                <?php if($topB->target_url == ''):?>
                    <img class="banner__img" init-width="<?=$topB->width?>" width="100%<?=''//$Banners->width?>" init-height="<?=$topB->height?>"  src="<?=Banners::PATH_BANNER.$topB->file_banner?>" alt="<?=$topB->name?>" />
                <?php else:?>
                    <a href="<?=$topB->target_url?>" target="_blank">
                        <img class="banner__img" init-width="<?=$topB->width?>" width="100%<?=''//$Banners->width?>" init-height="<?=$topB->height?>"  src="<?=Banners::PATH_BANNER.$topB->file_banner?>" alt="<?=$topB->name?>" />
                    </a>
                <?php endif;?>
            <?php endif; ?>
          <?php endif; ?>
        </div>
    <?php endif;?>

    <?php $this->widget('application.components.widgets.Header', array( 'activeSection'         => $this->activeSection,
                                                                        'activeSubRubricsId'    => $this->activeSubRubricsId,
                                                                        'activeRubric'          => $this->activeRubric)); ?>

    <section class="content">

        <?php echo $content ?>

    </section>

    <footer class="footer">
        <a href="/" class="footer-logo"></a>
        <div class="socials">
            <a target="_blank" href="https://www.facebook.com/comma.com.ua" class="fb"></a>
            <a target="_blank" href="https://twitter.com/comma_com_ua" class="tw"></a>
            <a target="_blank" href="https://vk.com/commacomua" class="vk"></a>
            <a target="_blank" href="http://instagram.com/commacomua" class="in"></a>
            <a target="_blank" href="/rss/rss.xml" class="rss"></a>
        </div>

        <?php $this->widget('application.components.widgets.FooterMenu'); ?>

        <div class="footer-copyright">
            <div class="float-left">
                &copy; <?=((date('Y') > '2014') ? '2014 - ' : '')?><?=date('Y');?> Comma
            </div>
            <div class="footer-copyright-text">
                <?=Constants::getItemByKey('footer_text')?>
            </div>
        </div>
    </footer>
    <div class="popup "> <!-- opened -->
        <div class="popup-inner">
            <div class="popup-center">
                <div class="popup-box">
                    <div class="popup-close"></div>

                    <div  class="user_login">
                        <div class="user_login-title">Войдите, чтобы оставлять комменарии</div>
                        <?php $this->widget('application.extensions.eauth.EAuthWidget', array( 'returnUrl' => Yii::app()->request->url)); ?>
                        <div class="user_login-btns">
                            <a href="/login?service=facebook&returnUrl=http://<?=$_SERVER['HTTP_HOST'] . Yii::app()->request->url?>" class="fb facebook"><b></b><span>Войти через Facebook</span></a>
                            <span>или</span>
                            <a href="/login?service=vkontakte&returnUrl=http://<?=$_SERVER['HTTP_HOST'] . Yii::app()->request->url?>" class="vk vkontakte"><b></b><span>войти через вконтакте</span></a>
                        </div>
                    </div>

                    <div id="message" class="message">
                        <div class="user_login-title">
                            сообщение
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php

        $session = new CHttpSession;
        $session->open();
        if($session['user_deactive_popup']): $session['user_deactive_popup'] = false;?>
        <div class="popup user_deactive opened" style="display:block">
            <div class="popup-inner">
                <div class="popup-center">
                    <div class="popup-box">
                        <div class="popup-close"></div>

                        <div class="message">
                            <div class="user_ban">
                                По тем или иным причинам вы забанены. Если вы думаете, что это произошло по ошибке и хотите снова писать комментарии, напишите нам письмо.
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <?php endif;?>

        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.0";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        <!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter26704155 = new Ya.Metrika({id:26704155, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/26704155" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->

        <?php if ($this->featuresEnabled['nativeAds']): ?>
           <script src="//cdn.infeedl.com/js/infeedl.min.js" crossorigin></script>
        <?php endif; ?>

</div>

</body>
</html>
