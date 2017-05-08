<?php
        $img      = false;
        $width     = '';
        $height   = '';
        $style    = ''; 
        
        if(!$Article->blog){

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

            //------------------
            $color = $Article->rubric->color;
        } else {

            $img = Blogers::PATH_IMAGE_SMALL.$Article->bloger->image_filename;

            if(!empty($Article->bloger->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].$img)){
                $issetImg   = true; 
            } else {
                $img = '';
            }

            $color = "#961f20";
        } 

        //----------------------------------------------------------------------------
        if($img != ''){
            $size       = getimagesize($_SERVER['DOCUMENT_ROOT'].$img);
            $width      = $size[0];
            $height     = $size[1];
            $style      = 'min-height:'.$height.'px;';
            $style2     = 'min-width:'.$width.'px;';
        }
        ?> 
        <div class="item">
            <?php if($issetImg): ?>
                <div class="item-img" style="<?=$style2?>">
                    <img width="<?=$width;?>" height="<?=$height;?>" src="<?=$img;?>" alt="<?=$Article->transfer->name?>" />
                </div>
            <?php endif;?>

            <div class="item-title">
                <div style="background: <?=$color?>;" class="line"></div>
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
                <?php elseif($Article->blog):?>
                    <a href="<?=Blogers::getSectionUrl()?>" class="item-rubric">  
                        Блоги
                    </a>
                <?php endif;?>
            </div>

            <?php if($Article->blog):?>
                <a href="<?=$Article->bloger->getItemUrl()?>" class="item-author">
                    <?=$Article->bloger->transfer->name?>
                </a>
            <?php endif;?>

            <div class="item-text">
                   <?php if ($Article->main_title): ?>
                        <?= $Article->main_title ?>
                        <?php else: ?>
                        <?= $Article->transfer->name?>
                        <?php endif; ?>

            </div>
            <a href="<?=$Article->getItemUrl()?>" class="item-link">
                <div style="border: 5px solid <?=$color?>;" class="item-border"></div>
            </a> 
        </div>  
