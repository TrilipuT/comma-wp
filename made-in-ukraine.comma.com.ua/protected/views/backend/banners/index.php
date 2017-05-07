 

<?php if(empty($list)): ?>

		<p>Записей нет.</p> 

<?php else: ?> 

	<div class="content noPad">
	    <table class="responsive table table-bordered" >
	        <thead>
				<tr>
					<th width="1%"><?=Banners::model()->getAttributeLabel('id');?></th>
					<th><?=Banners::model()->getAttributeLabel('name');?></th>
					<th width="15%">Позиция</th>
					<th width="10%"><?=Banners::model()->getAttributeLabel('date_start');?></th>
					<th width="10%"><?=Banners::model()->getAttributeLabel('date_end');?></th>

					<th width="2%"><?=Banners::model()->getAttributeLabel('views_num');?></th>

					<th width="1%"><?=Banners::model()->getAttributeLabel('active');?></th>
					<th width="5%">Actions</th>
					<th width="1%" class="ch"><input id="checkAll" type="checkbox" name="checkbox" value="all" class="styled" /></th>
					<th width="1%">тестовый просмотр</th>
				</tr>
	        </thead>
	        <tbody id="" >
					<?php
					$type_list = Banners::model()->getTypeList();
					$banner_test_show = Yii::app()->request->cookies['banner_test_show']->value;

					foreach($list as $item):?>
						<tr class="ui-state-default" data-id="<?=$item->id;?>" data-order="<?=$item->order_num;?>"  >
							<td><?=$item->id;?></td>
						 	<td><a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id.$dop_link)?>" ><?=$item->name;?></a></td>

							<td><?=$type_list[$item->type];?></td>

							<td><?=$item->date_start;?></td>
							<td><?=$item->date_end;?></td>
						 	<td><?=$item->views_num;?></td>  

						 	<? /*
						 	<td>
						 		<?php if($item->created_by > 0):
				 					$Editors = Editors::model()->findByPk($item->created_by);
				 					?>
				 					<?=$Editors->fullname?>
				 				<?php endif;?>
				 			</td>  */?>

						 	<td><?=$item->active;?></td>

						 	<td>
							    <div class="controls center">
							        <a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id.$dop_link)?>" title="Edit task" class="tip"><span class="icon12 icomoon-icon-pencil"></span></a>
							        <a href="<?=$this->createUrl('support/delete/'.$model_name.'/'.$item->id.$dop_link)?>" title="Remove task" class="tip"><span class="icon12 icomoon-icon-remove"></span></a>
							    </div>
							</td>

							<td ><input class="chChildren" type="checkbox" name="checkbox" value="<?=$item->id;?>" class="styled" /></td>
							<td><a class="btn btn-info" href="<?=$this->createUrl('support/'.$model_name.'/?banner='.$item->id . '&test_show=1')?>"><?=($item->id ==  $banner_test_show ? 'OK' : '&nbsp;')?></a></td>
						</tr>
					<?php endforeach; ?>
	        </tbody>
	    </table>
	</div>
<?php endif; ?>	 