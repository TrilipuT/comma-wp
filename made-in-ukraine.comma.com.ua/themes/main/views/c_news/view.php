<div class="container_for_grid margin_bottom_20 clearfix">
    <div class="grid_3of4">
        <div class="single_news">
            <div class="single_news-title">
                <?=$News->transfer->name?>
            </div>
            <div class="info_line">
                <div class="info_line-rubric">
                    <a href="<?=News::getSectionUrl();?>">
                        <?=$this->Section->transfer->name?>
                    </a><b></b>
                </div>
                <div class="info_line-date">
                    <?=$News->getTime()?>
                </div>
                <div class="item-comments">
                    <?=$News->comments_num?>
                </div>
                <div class="item-views">
                    <?=$News->views_num?>
                </div>
            </div>
            <div class="single_news-content"> 
                <?=$News->textOutput($News->transfer->content)?>
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


            <?php $this->widget('application.components.widgets.Comments', array('returnUrl' => Yii::app()->request->url, 'type' => 'news', 'data_id' => $News->id, 'countComments' => $News->comments_num )); ?>
        </div>
    </div>
    <div class="grid_1of4 grid_last">
        <div class="scroll_switcher">
            <div> 
                <div class="scroll_switcher-item">
                    <?php $this->widget('application.components.widgets.Banner'); ?> 
                </div>
                <?php if($lastNewsItems):?>
                    <div class="scroll_switcher-item">
                        <div class="news">
                            <div class="news-title">
                                <?=Yii::t('app','lasn_news');?>
                            </div> 
                            
                            <?php foreach($lastNewsItems as $LastNews):?>
                                <a href="<?=$LastNews->getItemUrl();?>" class="item">
                                    <?php if(!empty($LastNews->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].News::PATH_IMAGE.$LastNews->image_filename)): ?>
                                        <div class="item-img">
                                            <img src="<?=News::PATH_IMAGE.$LastNews->image_filename;?>" alt="<?=$LastNews->transfer->name?>"/>
                                        </div>
                                    <?php endif;?>

                                    <div class="item-title">
                                        <?=$LastNews->getTime();?>
                                    </div>
                                    <div class="item-text">
                                        <?=$LastNews->transfer->name?>
                                    </div>
                                </a>
                            <?php endforeach;?>                             
                        </div>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div> 


<?php if($otherItems):?>
    <div class="simple_title item_blue">
        <b></b><?=Yii::t('app','in_thema');?><b></b>
    </div>

 <div class="news news-other container_for_grid clearfix"> 
        
        <?php foreach($otherItems as $Obj):

            $time        = "";
            $img         = "";
            switch(get_class($Obj)){
                case 'News':
                    if(!empty($Obj->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].News::PATH_IMAGE.$Obj->image_filename)){
                        $img = News::PATH_IMAGE.$Obj->image_filename;
                    }
                    $time = $Obj->getTime();
                    break;
                case 'Article':
                    if(!empty($Obj->icon_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::PATH_ICON_MINI.$Obj->icon_filename)){
                        $img = Article::PATH_ICON_MINI.$Obj->icon_filename;
                    }
                    break;
                case 'Videos':
                    if(!empty($Obj->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Videos::PATH_IMAGE_ICON.$Obj->image_filename)){
                        $img = Videos::PATH_IMAGE_ICON.$Obj->image_filename;
                    }
                    break;
                case 'Gallery':
                    if(!empty($Obj->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Gallery::IMAGE_80x80.$Obj->image_filename)){
                        $img = Gallery::IMAGE_80x80.$Obj->image_filename;
                    }
                    break;
            }//end switch


            ?>
            <a href="<?=$Obj->getItemUrl();?>" class="item">
                <?php if($img != ""): ?>
                    <div class="item-img">
                        <img src="<?=$img;?>" alt="<?=$Obj->transfer->name?>"/>
                    </div>
                <?php endif;?>

                <div class="item-title">
                </div>
                <div class="item-text">
                    <?=$Obj->transfer->name?>
                </div>
            </a>
        <?php endforeach;?>                             
    </div>

<?php endif;?>

   
