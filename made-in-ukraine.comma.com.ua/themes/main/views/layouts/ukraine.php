<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/ukraine/" target="_self">
    <meta charset="utf-8" />
    <link href="/ukraine/css/fav.ico" rel="shortcut icon" type="image/x-icon" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <link href="css/main_ukraine.css" rel="stylesheet" />
    <link href="css/social-likes_birman.css" rel="stylesheet" />

    <meta name="description" content="<?php echo CHtml::encode($this->metaDescription); ?>">
    <meta name="keywords" content="<?php echo CHtml::encode($this->metaKeywords); ?>">

    <meta property="og:title" content="<?php echo CHtml::encode($this->og_title); ?>" /> 
    <meta property="og:image" content="<?=($this->og_image != '' ? $this->og_image : "http://comma.com.ua/img/share_ukraine.png")?>" />
     
    <meta property="og:type" content="website" />
    <meta property="og:description" content="<?php echo CHtml::encode($this->og_desc); ?>" /> 

    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="js/css_browser_selector.js"></script>
    <script src="js/modernizr.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/sly.min.js"></script>
    <script src="js/scripts_ukraine.js?2"></script>
    <script src="js/social-likes.min.js"></script>
    <script src="js/jquery.videobackground.js"></script>
    <script src="js/ajax.js"></script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-49401815-1', 'comma.com.ua');

        ga('require', 'displayfeatures');
        ga('send', 'pageview');
    </script>  

    <script type="text/javascript">
        var lang = '<?=Yii::app()->language?>';
    </script>
</head>

<!-- View -->
   <?php echo $content ?>


    <div class="popup popup-login"> <!-- opened -->
        <div class="popup-inner">
            <div class="popup-center">
                <div class="popup-back"></div>
                <div class="popup-close"></div>
                <div class="popup-box">

                    <div  class="user_login">
                        <div class="user_login-title">
                            Чтобы оставить и оценить комменарий,<br>авторизируйтесь
                        </div>
                        <?php $this->widget('application.extensions.eauth.EAuthWidget', array( 'returnUrl' => Yii::app()->request->url)); ?>
                        <div class="user_login-btns">
                            <a href="/login?service=facebook&returnUrl=<?=Yii::app()->request->url?>" class="fb facebook"><b></b></a>
                            <a href="/login?service=vkontakte&returnUrl=<?=Yii::app()->request->url?>" class="vk vkontakte"><b></b></a>
                        </div>
                    </div>
                   <div id="message" class="message">
                        <div class="user_login-title">
                            
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
        <?php if ($this->featuresEnabled['nativeAds']): ?>
           <script src="//cdn.infeedl.com/js/infeedl.min.js" crossorigin></script>
        <?php endif; ?>
</body>
</html>
