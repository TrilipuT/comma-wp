<div class="container_for_grid margin_bottom_20 clearfix">
    <div class="grid_3of4">
        <div class="news news_center">
            <?php $this->renderPartial('_items', array('newsItems' => $newsItems)); ?> 
        </div>

        <?php if($newsItems['total_pages'] > 0): ?>
            <!--<div class="more"><a href="#" page="2" type="experts" class="more_items more__in"><i></i><?=Yii::t('app','expert_more')?></a></div>--> 
            <?php $this->widget('application.components.widgets.Pagination', array('page' => $newsItems['page'], 'total_pages' => $newsItems['total_pages'], 'remains' => $newsItems['remains'], 'remains_class' => 'js-pagination-more' )); ?> 
        <?php endif;?>  

    </div>
    <div class="grid_1of4 grid_last">
        <div class="scroll_switcher">
            <div>
                <div class="scroll_switcher-item">
                    <?php $this->widget('application.components.widgets.Banner'); ?>           
                </div> 
                <?php $this->widget('application.components.widgets.SocialsBlock'); ?>  
            </div>
        </div>
    </div>
</div> 

<div id="news_hide_block" style="display:none;"></div>
  

 
    
 