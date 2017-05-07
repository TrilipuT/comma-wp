<div class="big_title">
    <?=$Section->transfer->menu_name?>
</div>

<div class="container_for_grid clearfix">
    <div class="grid_3of4">
        <div class="about">
            <?=$Section->textOutput($Section->transfer->content)?>
        </div>
    </div>
    <div class="grid_1of4 grid_last"> 
        <?php $this->widget('application.components.widgets.Banner'); ?>  
    </div>
</div>
<?php if($authorsItems):?>
    <div class="authors">
        <div class="title">
            <?=Constants::getItemByKey('about_edition')?>
        </div>
        <div class="authors-inner">
            <a href="<?=Authors::getSectionUrl()?>" class="authors-all"><?=Constants::getItemByKey('about_all-authors')?></a>
            
            <?php foreach($authorsItems as $Authors):?>
                <div class="item">
                    <?php if(!empty($Authors->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Authors::PATH_IMAGE.$Authors->image_filename)): ?>
                        <div class="item-img">
                            <a href="<?=$Authors->getItemUrl()?>">
                                <img src="<?=Authors::PATH_IMAGE.$Authors->image_filename;?>" alt="<?=$Authors->transfer->name?>"/>
                            </a>
                        </div>
                    <?php endif;?>  
                    <a href="<?=$Authors->getItemUrl()?>">
                        <div class="item-name"><b></b>
                            <span> 
                                <?=$Authors->transfer->getName()?>                            
                            </span>
                        </div>
                    </a>
                    <div class="item-job"><?=$Authors->transfer->post?></div>
                </div> 
            <?php endforeach;?>
        </div>
    </div>
<?php endif;?>

<div class="contacts"> 
    <div class="title">
        <?=Constants::getItemByKey('about_contact_Information')?> 
    </div>
    <?=$Section->textOutput($Section->transfer->seo_text)?>
</div>