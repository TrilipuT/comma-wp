<?php if($result['itemsCount'] > 0 ): 

        
    ?>    
    <div class="bg_white">
        <div class="simple_title item_orange">
            <b style="background: <?=$Rubrics->color;?>;"></b><?=Yii::t('app','new_article');?><b style="background: <?=$Rubrics->color;?>;"></b>
        </div> 
        
        <div class="articles container_for_grid clearfix">
            
            <?php foreach($result['items'] as $key=>$Article): 
                
                if($key == 4){
                    break;
                } 

                $color = "#961f20";
                if(!$Article->blog){
                    $color = $Article->rubric->color;
                }  

                $last = '';

                if($key == count($result['items']) || $key == 3 ){
                    $last = 'grid_last';
                }

                

                $gif = Article::PATH_IMAGE_GIF.$Article->gif_filename;
                $img = Article::PATH_ICON_SMALL.$Article->icon_filename;

                if(!empty($Article->gif_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].$gif)){
                    $issetImg   = true;  
                    $img = $gif;

                } else if(!empty($Article->icon_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].$img)){
                    $issetImg   = true;  
                } else {
                    $img = '';
                } 

                if($img != ''){
                    $size       = getimagesize($_SERVER['DOCUMENT_ROOT'].$img);
                    $width      = $size[0];
                    $height     = $size[1];
                    $style      = 'min-height:'.$height.'px;';
                    $style2     = 'min-width:'.$width.'px;';
                }


                ?>   
                
                <div class="grid_1of4 <?=$last;?>">
                    <div class="item ">
                        <?php if($issetImg): ?>
                            <div class="item-img" style="<?=$style2?>">
                                <img  width="<?=$width;?>" height="<?=$height;?>" src="<?=$img?>" alt="<?=$Article->transfer->name?>"/>
                            </div>
                        <?php endif;?>
                        <div class="item-title">
                            <div class="line" style="background: <?=$color;?>;"></div>
                            <div class="item-data">
                                <div class="item-comments">
                                    <?=$Article->comments_num;?>
                                </div>
                                <div class="item-views">
                                    <?=$Article->views_num;?>
                                </div>
                            </div>

                            <?php if($Article->rubric):?>
                                <a href="<?=$Article->rubric->getItemUrl()?>" class="item-rubric">
                                    <?=$Article->rubric->transfer->name;?>
                                </a>
                            <?php endif;?>

                        </div>
                        <?php if($Article->blog):?>
                            <a href="<?=$Article->bloger->getItemUrl()?>" class="item-author">
                                <?=$Article->bloger->transfer->name?>
                            </a>
                        <?php endif;?>
                        <div class="item-text">
                            <?=$Article->transfer->name?>
                        </div>
                        <a href="<?=$Article->getItemUrl()?>" class="item-link">
                            <div style="border: 5px solid<?=$color;?>;" class="item-border"></div>
                        </a>
                    </div>
                </div> 
            <?php   unset($result['items'][$key]);
                    $result['itemsCount']--;
                 endforeach;?>  
        </div>
    </div>
<?php endif;?>

<div class="container_for_grid margin_bottom_20 clearfix">
    <div class="grid_3of4">
        <div class="articles articles_wide">
            <?php $this->renderPartial('_items', array('items' => $result['items'], 'url' => $this->url)); ?> 
        </div> 
  
        <?php if($result['itemsCount'] > 0 && $result['total_pages'] > 0): ?>
            <?php $this->widget('application.components.widgets.Pagination', array('page' => $result['page'], 'total_pages' => $result['total_pages'], 'remains' => $result['remains'], 'remains_class' => 'js-pagination-more' )); ?> 
        <?php endif;?>


    </div>
    <div class="grid_1of4 grid_last">
        <div class="scroll_switcher">
            <div>
                <div class="scroll_switcher-item">
                    <?php $this->widget('application.components.widgets.Banner'); ?> 
                </div>              
                <?php $this->widget('application.components.widgets.BestArticle'); ?>
                <?php $this->widget('application.components.widgets.SocialsBlock'); ?> 
            </div>
        </div>
    </div>
</div>
  
    
 