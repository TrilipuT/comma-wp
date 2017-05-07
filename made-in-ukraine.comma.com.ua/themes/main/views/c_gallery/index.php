<div class="container_for_grid margin_bottom_20 clearfix">
    <div class="grid_3of4">

        <div class="photos"> 

            <?php $this->renderPartial('_items', array('items' => $result['items'], 'url' => $this->url, 'big_img' => true)); ?> 
              
        </div>
        <?php if($result['total_pages'] > 0): ?>
            <?php $this->widget('application.components.widgets.Pagination', array('page' => $result['page'], 'total_pages' => $result['total_pages'], 'remains' => $result['remains'], 'remains_class' => 'js-pagination-more' )); ?> 
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