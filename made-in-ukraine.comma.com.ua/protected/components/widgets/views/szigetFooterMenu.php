<?php if($sectionItems):?>
	<div class="rules">
        <?php foreach ($sectionItems as $key => $Section):?>
            <a href="<?=$Section->getItemUrl()?>">
                <?=$Section->transfer->name?>
            </a>
        <?php endforeach;?>
    </div>
<?php endif;?>