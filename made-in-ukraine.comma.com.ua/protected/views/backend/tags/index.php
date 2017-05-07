<?php 

$search_name = Yii::app()->request->getParam('search_name');
?>
<form action="/support/<?=$model_name?>" >  
	<span>Найти </span>
	<div class="row-fluid">
        <?php echo CHtml::textField('search_name', $search_name, array('size'=>60, 'class' => "span2 text", 'placeholder' => 'по заголовку (рус или укр)')); ?> 
        
        <button style="margin-top: -10px;" class="btn btn-danger" href="#">Найти</button> 
    </div>  

</form>


<?php if(empty($list)): ?>
		<p>Записей нет.</p>
<?php else: ?> 

	<div class="content noPad">
	    <table class="responsive table table-bordered" >
	        <thead>
	          <tr>
	            <th width="1%"><?=Tags::model()->getAttributeLabel('id');?></th>
	            <th><?=TagsTransfer::model()->getAttributeLabel('name');?> [ru]</th>
	            <th><?=TagsTransfer::model()->getAttributeLabel('name');?> [ua]</th>  
	            <th width="1%"><?=Tags::model()->getAttributeLabel('active');?></th>
	            <th width="5%">Actions</th>
	            <th width="1%" class="ch"><input id="checkAll" type="checkbox" name="checkbox" value="all" class="styled" /></th>
	            
	          </tr>
	        </thead>
	        <tbody id="sortable" >
					<?php foreach($list as $item):

							$Tags = Tags::model()->withTransfer(2)->findByPk($item->id);  
						?>
						<tr class="ui-state-default" data-id="<?=$item->id;?>" data-order="<?=$item->order_num;?>"  >
							<td><?=$item->id;?></td>
						 	

						 	<td>
						 		<a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id.$dop_link)?>">
						 			<?=$item->transfer->name;?>
					 			</a>
						 	</td> 

						 	<td>
						 		<a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id.$dop_link)?>">
						 			<?=$Tags->transfer->name;?>
					 			</a>
						 	</td>  


						 	<td><?=$item->active;?></td>

						 	<td>
							    <div class="controls center">
							        <a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id.$dop_link)?>" title="Edit task" class="tip"><span class="icon12 icomoon-icon-pencil"></span></a>
							        <a href="<?=$this->createUrl('support/delete/'.$model_name.'/'.$item->id.$dop_link)?>" title="Remove task" class="tip"><span class="icon12 icomoon-icon-remove"></span></a>
							    </div>
							</td>

							<td ><input class="chChildren" type="checkbox" name="checkbox" value="<?=$item->id;?>" class="styled" /></td>
							
						</tr>
					<?php endforeach; ?>
	        </tbody>
	    </table>
	</div>
<?php endif; ?>	  

 