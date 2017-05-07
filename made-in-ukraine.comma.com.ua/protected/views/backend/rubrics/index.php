
<?php if(empty($list)): ?>

		<p>Записей нет.</p> 

<?php else: ?> 

	<div class="content noPad">
	    <table class="responsive table table-bordered" >
	        <thead>
	          <tr>
	            <th width="1%"><?=Rubrics::model()->getAttributeLabel('id');?></th>
	            <th><?=RubricsTransfer::model()->getAttributeLabel('name');?> [ru]</th> 
	            <th><?=Rubrics::model()->getAttributeLabel('code_name');?></th>  
	            <th width="1%"><?=Rubrics::model()->getAttributeLabel('active');?></th>
	            <th width="5%">Actions</th>
	            <th width="1%" class="ch"><input id="checkAll" type="checkbox" name="checkbox" value="all" class="styled" /></th>
	            
	          </tr>
	        </thead>
	        <tbody id="sortable" >
					<?php foreach($list as $item):?>
						<tr class="ui-state-default" data-id="<?=$item->id;?>" data-order="<?=$item->order_num;?>"  >
							<td><?=$item->id;?></td>
						 	<td>
						 		<a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id)?>" title="Edit task" class="">
						 			<?=$item->transfer->name;?>
						 		</a>	
						 	</td> 
						 	<td><?=$item->code_name;?></td>  
						 	<td><?=$item->active;?></td>

						 	<td>
							    <div class="controls center">
							        <a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id)?>" title="Edit task" class="tip"><span class="icon12 icomoon-icon-pencil"></span></a>
							        <a onclick="if(confirm('Ви впевнені, що хочете видалити?')){return true;} else { return false;}" href="<?=$this->createUrl('support/delete/'.$model_name.'/'.$item->id)?>" title="Remove task" class="tip"><span class="icon12 icomoon-icon-remove"></span></a>
							    </div>
							</td>

							<td ><input class="chChildren" type="checkbox" name="checkbox" value="<?=$item->id?>" class="styled" /></td>
							
						</tr>

						<?php 

							$subRubricsItems = Rubrics::model()->published()->orderByOrderNum()->findAll('parent_id = :parent_id', 
    																						 array(':parent_id' => $item->id));

							if($subRubricsItems):

								$lvl = 1;
								?>

							<?php foreach($subRubricsItems as $_Rubrics):?>
								<tr class="ui-state-default" data-id="<?=$_Rubrics->id;?>" data-order="<?=$_Rubrics->order_num;?>"  >
									<td><?=$_Rubrics->id;?></td>
								 	<td style="padding-left: <?=($lvl*50)?>px;">
								 		<a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$_Rubrics->id)?>" title="Edit task" class="">
								 			<i><?=$_Rubrics->transfer->name;?></i>
								 		</a>	
								 	</td> 
								 	<td><?=$_Rubrics->code_name;?></td>  
								 	<td><?=$_Rubrics->active;?></td>

								 	<td>
									    <div class="controls center">
									        <a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$_Rubrics->id)?>" title="Edit task" class="tip"><span class="icon12 icomoon-icon-pencil"></span></a>
									        <a onclick="if(confirm('Ви впевнені, що хочете видалити?')){return true;} else { return false;}" href="<?=$this->createUrl('support/delete/'.$model_name.'/'.$item->id)?>" title="Remove task" class="tip"><span class="icon12 icomoon-icon-remove"></span></a>
									    </div>
									</td>

									<td ><input class="chChildren" type="checkbox" name="checkbox" value="<?=$_Rubrics->id?>" class="styled" /></td> 
								</tr>  

							<?php endforeach; ?> 

						<?php endif;?>

					<?php endforeach; ?>
	        </tbody>
	    </table>
	</div>
<?php endif; ?>	 
