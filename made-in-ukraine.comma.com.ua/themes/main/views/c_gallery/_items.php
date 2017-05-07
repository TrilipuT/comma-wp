<?php if (count($items) > 0):    

    if($big_img){
        $img_path = Gallery::PATH_IMAGE2;
    } else {
        $img_path = Gallery::PATH_IMAGE;
    }

    foreach ($items as $Gallery): ?>

        <a href="<?=$Gallery->getItemUrl();?>" class="item">
            <div class="item-img">
                <?php if(!empty($Gallery->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].$img_path.$Gallery->image_filename)): ?>
                    <img src="<?=$img_path.$Gallery->image_filename;?>" alt="<?=$Gallery->transfer->name?>"/>
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
                            <?=$Gallery->comments_num;?>
                        </div>
                        <div class="item-views">
                            <?=$Gallery->views_num;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item-title">
                <?=$Gallery->transfer->name?>
            </div>
        </a> 

    <?php endforeach;?>
<?php endif;?>  