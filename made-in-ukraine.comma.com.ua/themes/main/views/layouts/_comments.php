<?php if(is_array($commentsItems) && count($commentsItems) > 0):  
        
        $ulClass = 'comments-wrap';
        if($lvl > 0){
            $ulClass = 'comments-wrap_sublevel';
        }


    ?>           
        <?php foreach ($commentsItems as $key => $item):  

                //$rating = 'rating_positive';
                $_rating        = 'positive';
                $_rating_text   = 'positive-text';
                if($item['item']->votes_pro < 0){
                    $_rating = 'negative';
                }

                if($item['item']->votes_pro <= -15){
                    $_rating_text = 'negative-text';
                }
  
                if($lvl > 0){
                    $rating = '';    
                }   
                
                if($lvl <= 1):?>
                    <div id="comment_item_<?=$item['item']->id?>" class="item  <?=$rating;?>">
                <?php endif;?>

                    <div class="item-inner <?=$_rating;?> <?=$_rating_text;?>">
                        <div class="item-avatar">
                            <img style="max-width:50px;" src="<?=$item['item']->getUserPhoto();?>" 1 alt="<?=$item['item']->getUserName();?>" /> 
                        </div>
                        <div class="item-top">
                            <div class="item-rating">
                                <div onclick="changeRate('top', this); return false;" data-id="<?=$item['item']->id;?>" class="plus"></div>
                                <span><?=$item['item']->votes_pro;?></span>
                                <div onclick="changeRate('down', this); return false;" data-id="<?=$item['item']->id;?>" class="minus"></div>
                            </div>

                            <a target="_blank" href="<?=$item['item']->getUserUrl();?>">
                                <div class="item-name"> 
                                        <?=$item['item']->getUserName();?>  
                                </div>
                            </a>
                            <div class="item-date">
                                <?=$item['item']->getDate('name');?>
                            </div>
                        </div>
                        <div class="item-text">
                            <?=str_replace("\n", "<br/>", $item['item']->content);?>
                        </div>
                        <div class="item-answer"> <!-- onclick="toCommentBlock($(this)); return false;" -->
                            <a class="comment-answer" data-id="<?=$item['item']->id;?>" href="#"><?=Constants::getItemByKey('comments_answer')?></a>
                        </div>                         
                    </div>
                    
                    <?php if(($item['items']) != NULL):?> 
                        <?php $this->renderPartial('/layouts/_comments', array('commentsItems' => $item['items'], 'lvl' => $lvl+1 , 'Users' => $Users )); ?>
                    <?php endif;?> 

                <?php if($lvl <= 1):?>
                    </div>  
                <?php endif;?>
                
        <?php endforeach;?>
 
<?php endif;?>