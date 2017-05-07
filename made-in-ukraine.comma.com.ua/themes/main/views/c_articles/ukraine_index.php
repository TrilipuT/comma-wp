<body class="new_design with_topback">
    <div class="wrapper">

        <div class="topback">
            <div class="video-background"></div>
        </div>
        <script type="text/javascript">
            $('.video-background').videobackground({
                    videoSource: [['/ukraine/images/made_in_ukraine.webm', 'video/webm'],
                    ['/ukraine/images/made_in_ukraine.ogg', 'video/ogg'],
                    ['/ukraine/images/made_in_ukraine.m4v', 'video/mp4']],
                    resizeTo: 'window',
                    resize: false,
                    preload: 'auto',
                    loop: true,
                    //poster: '/ukraine/images/fullsize.jpg',
                    loadedCallback: function() {
                        $(this).videobackground('mute');
                    }
                });
</script>

        <?php $this->widget('application.components.widgets.UkraineHeader', array()); ?>  

        <div class="height100">

            <div class="header_holder">
                <div class="how_to_logo"><b></b></div>
            </div>

            <section class="content">

                <div class="how-about">
                    <div class="title">О проекте</div>
                    <?= $Rubrics->transfer->content ?>
                </div>

                <div class="how-content">
                    <?php $cnt = 1; foreach ($result['items'] as $a ): ?>
                    <div class="item <? if ( $cnt % 4 > 1 ) { ?> reverse <? } ?>">
                        <div class="item-img">
                            <img src="<?=Article::ICON480x480.$a->image_filename;?>" alt=""/>
                            <div><a href="<?=$a->getItemUrl()?>" target="_self">Читать дальше<b></b></a></div>
                        </div>
                        <div class="item-info">
                            <div class="item-title">
                                <a href="<?=$a->getItemUrl()?>" target="_self"><?= $a->transfer->name?></a>
                            </div>
                            <div class="item-text">
                                <?= $a->transfer->annotation ?>
                            </div>
                            <div class="info_line">
                                <div class="item-views">
                                    <?= $a->views_num ?>
                                </div>
                                <div class="item-comments">
                                    <?= $a->comments_num ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $cnt++; ?>                    
                    <?php endforeach; ?>
                </div>
          <div class="to_top-wrapper">
                    <div class="to_top">Наверх<b></b></div>
                </div>

            </section>

            <footer class="footer">
                <a href="http://comma.com.ua/" target="_blank" class="footer-logo"></a>
                <div class="footer-links">
                    Подписывайтесь:
                    <a href="https://www.facebook.com/comma.com.ua" target="_blank" class="fb"></a>
                    <a href="https://twitter.com/comma_com_ua" target="_blank" class="tw"></a>
                    <a href="https://vk.com/commacomua" target="_blank" class="vk"></a>
                </div>

            </footer>


<?php $this->widget('application.components.widgets.UkraineAboutPopup', array()); ?>
