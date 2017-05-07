<div class="article single_photo clearfix">
    <div class="single_photo-top clearfix">
        <?php $this->widget('application.components.widgets.Banner'); ?> 
        <div class="big_title">
            <?=$Gallery->transfer->name?>
        </div>
        <div class="single_photo-author">
            
            <?php if($authorsItems):?>
                <div class="article-container">

                    <div class="article-rubric">
                        <b style="background: #000;"></b><a href="<?=Gallery::getSectionUrl();?>">Фото</a><b style="background: #000;"></b>
                    </div>

                    <?php foreach($authorsItems as $Authors):?>
                        <div class="gallery-author">
                            
                            <?php if(!empty($Authors->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Authors::PATH_IMAGE_MINI.$Authors->image_filename)): ?>
                                <div class="item-img">  
                                    <a href="<?=$Authors->getPhotografUrl()?>">                               
                                        <img src="<?=Authors::PATH_IMAGE_MINI.$Authors->image_filename;?>" alt="<?=$Authors->transfer->getName()?>"/>  
                                    </a>                              
                                </div>  
                            <?php endif;?> 
         
                            <div class="item-name"> 
                                <div><a href="<?=$Authors->getPhotografUrl()?>"><?=$Authors->transfer->getName()?></a></div> 
                            </div>
                            
                        </div>
                    <?php endforeach;?>
          
                </div> 
            <?php endif;?>
 

            <div class="item-title">
                <?=$Gallery->transfer->description?>    
            </div>
            <div class="info_line">
                <div class="info_line-date">
                    <?=$Gallery->getDate('name')?>
                </div>
                <div class="item-comments">
                    <?=$Gallery->comments_num?>
                </div>
                <div class="item-views">
                    <?=$Gallery->views_num?>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($Gallery->photos)):?>
        
        <div class="article-photos">
            <?php foreach($Gallery->photos as $Photos):?>

                <?php if(!empty($Photos->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Photos::PATH_IMAGE.$Photos->image_filename)): ?>
                    <img src="<?=Photos::PATH_IMAGE.$Photos->image_filename;?>" alt="<?=$Gallery->transfer->name?>"/> 
                <?php endif;?> 
            <?php endforeach;?>
        </div> 
    <?php endif;?>        
    <div class="article-content">
        <?=$Gallery->transfer->content?>    
    </div>

    <div class="likes_n_tags">
        <?php $this->widget('application.components.widgets.Socials'); ?>
        <?php if($tags):?>
            <div class="tags">
                <div class="tags-title"><?=Yii::t('app','tags');?>:</div> 
                <div class="tags-block">
                    <?php foreach($tags as $Tags):?>
                        <a href="<?=$Tags->getItemUrl()?>">
                            <?=$Tags->transfer->name?>
                        </a>
                    <?php endforeach;?>
                </div>
            </div>
        <?php endif;?>
    </div>
    <?php $this->widget('application.components.widgets.Comments', array('returnUrl' => Yii::app()->request->url, 'type' => 'gallery', 'data_id' => $Gallery->id, 'countComments' => $Gallery->comments_num )); ?>
</div>




 
   