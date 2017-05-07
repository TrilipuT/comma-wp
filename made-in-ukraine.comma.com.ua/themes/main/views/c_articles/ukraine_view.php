<body class="new_design">
    <div class="topback_small">
          <?php if(!empty($Article->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::PATH_IMAGE.$Article->image_filename)): ?>
            <div>
                <img src="<?=Article::IMAGE1050.$Article->image_filename;?>" alt="<?=$Article->transfer->name?>"/>
            </div>
        <?php endif;?> 
        
    </div>

    <?php $this->widget('application.components.widgets.UkraineHeader', array()); ?>  

    <div class="height100">

        <div class="header_holder"></div>

        <section class="content">

            <div class="article-bigimg">
                <div class="table">
                    <div class="cell">
                <!--<div class="article-info"> -->
                    <div class="article-title">
                        <?=$Article->transfer->name?>
                    </div>
                    <div class="article-about">
                        <?=$Article->transfer->description?>
                    </div>
                    <div class="info_line">
                        <div class="info_line-date">
                            <?=$Article->getDate('name')?>
                        </div>
                        <div class="item-views">
                            <?=$Article->views_num?>
                        </div>
                        <div class="item-comments">
                            <?=$Article->comments_num?>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="article clearfix">
                <div class="article-topinfo">
                    <?php foreach($authorsItems as $Authors): ?>
                    <?php if ( $Authors->photographer == 0 ): ?>
                    <div class="topinfo-photo">Автор: <a href="http://comma.com.ua<?=$Authors->getAuthorUrl()?>"><span><?=$Authors->transfer->getName()?></span></a></div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php foreach($authorsItems as $Authors): ?>
                    <?php if ( $Authors->photographer == 1 ): ?>
                    <div class="topinfo-author">Фото: <a href="http://comma.com.ua<?=$Authors->getAuthorUrl()?>"><span><?=$Authors->transfer->getName()?></span></a></div>
                    <?php endif; ?>
                    <?php endforeach; ?>

                    <div class="likes">
                        <div class=" social-likes">
                            <div class="fb l-fb facebook" title="Поделиться ссылкой на Фейсбуке">Facebook</div>
                            <div class="tw l-tw twitter" title="Поделиться ссылкой в Твиттере">Twitter</div>
                            <div class="vk l-vk vkontakte" title="Поделиться ссылкой во Вконтакте">Вконтакте</div>
                        </div>
                    </div>
                </div>

                <div class="article-content">
                    <?=$Article->textOutput($Article->transfer->content)?>
                </div>

                <div class="likes_n_tags">
                    <?php $this->widget('application.components.widgets.Socials'); ?>
                    <!--<?php if($tags):?>
                    <div class="tags">
                        <div class="tags-title"><?=Yii::t('app','tags');?>:</div>
                        <div class="tags-block">
                            <?php foreach($tags as $Tags):?>
                            <a href="<?=$Tags->getItemUrl()?>">
                                <?=$Tags->transfer->name?>
                            </a>
                        <?php endforeach;?>
                    </div>
                </div>
            <?php endif;?> -->
        </div>

            <?php if ( $this->featuresEnabled['nativeAds'] ): ?>
                <div class="infeedl--placement" data-infeedl-placement="<?php echo $this->infeedl_ids['article_placement']?>"></div>
            <?php endif; ?>

                <?php if(!$prev):?>
                <div class="comments">
                    <?php $this->widget('application.components.widgets.Comments', array('returnUrl' => Yii::app()->request->url, 'type' => 'article', 'data_id' => $Article->id, 'countComments' => $Article->comments_num )); ?> 
                </div>
            <?php endif;?>

            </div>

        </section>

        <footer class="footer">
            <a href="/" class="footer-logo"></a>
            <div class="footer-links">
                    Подписывайтесь:
                    <a href="https://www.facebook.com/comma.com.ua" target="_blank" class="fb"></a>
                    <a href="https://twitter.com/comma_com_ua" target="_blank" class="tw"></a>
                    <a href="https://vk.com/commacomua" target="_blank" class="vk"></a>
                </div>
        </footer>

    </div>

    <div class="to_top_fixed"></div>

    <?php $this->widget('application.components.widgets.UkraineAboutPopup', array()); ?>


   