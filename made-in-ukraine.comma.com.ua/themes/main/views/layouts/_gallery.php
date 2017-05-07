<?php if($Gallery && count($Gallery->photos) > 0):
    
    $blockHeight = "";
    foreach ($Gallery->photos as $Photos){
        if(!empty($Photos->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Photos::PATH_IMAGE.$Photos->image_filename)){  
            list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'].Photos::PATH_IMAGE.$Photos->image_filename);

            if($height > $blockHeight){
                $blockHeight = $height;
            }
        }
    } 


    //images_size2
    //style="height: $blockHeight px;"  images_wide
    ?>
    <div class="images " >
        <div class="images-content " style="height:<?=$blockHeight?>px;">
            <div class="floater"> 
                <?php foreach ($Gallery->photos as $Photos): ?>
                     
                    <?php if(!empty($Photos->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Photos::PATH_IMAGE.$Photos->image_filename)): 
                            list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'].Photos::PATH_IMAGE.$Photos->image_filename);
                        ?>
                        <div class="item">
                            <a href="#" onclick="return false;">
                                <img width="<?=$width?>"  height="<?=$height?>" src="<?=Photos::PATH_IMAGE.$Photos->image_filename;?>" alt="<?=$Gallery->transfer->name?>#<?=$Photos->id?>"/>
                            </a>
                        </div>
                    <?php endif;?>  

                <?php endforeach;?> 
            </div>
        </div>
        <div class="clear"></div>
        <div class="images-num"></div>
        <div class="prev"></div>
        <div class="next"></div>
    </div>
<?php endif;?>

 