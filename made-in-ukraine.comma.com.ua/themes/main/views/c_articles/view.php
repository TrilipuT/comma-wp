<div class="big_title">
    <?=$Article->transfer->name?>
</div>

<div class="article clearfix"> 
    <?php $this->widget('application.components.widgets.Banner'); ?> 
                 
    <div class="article-img" <?=$Article->image_filename;?> >
        <?php if(!empty($Article->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::PATH_IMAGE.$Article->image_filename)): ?>
            <div class="item-img">
                <img src="<?=Article::PATH_IMAGE.$Article->image_filename;?>" alt="<?=$Article->transfer->name?>"/>
            </div>
        <?php endif;?> 
        <div class="article-info <?=$Article->light ? '' : 'item_bg_black' ?>" style="border: 3px solid  <?=$Article->rubric->color;?>;">
            <div class="article-rubric">
                <b style="background: <?=$Article->rubric->color;?>;"></b>
                    <?php if($Article->rubric):?>
                        <a href="<?=$Article->rubric->getItemUrl()?>" >
                            <?=$Article->rubric->transfer->name;?>
                        </a>
                    <?php endif;?> 
                    <b style="background: <?=$Article->rubric->color;?>;"></b>
            </div>
            <div class="article-title">
                <?=$Article->transfer->annotation?>
            </div>
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
    </div>

    <div class="article-info-block">

        <?php if($authorsItems):?>
            <div class="article-container authors-<?=count($authorsItems)?>"> 
                <?php foreach($authorsItems as $Authors):?>
                    <div class="article-author">
                        <a href="<?=$Authors->getItemUrl()?>">
                            <?php if(!empty($Authors->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Authors::PATH_IMAGE_MINI.$Authors->image_filename)): ?>
                                <div class="item-img">                                 
                                   <img src="<?=Authors::PATH_IMAGE_MINI.$Authors->image_filename;?>" alt="<?=$Authors->transfer->getName()?>"/>                                
                                </div> <br>
                            <?php endif;?> 
     
                            <div class="item-name">
                                <?=$Authors->transfer->getName()?>
                            </div>
                        </a>
                    </div>
                <?php endforeach;?>
      
            </div> 
        <?php endif;?>

        <div class="article-quote">
            <?=$Article->transfer->description?>
        </div> 

    </div>
    <div class="clear"></div>

    <?php if($Article->interview):?>
        <div class="quotes_line"><b></b><span></span><b></b></div>
    <?php else:?>
        <div class="separator" style="background: <?=$Article->rubric->color;?>;"></div>
    <?php endif;?>

    
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

    <?php if ( $this->featuresEnabled['nativeAds'] ): ?>
        <div class="infeedl--placement" data-infeedl-placement="<?php echo $this->infeedl_ids['article_placement']?>"></div>
    <?php endif; ?>

    <?php if(count($otherItems) > 0):?>
    <div class="simple_title">
        <b style="background: <?=$Article->rubric->color;?>;"></b><?=Yii::t('app','in_thema');?><b style="background: <?=$Article->rubric->color;?>;"></b>
    </div>

    <div class="articles container_for_grid clearfix">        
        <?php $this->renderPartial('/site/_article_other_items', array('items' => $otherItems)); ?>  
    </div>
    <?php endif;?>

    <?php if(!$prev):?>
        <?php $this->widget('application.components.widgets.Comments', array('returnUrl' => Yii::app()->request->url, 'type' => 'article', 'data_id' => $Article->id, 'countComments' => $Article->comments_num )); ?> 
    <?php endif;?>
</div>

