
<?php if(empty($list)): ?>

		<p>Записей нет.</p> 

<?php else: ?> 

	<div class="content noPad">
	    <table class="responsive table table-bordered" >
	        <thead>
	          <tr>
	            <th width="1%"><?=Article::model()->getAttributeLabel('id');?></th>
	            <th><?=ArticleTransfer::model()->getAttributeLabel('name');?> [ru]</th> 
	            <th><?=Article::model()->getAttributeLabel('code_name');?></th>  
	            <th width="4%"><?=Article::model()->getAttributeLabel('datetime');?></th>
	            <th width="1%"><?=Article::model()->getAttributeLabel('active');?></th>
	            <th width="5%">Actions</th>
	            <th width="1%" class="ch"><input id="checkAll" type="checkbox" name="checkbox" value="all" class="styled" /></th>
	            
	          </tr>
	        </thead>
	        <tbody id="sortable" >
					<?php foreach($list as $item):?>
						<tr class=" " data-id="<?=$item->id;?>" data-order="<?=$item->order_num;?>"  >
							<td><?=$item->id;?></td>
						 	<td>
						 		<a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id)?>" title="Edit task" class="">
						 			<?=$item->transfer->name;?>
						 		</a>	
						 	</td> 
						 	<td><?=$item->code_name;?></td>  
						 	<td><?=$item->datetime;?></td>

						 	<td><?=$item->active;?></td>

						 	<td>
							    <div class="controls center">
							        <a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id)?>" title="Edit task" class="tip"><span class="icon12 icomoon-icon-pencil"></span></a>
							        <a onclick="if(confirm('Ви впевнені, що хочете видалити?')){return true;} else { return false;}" href="<?=$this->createUrl('support/delete/'.$model_name.'/'.$item->id)?>" title="Remove task" class="tip"><span class="icon12 icomoon-icon-remove"></span></a>
							    </div>
							</td>

							<td ><input class="chChildren" type="checkbox" name="checkbox" value="<?=$item->id?>" class="styled" /></td>
							
						</tr>
					<?php endforeach; ?>
	        </tbody>
	    </table>
	</div>
<?php endif; ?>	 
