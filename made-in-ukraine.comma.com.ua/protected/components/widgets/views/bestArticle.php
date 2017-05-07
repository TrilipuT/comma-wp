 <?php if($articleItems):?> 
    <div class="scroll_switcher-item">
        <div class="news">
            <div class="news-title"><?=Constants::getItemByKey('best_article')?></div>
            <?php foreach($articleItems as $Article):?>
                <div class="item">
                    <?php if(!empty($Article->icon_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::PATH_ICON_MINI.$Article->icon_filename)): ?>
                        <div class="item-img">
                            <img src="<?=Article::PATH_ICON_MINI.$Article->icon_filename;?>" alt="<?=$Article->transfer->name?>"/>
                        </div>
                    <?php endif;?> 
                    <div class="item-title">
                        <?php if($Article->rubric):?>
                            <a href="<?=$Article->rubric->getItemUrl()?>" >
                                <?=$Article->rubric->transfer->name;?>
                            </a>
                        <?php elseif($Article->blog):?>
                            <a href="<?=Blogers::getSectionUrl()?>" class="item-rubric">  
                                Блоги
                            </a>
                        <?php endif;?>
                    </div>
                    <div class="item-text">
                        <?=$Article->transfer->name?>
                    </div>
                    <a href="<?=$Article->getItemUrl()?>" class="item-link"></a>
                </div>
            <?php endforeach;?>
        </div>
    </div>
<?php endif;?> 
