<?php
 if($item['videos']['itemsCount'] > 0): 
        $rows = ceil($item['videos']['itemsCount']/4); 
        for($k=0; $k < $rows;$k++):?>
            <div class="clearfix">
                <?php $j = 0; 
                        for($i; $i < $item['videos']['itemsCount']; $i++):

                            $last = "";
                            if($j == 3 || ($item['videos']['itemsCount']) == $i+1 ){
                                $last = "grid_last";
                            }
                        ?>
                        
                        <div <?=$i?> <?=($item['videos']['itemsCount']+1)?> class="grid_1of4 <?=$last?>" >
                            <?php $this->renderPartial('_item', array('Videos' => $item['videos']['items'][$i])); ?>
                        </div>

                <?php $j++;  endfor;?>
            </div> 
        <?php endfor;?>
<?php endif;?>