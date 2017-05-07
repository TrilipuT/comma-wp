<?php if (count($colsArray) > 0):  ?>
     <?php foreach ($colsArray as $key => $items):?>
        
        <?php if (count($items) > 0): ?>
            <div class="grid_1of4">   
                <?php $this->renderPartial('/site/_article_items', array('items' => $items)); ?>  
            </div>
        <?php endif;?>  

    <?php endforeach;?>
<?php endif;?>  