<?php if (count($items) > 0):   
    foreach ($items as $Article):
  
        $flip = '';
        if($i%2 == 1){
            $flip = 'item_flip';
        }


        $issetImg = false;
        $width     = '';
        $height   = '';
        $style    = '';

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
 

        $color = "#961f20";
        if(!$Article->blog){
            $color = $Article->rubric->color;
        }  
 
        ?>

        <div data-flip="<?=$i;?>" class="item <?=$flip;?>" style="<?=$style;?>">
            <?php if($issetImg): ?>
                <div class="item-img" style="<?//$style2?>">
                    <img width="<?=$width;?>" height="<?=$height;?>" src="<?=$img;?>" alt=""/>
                </div> 
            <?php endif;?> 
            
            <div class="item-title">
                <div class="line" style="background: <?=$color?>;"></div>
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
            <div class="item-text">
                <?=$Article->transfer->name?>
            </div>
            <div class="item-text2">
                <?=$Article->transfer->description?>
            </div>
            <a href="<?=$Article->getItemUrl()?>" class="item-link">
                <div style="border: 5px solid <?=$color?>;" class="item-border"></div>
            </a>
        </div>   

    <?php $i++; endforeach;?>
<?php endif;?>  