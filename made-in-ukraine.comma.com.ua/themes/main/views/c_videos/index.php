<?php if(count($result) > 0):?>
    <?php 
        $key = 0;
        foreach ($result as $item): 

            if($item['videos']['itemsCount'] == 0){
                continue;
            }

            $class = '';
            if($key == 1){
                $class = 'videos_bg';
            }

        ?>

        <div class="videos <?=$class;?>">
            <div class="big_title"><?=$item['cat']->transfer->name?></div>

            <div class="articles container_for_grid">
                 
                <div class="clearfix">
                    <?php 

                        $Videos = $item['videos']['items'][0];
                    ?>
                    <div class="grid_2of4">
                        <a href="<?=$Videos->getItemUrl()?>" class="item item_big">
                            
                            <?php if(!empty($Videos->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Videos::PATH_IMAGE.$Videos->image_filename)):?>
                                <div class="item-img">
                                    <img src="<?=Videos::PATH_IMAGE.$Videos->image_filename;?>" alt="<?=$Videos->transfer->name?>" />
                                </div>
                            <?php endif;?>

                            <div class="item-video">
                                <b></b>
                                <div class="item-song">
                                    <?=$Videos->transfer->name?>
                                </div>
                                <?php if($Videos->transfer->sub_name):?>
                                    <div class="item-artist">
                                        <?=$Videos->transfer->sub_name?>
                                    </div>
                                <?php endif;?>
                            </div>
                        </a>
                    </div> 

                    <?php if($key == 0):

                        $i = 3;
                        ?>
                        <div class="grid_1of4">
                            <?php $this->renderPartial('_item', array('Videos' => $item['videos']['items'][1])); ?> 
                            <?php $this->renderPartial('_item', array('Videos' => $item['videos']['items'][2])); ?> 
                        </div>   
                        <div class="grid_1of4 grid_last" style="float:right;">
                            <?php $this->widget('application.components.widgets.Banner'); ?> 
                        </div>

                    <?php else: ?>
                        <div class="grid_2of4 grid_last">
                            <?php 
                                $j = 0;
                                for($i = 1; $i < 5; $i++): 

                                    if($item['videos']['items'][$i] == NUll) break;
                                    ?>

                                    <?php if($j%2 == 0):?>
                                        <div class="clearfix">
                                    <?php endif;?>


                                        <div <?=$i?>  class="grid_1of4 <?=(($j%2 == 1) ? 'grid_last' : '')?>" >
                                            <?php $this->renderPartial('_item', array('Videos' => $item['videos']['items'][$i])); ?>
                                        </div>


                                    <?php if($j%2 == 1 || ($i+1) == $item['videos']['itemsCount']):?>
                                        </div>
                                    <?php endif;?>

                            <?php   $j++; 
                                    endfor;?>
                        </div>
                    <?php endif;?>
                </div>  

                <?php if($item['videos']['itemsCount'] >= $i):?>
                    <div class="clearfix">
                        <?php $j = 0; 
                                for($i; $i < $item['videos']['itemsCount']; $i++): ?>
                                
                                <div <?=$i?> class="grid_1of4 <?=(($j == 3) ? 'grid_last' : '')?>" >
                                    <?php $this->renderPartial('_item', array('Videos' => $item['videos']['items'][$i])); ?>
                                </div>

                        <?php $j++;  endfor;?>
                    </div> 
                <?php endif;?>
            </div>
            

            <?php if($item['videos']['remains'] > 0):?>
                <div class="pagination">
                    <div class="pagination-more js-video-pagination-more">
                        <a offset="<?=$item['start'];?>" page="<?=$item['videos']['page'];?>" href="<?=$item['cat']->getItemUrl()?>"><b></b>Больше клипов</a>
                    </div>
                </div>
            <?php endif;?> 
        </div>
    <?php $key++; 
        endforeach;?>
<?php endif;?> 

<?php if($total_pages > 0): ?>
    <?php $this->widget('application.components.widgets.Pagination', array('page' => $page, 'total_pages' => $total_pages )); ?> 
<?php endif;?>
