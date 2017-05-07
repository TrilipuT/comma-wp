<?php  foreach ($items as $key=>$Article):
    
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

        <div class="grid_1of4 <?=(($key == 3) ? 'grid_last' : '' )?>"> 

            <div class="item <?=$color;?>">

                <?php if($issetImg): ?>
                    <div class="item-img" style="<?=$style2;?>">
                        <img with="<?=$with;?>" height="<?=$height;?>" src="<?=$img;?>" alt="<?=$Article->transfer->name?>" />
                    </div> 
                <?php endif;?> 
                
                <?php if($Article->rubric):?>

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
                        
                        <a href="<?=$Article->rubric->getItemUrl()?>" class="item-rubric">
                            <?=$Article->rubric->transfer->name;?>
                        </a>  
                    </div>

                <?php elseif($Article->blog):?>

                    <div class="item-author2">
                        <div class="line"></div>
                        <a href="<?=$Article->bloger->getItemUrl();?>">
                            <?=$Article->bloger->transfer->name;?>
                        </a>
                    </div>
                <?php endif;?>

                <div class="item-text">
                    <?=$Article->transfer->name?>
                </div> 

                <a href="<?=$Article->getItemUrl()?>" class="item-link">
                    <div style="border: 5px solid <?=$color?>;" class="item-border"></div>
                </a>
            </div>
        </div> 

<?php endforeach;?> 

 