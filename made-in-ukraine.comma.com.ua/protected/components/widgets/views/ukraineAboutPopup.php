<div class="popup about-popup"> <!-- opened -->
        <div class="popup-inner">
            <div class="popup-center">
                <div class="popup-back"></div>
                <div class="popup-close"></div>
                <div class="popup-box popup-about">
                    <div>
                        <div class="logo"></div>
                        <div class="title">
                            <?= $title ?>
                            
                        </div>
                        <div class="people">
                            <?php foreach ( $authors as $author ): ?>
                            <? if ( $author->photographer == 0 ): ?>
                            <div class="item">
                                <div class="item-img"><a href="http://comma.com.ua<?=$author->getItemUrl()?>"><img src="<?= Authors::PATH_IMAGE_SMALL.$author->image_filename?>" alt=""/></a></div>
                                <div class="item-name"><a href="http://comma.com.ua<?=$author->getItemUrl()?>"><?=$author->transfer->name ?><br/><?=$author->transfer->last_name?></a></div>
                                <div class="item-job"><?php if ( $author->photographer == 0 ): ?>автор<? else: ?>фотограф<? endif; ?></div>
                            </div>
                            <? endif; ?>
                            <? endforeach; ?>
                            <?php foreach ( $authors as $author ): ?>
                            <? if ( $author->photographer == 1 ): ?>
                            <div class="item">
                                <div class="item-img"><a href="http://comma.com.ua<?=$author->getPhotografUrl()?>"><img src="<?= Authors::PATH_IMAGE_SMALL.$author->image_filename?>" alt=""/></a></div>
                                <div class="item-name"><a href="http://comma.com.ua<?=$author->getPhotografUrl()?>"><?=$author->transfer->name ?><br/><?=$author->transfer->last_name?></a></div>
                                <div class="item-job"><?php if ( $author->photographer == 0 ): ?>автор<? else: ?>фотограф<? endif; ?></div>
                            </div>
                            <? endif; ?>
                            <? endforeach; ?>
                        </div>
                        <div class="text">
                            <?= $text ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>