
<div class="articles container_for_grid clearfix">
    <div class="grid_2of4">
        
        <?php if($mainArticle):

                $color = "#961f20";
                if(!$mainArticle->blog){
                    $color = $mainArticle->rubric->color;
                }  
            ?>
            <div class="item item_blue item_big item_bg_black">
                <?php if(!empty($mainArticle->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::IMAGE488x423.$mainArticle->image_filename)):?>
                    <div class="item-img">
                        <img src="<?=Article::IMAGE488x423.$mainArticle->image_filename;?>" alt=""/>
                        <b></b>
                    </div>
                <?php elseif(!empty($mainArticle->icon_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::PATH_ICON.$mainArticle->icon_filename)):?>
                    <div class="item-img">
                        <img src="<?=Article::PATH_ICON.$mainArticle->icon_filename;?>" alt=""/>
                        <b></b>
                    </div> 
                <?php endif;?>


                <div style="border: 5px solid <?=$color?>;" class="item-border <?=$mainArticle->light ? '' : 'item_bg_black' ?>">
                    <?php if($Article->rubric->transfer->name != ''):?>
                        <div class="item-title">
                            <div class="line"></div>
                            <a href="#" class="item-rubric">
                                <?=$Article->rubric->transfer->name;?>
                            </a>
                            <div class="line"></div>
                        </div>
                    <?php endif;?>
                    <div class="item-text">
                        <? if ($mainArticle->main_title): ?>
                        <?= $mainArticle->main_title ?>
                        <? else: ?>
                        <?= $mainArticle->transfer->name?>
                        <? endif; ?>
                    </div>
                </div>
                <a href="<?=$mainArticle->getItemUrl()?>"  class="item-link"></a>
            </div>
        <?php endif;?>

        <?php if(count($colsArray[0]) > 0):?>
            <div class="grid_1of4">
                <?php $this->renderPartial('_article_items', array('items' => $colsArray[0], 'url' => $this->url)); ?> 
            </div>
        <?php endif;?>

        <?php if(count($colsArray[1]) > 0):?>
            <div class="grid_1of4"> 
                <?php if ($this->featuresEnabled['nativeAds']): ?>
                    <?php 

                    //$colsArray[1] = array_slice($colsArray[1], -1); 
                    ?>
                    <div class="item" style="display:none" id="infeedl-placement-parent">
                        <div class="infeedl--placement" id="infeedl-placement"></div>
                        <script src="//cdn.infeedl.com/js/infeedl.min.js" crossorigin></script>
                    <script type="text/javascript">
                    var node = document.getElementById("infeedl-placement");
                    var placement = new Infeedl.Placement("<?php echo $this->infeedl_ids['main_placement']?>", node);
                    placement.fetch({
                      onSuccess: function(placement_id) {
                         console.log('helloworld');
                         $('#infeedl-placement-parent').show();
                         $('#infeedl-placement-parent').next().hide();
                      },
                      onFailure: function(placement_id) {}
                    });
                    </script>
                    </div>
                <?php endif; ?>
                <?php foreach ($colsArray[1] as $item): ?>                  
                    <?php $this->renderPartial('_article_item', array('Article' => $item, 'url' => $this->url)); ?>
                <?php endforeach; ?>
            </div>
        <?php endif;?>
    </div>
  
    <?php if(count($colsArray[2]) > 0):?>
        <div class="grid_1of4"> 
            <?php $this->renderPartial('_article_items', array('items' => $colsArray[2], 'url' => $this->url)); ?>     
        </div>
    <?php endif;?>

   
    <?php if(count($colsArray[3]) > 0):?>
        <div class="grid_1of4 grid_last"> 
            <?php $this->widget('application.components.widgets.Banner'); ?> 
            <?php $this->renderPartial('_article_items', array('items' => $colsArray[3], 'url' => $this->url)); ?>     
        </div>
    <?php endif;?>  
</div>

<?php if($newsItems):?>
    <div class="slider news">
        <div class="slider-title">
            <a href="<?=News::getSectionUrl();?>"><?=Yii::t('app','main_news');?><b></b></a>
        </div>
        <div class="slider-content">
            <div class="floater">
                
                <?php foreach($newsItems as $News):?>
                    <a href="<?=$News->getItemUrl();?>" class="item">
                        <?php if(!empty($News->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].News::PATH_IMAGE.$News->image_filename)): ?>
                            <div class="item-img">
                                <img src="<?=News::PATH_IMAGE.$News->image_filename;?>" alt="<?=$News->transfer->name?>"/>
                            </div>
                        <?php endif;?>

                        <div class="item-title">
                            <?=$News->getTime();?>
                        </div>
                        <div class="item-text">
                            <?=$News->transfer->name?>
                        </div>
                    </a>
                <?php endforeach;?>
            </div>
        </div>

        <?php if(count($newsItems) > 4):?>
            <div class="prev"></div>
            <div class="next"></div>
        <?php endif;?>
    </div>
<?php endif;?>


<?php if($videoItems):?>
    <div class="switch clearfix">
        <div class="switch-inner">
            <div class="switch-title">
                <a href="<?=Videos::getSectionUrl();?>"><?=Yii::t('app','main_videos');?><b></b></a>
            </div>
            
            
            <div class="switch-links">
                <ul>
                    <?php $key = 0;
                         foreach($videoItems as $item): 

                            if(!$item['video']) continue;

                            $select = '';
                            //if($key == 0){
                            if($item['main']){
                                $select = 'selected';
                            }


                            $key++;
                        ?>
                        <li class="<?=$select?>">
                            <b></b>
                            <span>
                                <a href="#">
                                    <?=$item['cat']->transfer->menu_name;?>
                                    <b></b>
                                </a>
                            </span>
                        </li>
                    <?php endforeach;?> 
                </ul>
            </div>
            

            <div class="switch-content js-video-main-container">
                <?php 
                    $key = 0;
                    foreach($videoItems as $item): 

                            if(!$item['video']) continue;

                            $select = '';
                            //if($key == 0){
                            if($item['main']){
                                $select = 'selected';
                            }
                            $key++;
                        ?> 
                    <div class="item <?=$select?> <?=$item['cat']->code_name?>">
                        
                        <?php if(!empty($item['video']->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Videos::PATH_IMAGE.$item['video']->image_filename)): ?>
                            <div class="item-video">
                                <img src="<?=Videos::PATH_IMAGE.$item['video']->image_filename;?>" alt="<?=$item['video']->transfer->name;?>"/>
                                <a href="<?=$item['video']->getItemUrl();?>"></a>
                            </div>
                        <?php endif;?> 
                        

                        <div class="item-title">
                            <?php if($item['video']->transfer->sub_name != "" && $item['video']->transfer->name != ""):?> 
                                    <?=$item['video']->transfer->name?> «<?=$item['video']->transfer->sub_name;?>»                               
                            <?php else:?>

                                <?=$item['video']->transfer->name;?>

                            <?php endif;?>

                            
                        </div>

                        <div class="item-text">
                            <?=$item['video']->transfer->description;?>
                        </div>
                    </div>
                <?php endforeach;?> 

            </div>

        </div>
    </div>
<?php endif;?>

<?php if(count($rowsArray) > 0):?>
    <div class="articles container_for_grid clearfix">
        <div class="grid_3of4 js-main-rows-block">
            <?php $this->renderPartial('_article_items2', array('items' => $rowsArray, 'url' => $this->url, 'i' => 0)); ?>       
        </div>

        <?php if(count($lastCol) > 0):?>
            <div class="grid_1of4 grid_last"> 
                

                <!--<a href="#" class="banner">
                    <img src="http://placehold.it/240x70" alt=""/>
                </a>-->

                <?php //$this->widget('application.components.widgets.Banner'); ?> 
                <?php $this->renderPartial('_article_items', array('items' => $lastCol, 'url' => $this->url)); ?>     
            </div>
        <?php endif;?>    
    </div> 
<?php endif;?>

<?php if($article['total_pages'] > 0): ?>
    <?php $this->widget('application.components.widgets.Pagination', array('page' => $article['page'], 'total_pages' => $article['total_pages'], 'remains' => $article['remains'], 'remains_class' => 'js-main-remains' )); ?> 
<?php endif;?>

<?php if($articleGallery):?>
    <div class="slider photos">
        <div class="slider-title">
            <a href="<?=Gallery::getSectionUrl();?>"><?=Yii::t('app','main_gallery');?><b></b></a>
        </div>
        <div class="slider-content">
            <div class="floater">
                <?php foreach($articleGallery as $Gallery):?>

                    <a href="<?=$Gallery->getItemUrl();?>" class="item">
                        <div class="item-img">
                            <?php if(!empty($Gallery->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Gallery::PATH_IMAGE.$Gallery->image_filename)): ?>
                                <img src="<?=Gallery::PATH_IMAGE.$Gallery->image_filename;?>" alt="<?=$Gallery->transfer->name?>"/>
                            <?php endif;?>
                            <div class="item-info">
                                <div>
                                    <div class="item-date">
                                        <?=$Gallery->getDate();?>
                                    </div>
                                    <?php if($Gallery->transfer->place != ''):?>
                                        <div class="item-place">
                                            @<?=$Gallery->transfer->place?>
                                        </div>
                                    <?php endif;?>
                                    <div class="item-comments">
                                        <?=$Gallery->comments_num?>
                                    </div>
                                    <div class="item-views">
                                        <?=$Gallery->views_num?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item-title">
                            <?=$Gallery->transfer->name?>
                        </div>
                    </a>         
                <?php endforeach;?>    
            </div>
        </div>
        <?php if(count($articleGallery) > 4):?>
            <div class="prev"></div>
            <div class="next"></div>
        <?php endif;?>
    </div>
<?php endif;?>