<?php if($newMaterials):?>
    <div class="bg_white">
        <div class="simple_title item_orange">
            <b></b><?=Yii::t('app','new_article');?><b></b>
        </div> 
        
        <div class="articles container_for_grid clearfix">
            
            <?php foreach($newMaterials as $key=>$Article): 

                $color = '';
                if(!$Article->blog){
                   $color = 'style="background:'.$Article->rubric->color.';';
                }

                $last = '';
                if($key+1 == count($newMaterials)){
                    $last = 'grid_last';
                }
                ?>   
                
                <div class="grid_1of4 <?=$last;?>">
                    <div class="item ">
                        <?php if(!empty($Article->icon_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::PATH_ICON_SMALL.$Article->icon_filename)): ?>
                            <div class="item-img">
                                <img src="<?=Article::PATH_ICON_SMALL.$Article->icon_filename;?>" alt="<?=$Article->transfer->name?>"/>
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

                            <a href="#" class="item-rubric">
                               <?=$Article->rubric->transfer->name;?>
                            </a>

                        </div>
                        <?php if($Article->blog):?>
                            <a href="#" class="item-author">
                                <?=Constants::getItemByKey('name_blogger')?>
                            </a>
                        <?php endif;?>
                        <div class="item-text">
                            <?=$Article->transfer->name?>
                        </div>
                        <a href="<?=$Article->getItemUrl()?>" class="item-link"><div class="item-border"></div></a>
                    </div>
                </div> 
            <?php endforeach;?>  
        </div>
    </div>
<?php endif;?>
<div class="container_for_grid margin_bottom_20 clearfix">
    <div class="grid_3of4">
        <div class="articles articles_wide">
            <?php $this->renderPartial('_items', array('items' => $result['items'], 'url' => $this->url)); ?> 
        </div> 
        
        <?php if($result['total_pages'] > 0): ?>
            <?php if($result['remains'] > 0): ?>
                <!--<div class="more"><a href="#" page="2" type="experts" class="more_items more__in"><i></i><?=Yii::t('app','expert_more')?></a></div>-->
            <?php endif;?> 
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
  
    
 