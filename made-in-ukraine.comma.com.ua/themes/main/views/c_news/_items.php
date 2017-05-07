<?php if ($newsItems['itemsCount'] > 0):   
   //echo '<pre>'; var_dump($newsItems['items']); echo '</pre>'; 
    foreach ($newsItems['items'] as $data=>$item): ?>
        <div class="news-block" data="<?=strtotime($data)?>">
            <div class="news-title">
                <?=News::getMainDate($data)?> 
                <b></b>
            </div>
            
            <?php if(count($item) > 0): ?>
                <?php foreach ($item as $i => $News):?>
                    <a href="<?=$News->getItemUrl()?>" class="item">
                        <?php if(!empty($News->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].News::PATH_IMAGE.$News->image_filename)):?>
                            <div class="item-img">
                                <img src="<?=News::PATH_IMAGE.$News->image_filename?>" alt="<?=$News->transfer->name?>"/>
                            </div>
                        <?php endif;?>
                        <div class="item-title">
                            <?=$News->getTime()?>
                        </div>
                        <div class="item-text">
                            <?=$News->transfer->name?>
                        </div>
                    </a>
                <?php endforeach;?>
            <?php endif;?>
        </div>
        
    <?php $i++; endforeach;?>
<?php endif;?>  