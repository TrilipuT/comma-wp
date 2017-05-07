 <div class="container_for_grid margin_bottom_20 clearfix">
    <div class="grid_3of4">

        <?php if($authorsItems):?>
        <div class="writers"> 
            
            <?php foreach($authorsItems as $Authors):?>
          
                    <?php if(!empty($Authors->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Authors::PATH_IMAGE_MINI.$Authors->image_filename)): ?>
                        <div class="item">
                            <a href="<?=$Authors->getItemUrl()?>">
                                <img src="<?=Authors::PATH_IMAGE_MINI.$Authors->image_filename;?>" alt="<?=$Authors->transfer->getName()?>"/>
                                <span><?=$Authors->transfer->getName()?></span>
                            </a>
                        </div> 
                    <?php endif;?>   

            <?php endforeach;?>
        </div>
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