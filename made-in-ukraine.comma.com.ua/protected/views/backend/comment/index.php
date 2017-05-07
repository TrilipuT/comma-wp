<?php    

if(empty($list)): ?>

		<p>Записей нет.</p> 

<?php else: 
	
	$Users = new Users();
	?> 
    <div>
        <a href="/support/comment/">
            Сбросить фильтр
        </a>
    </div>
	<div class="content noPad">
	    <table class="responsive table table-bordered" >
	        <thead>
	          <tr>
	            <th width="1%"><?=Comment::model()->getAttributeLabel('id');?></th>
	            <th><?=Comment::model()->getAttributeLabel('datetime');?></th>  
	            <th><?=Comment::model()->getAttributeLabel('user');?></th> 
	            <th>Статья</th> 

	            <th><?=Comment::model()->getAttributeLabel('content');?></th> 
	            <th width="1%"><?=Comment::model()->getAttributeLabel('active');?></th>
	            <th width="5%">Actions</th>
	            <th width="1%" class="ch"><input id="checkAll" type="checkbox" name="checkbox" value="all" class="styled" /></th>
	            
	          </tr>
	        </thead>
	        <tbody id="sortable" >
					<?php foreach($list as $item):

							$item->update = true;
							$item->is_new = 0;
							$item->update(array('is_new'));

						?>
						<tr class="ui-state-default" data-id="<?=$item->id;?>" data-order="<?=$item->order_num;?>"  >
							<td><?=$item->id;?></td>
						 	<td><?=$item->datetime;?></td>  
						 	<td>
                                <a href="/support/comment/?user_id=<?=$item->user_id?>">
                                    <?=$item->getUserName();?>
                                </a>
						 	</td> 

						 	<td>
						 		<?php if($item->news_id > 0):
						 			$News = News::model()->findByPk($item->news_id);
						 			if($News):?>
						 				<a target="_blank" href="<?=$News->getItemUrl();?>"><?=$News->transfer->name;?></a>
						 			<?php endif;?>
							 	<?php elseif($item->article_id > 0):
							 		$Article = Article::model()->findByPk($item->article_id);
							 		if($Article):?>
						 				<a target="_blank" href="<?=$Article->getItemUrl();?>"><?=$Article->transfer->name?></a>
						 			<?php endif;?> 
						 		<?php elseif($item->video_id > 0):
							 		$Videos = Videos::model()->findByPk($item->video_id);
							 		if($Videos):?>
						 				<a target="_blank" href="<?=$Videos->getItemUrl();?>"><?=$Videos->transfer->name?></a>
						 			<?php endif;?> 
						 		<?php elseif($item->gallery_id > 0):
							 		$Gallery = Gallery::model()->findByPk($item->gallery_id);
							 		if($Gallery):?>
						 				<a target="_blank" href="<?=$Gallery->getItemUrl();?>"><?=$Gallery->transfer->name?></a>
						 			<?php endif;?> 
							 	<?php endif;?>
						 	</td> 
						 	<td><a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id.$dop_link)?>"><?=$item->content;?></a></td> 
						 	<td><?=$item->active;?></td>
						 	<td>
							    <div class="controls center">
							        <a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id.$dop_link)?>" title="Edit task" class="tip"><span class="icon12 icomoon-icon-pencil"></span></a>
							        <a href="<?=$this->createUrl('support/delete/'.$model_name.'/'.$item->id.$dop_link)?>" title="Remove task" class="tip"><span class="icon12 icomoon-icon-remove"></span></a>
							    </div>
							</td>
							<td ><input class="chChildren" type="checkbox" name="checkbox" value="<?=$item->id?>" class="styled" /></td>							
						</tr>
					<?php endforeach; ?>
	        </tbody>
	    </table>
	</div>
<?php endif; ?>	 