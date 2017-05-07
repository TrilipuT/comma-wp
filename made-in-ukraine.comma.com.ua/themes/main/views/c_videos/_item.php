<?php if($Videos):?>
    <a href="<?=$Videos->getItemUrl()?>" class="item">   
        <?php if(!empty($Videos->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Videos::PATH_IMAGE_SMALL.$Videos->image_filename)):?>
            <div class="item-img">
                <img src="<?=Videos::PATH_IMAGE_SMALL.$Videos->image_filename;?>" alt="<?=$Videos->transfer->name?>" />
            </div>
        <?php endif;?>

        <div class="item-video">
            <b></b>
            <div class="item-song">
                <?=$Videos->transfer->name?>
            </div>
            <?php if($Videos->transfer->sub_name != ""):?>
                <div class="item-artist">
                    <?=$Videos->transfer->sub_name?>
                </div>
            <?php endif;?>
        </div>
    </a>
<?php endif;?>