<div class="box"> 
 	<div class="title"> 
	    <h4>
	        <span class="icon16 icomoon-icon-equalizer-2"></span>  
	    </h4> 
	</div>
	<?php if(empty($list)): ?>

			<p>Записей нет.</p> 

	<?php else: ?> 

		<div class="content noPad">
		    <table class="responsive table table-bordered" >
		        <thead>
		          <tr>
		            <th><?=Language::model()->getAttributeLabel('id');?></th>
		            <th><?=Language::model()->getAttributeLabel('name');?> [ru]</th> 
		            <th><?=Language::model()->getAttributeLabel('code_name');?></th>  
		            <th><?=Language::model()->getAttributeLabel('active');?></th>
		            <th>Actions</th>
		            <th class="ch"><input id="checkAll" type="checkbox" name="checkbox" value="all" class="styled" /></th>
		            
		          </tr>
		        </thead>
		        <tbody id="sortable" >
 					<?php foreach($list as $item):?>
							<tr class="ui-state-default">
								<td><?=$item->id;?></td>
							 	<td><?=$item->name;?></td> 
							 	<td><?=$item->code_name;?></td>  
							 	<td><?=$item->active;?></td> 
								<td>
								    <div class="controls center">
								        <a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id)?>" title="Edit task" class="tip"><span class="icon12 icomoon-icon-pencil"></span></a> 
								    </div>
								</td>
								<td ><input class="chChildren" type="checkbox" name="checkbox" value="2" class="styled" /></td>
							</tr>
 					<?php endforeach; ?>
		        </tbody>
		    </table>
		</div>
	<?php endif; ?>	
</div><!-- End .box --> 