<div class="big_title">
    <?=$Article->transfer->name?>
</div>

<div class="blogpost clearfix"> 
    <?php $this->widget('application.components.widgets.Banner'); ?> 
                 
    <div class="blogpost-img">
        <?php if(!empty($Article->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::PATH_IMAGE.$Article->image_filename)): ?>
            <div class="item-img">
                <img src="<?=Article::PATH_IMAGE.$Article->image_filename;?>" alt="<?=$Article->transfer->name?>"/>
            </div>
        <?php endif;?>  
    </div>

    <div class="blogpost-author <?=$Article->light ? '' : 'item_bg_black' ?>">
        <a href="<?=$Article->bloger->getItemUrl();?>" class="item-link">
            <?php if(!empty($Article->bloger->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Blogers::PATH_IMAGE_MINI.$Article->bloger->image_filename)): ?>
                <div class="item-img">
                    <img src="<?=Blogers::PATH_IMAGE_MINI.$Article->bloger->image_filename;?>" alt="<?=$Article->bloger->transfer->name?>"/>
                </div>
            <?php endif; ?> 

            <div class="item-name">
                <?=$Article->bloger->transfer->name?>
            </div>
        </a>
        <div class="info_line">
            <div class="info_line-date">
                <?=$Article->getDate('name')?>
            </div>
            <div class="item-comments">
                <?=$Article->comments_num?>
            </div>
            <div class="item-views">
                <?=$Article->views_num?>
            </div>
        </div>
    </div>  

    <div class="blogpost-quote">
        <?=$Article->transfer->description?>
    </div> 

    <div class="article-content text_center">  
        <?=$Article->textOutput($Article->transfer->content)?>      
    </div>

    <div class="likes_n_tags">
        <?php $this->widget('application.components.widgets.Socials'); ?>
        <?php if($tags):?>
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
        <?php endif;?>
    </div>
    <?php if(!$prev):?>
        <?php $this->widget('application.components.widgets.Comments', array('returnUrl' => Yii::app()->request->url, 'type' => 'article', 'data_id' => $Article->id, 'countComments' => $Article->comments_num )); ?>
    <?php endif;?>
    
</div>


<?php if($otherItems):?>
    <div class="simple_title item_red">
        <b></b><?=Yii::t('app','in_thema');?><b></b>
    </div>

    <div class="articles container_for_grid clearfix">
        <?php $this->renderPartial('/site/_article_other_items', array('items' => $otherItems)); ?> 
    </div> 
<?php endif;?>