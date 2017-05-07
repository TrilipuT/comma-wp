<?php if($Gallery && count($Gallery->photos) > 0):?>
    <div class="images">
        <div class="images-content">
            <div class="floater"> 
                <?php foreach ($Gallery->photos as $Photos): ?>
                     
                    <?php if(!empty($Photos->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Photos::PATH_IMAGE_MEDIUM.$Photos->image_filename)): ?>
                        <div class="item">
                            <a href="#" onclick="return false;">
                                <img src="<?=Photos::PATH_IMAGE_MEDIUM.$Photos->image_filename;?>" alt="<?=$Gallery->transfer->name?>#<?=$Photos->id?>"/>
                            </a>
                        </div>
                    <?php endif;?>  

                <?php endforeach;?> 
            </div>
        </div>
        <div class="images-num"></div>
        <div class="prev"></div>
        <div class="next"></div>
    </div>
<?php endif;?>


                        
                                    
                               
                                