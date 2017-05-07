<div class="container_for_grid margin_bottom_20 clearfix">    
    <?php 
        $countBlogers = count($blogerItems);
        if($countBlogers > 0):?>
       
        <div class="grid_3of4 articles">        
  
            <div class="clearfix">
               
                <div class="grid_2of4">
                    <div class="item item_red item_blog_big">
                        <div class="item-text">
                            <b></b>
                            <?=$blogerItems[0]->transfer->name?>
                        </div>
                        <?php if(!empty($blogerItems[0]->bloger->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Blogers::PATH_IMAGE.$blogerItems[0]->bloger->image_filename)): ?>
                            <div class="item-img">
                                <img src="<?=Blogers::PATH_IMAGE.$blogerItems[0]->bloger->image_filename;?>" alt="<?=$blogerItems[0]->bloger->transfer->name?>"/>
                            </div>
                        <?php endif;?>

                        <div class="item-author2">
                            <div class="line"></div>
                            <a href="<?=$blogerItems[0]->bloger->getItemUrl();?>">
                                <?=$blogerItems[0]->bloger->transfer->name?>
                            </a>
                        </div>
                        <a href="<?=$blogerItems[0]->getItemUrl();?>" class="item-link"></a>
                    </div>
                </div>

                <?php if($countBlogers > 1): ?>

                    <div class="grid_1of4">
                        <div class="item item_red">
                            <?php if(!empty($blogerItems[1]->bloger->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Blogers::PATH_IMAGE_SMALL.$blogerItems[1]->bloger->image_filename)): ?>
                                <div class="item-img">
                                    <img src="<?=Blogers::PATH_IMAGE_SMALL.$blogerItems[1]->bloger->image_filename;?>" alt="<?=$blogerItems[1]->bloger->transfer->name?>"/>
                                </div>
                            <?php endif;?>

                            <div class="item-author2">
                                <div class="line"></div>
                                <a href="<?=$blogerItems[1]->bloger->getItemUrl();?>">
                                    <?=$blogerItems[1]->bloger->transfer->name?>
                                </a>
                            </div>
                            <div class="item-text">
                                <?=$blogerItems[1]->transfer->name?>
                            </div>
                            <a href="<?=$blogerItems[1]->getItemUrl();?>" class="item-link">
                                <div class="item-border"></div>
                            </a>
                        </div>
                    </div>
 
                <?php endif;?>
            </div>


            
            <?php if($countBlogers > 2):?>

                <?php 
                    $key = 0;
                    for ($i=2; $i < $countBlogers; $i++): 

                    $Article = $blogerItems[$i]; 

                    ?>

                    <?php if($key == 0 || $key%3 == 0):?>
                        <div class="clearfix">
                    <?php endif;?>

                            <div class="grid_1of4">
                                <div class="item item_red">
                                    <?php if(!empty($Article->bloger->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Blogers::PATH_IMAGE_SMALL.$Article->bloger->image_filename)): ?>
                                        <div class="item-img">
                                            <img src="<?=Blogers::PATH_IMAGE_SMALL.$Article->bloger->image_filename;?>" alt="<?=$Article->bloger->transfer->name?>"/>
                                        </div>
                                    <?php endif;?> 
                                    <div class="item-author2">
                                        <div class="line"></div>
                                        <a href="<?=$Article->bloger->getItemUrl();?>">
                                            <?=$Article->bloger->transfer->name?>
                                        </a>
                                    </div>
                                    <div class="item-text">
                                        <?=$Article->transfer->name?>
                                    </div>
                                    <a href="<?=$Article->getItemUrl();?>" class="item-link">
                                        <div class="item-border"></div>
                                    </a>
                                </div>
                            </div>

                    <?php if($key == 2 || ($i+1) == $countBlogers):
                        $key = -1;
                        ?>        
                        </div>
                    <?php endif;?> 

                <?php $key++; endfor; ?>    
            <?php endif; ?> 
             
        </div>
    <?php endif;?>



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
  
    
 