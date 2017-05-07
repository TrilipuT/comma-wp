<div class="video clearfix">
    <div class="video-title1">
        <?=$Videos->transfer->name?>
    </div>
    <?php if($Videos->transfer->sub_name != NULL):?>
        <div class="video-title2">
            <?=$Videos->transfer->sub_name?>
        </div>
    <?php endif;?>

    <?php if(!empty($Videos->video_code) ): ?>
        <div class="video-video">
            <?=$Videos->video_code;?>
        </div>
    <?php endif;?>

    <?php $this->widget('application.components.widgets.Banner'); ?> 

    <div class="video-content">
        <div class="info_line">

            <?php if($VideoCats):?>
                <div class="info_line-rubric">
                    <a href="<?=Videos::getSectionUrl();?>">
                        <?=$VideoCats->transfer->name?>
                    </a><b></b>
                </div>
            <?php endif;?>

            <div class="info_line-date">
                <?=$Videos->getDate('name')?>
            </div>
            <div class="item-comments">
                <?=$Videos->comments_num?>
            </div>
            <div class="item-views">
                <?=$Videos->views_num?>
            </div>
        </div>
        <div class="video-text">
            <?=$Videos->textOutput($Videos->transfer->content)?>
        </div>
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

<?php if($otherItems):?>
    <div class="simple_title item_gray">
        <b></b><?=Yii::t('app','in_thema');?><b></b>
    </div>

    <div class="articles container_for_grid clearfix"> 
     
        <?php foreach($otherItems as $key => $_Videos):?>  

            <div class="grid_1of4 <?=(($key == 3) ? 'grid_last' : '' )?>">
                <?php $this->renderPartial('_item', array('Videos' => $_Videos)); ?>
            </div>

        <?php endforeach;?> 
    </div>
<?php endif;?>

  <?php $this->widget('application.components.widgets.Comments', array('returnUrl' => Yii::app()->request->url, 'type' => 'video', 'data_id' => $Videos->id, 'countComments' => $Videos->comments_num )); ?>

</div>

