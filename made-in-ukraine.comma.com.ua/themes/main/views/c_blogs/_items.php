<?php if (count($items) > 0):   
    foreach ($items as $Article):
  
        $flip = '';
        if($i%2 == 1){
            $flip = 'item_flip';
        }


        $issetImg = false;
        $with     = '';
        $height   = '';
        $style    = '';

        if(!empty($Article->icon_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Article::PATH_ICON_SMALL.$Article->icon_filename)){
            $issetImg = true;

            $size   = getimagesize($_SERVER['DOCUMENT_ROOT'].Article::PATH_ICON_SMALL.$Article->icon_filename);
            $with   = $size[0];
            $height = $size[1];
            $style    = 'min-height:'.$height.'px;';
        }

        $color = "#961f20";
        if(!$Article->blog){
            $color = $Article->rubric->color;
        }  

        ?>

        <div data-flip="<?=$i;?>" class="item <?=$flip;?>" style="<?=$style;?>">
            <?php if($issetImg): ?>
                <div class="item-img">
                    <img with="<?=$with;?>" height="<?=$height;?>" src="<?=Article::PATH_ICON_SMALL.$Article->icon_filename;?>" alt=""/>
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
            <div class="item-text">
                <?=$Article->transfer->name?>
            </div>
            <div class="item-text2">
                <?=$Article->transfer->description?>
            </div>
            <a href="<?=$Article->getItemUrl()?>" class="item-link">
                <div style="border: 5px solid <?=$color;?>;" class="item-border"></div>
            </a>
        </div>   

    <?php $i++; endforeach;?>
<?php endif;?>  