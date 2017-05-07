<div class="articles container_for_grid clearfix">
     
    <div class="grid_3of4">


        <div class=" ">

            <?php $this->renderPartial('_items', array('colsArray' => $colsArray )); ?>
        </div>

        <div class="clear"></div>
        <?php if($articleItems['total_pages'] > 0): ?>
            <!-- <?=$articleItems['remains']?> -->

            <?php if($articleItems['remains'] > 0): ?>
                <!--<div class="more"><a href="#" page="2" type="experts" class="more_items more__in"><i></i><?=Yii::t('app','expert_more')?></a></div>-->
            <?php endif;?>
            <?php //$articleItems['remains']
            $this->widget('application.components.widgets.Pagination', array('page' => $articleItems['page'], 'total_pages' => $articleItems['total_pages'], 'remains' => 0, 'remains_class' => 'js-pagination-more' )); ?>
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