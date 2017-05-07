 <div class="form">
	<?php if(!$model->isNewRecord): ?>
		<form>
			<div id="queue"></div>
			<input id="file_upload" name="file_upload" type="file" multiple="true" class="nostyle">
		</form>

		<script type="text/javascript">			

			<?php $timestamp = time();?>
			$(document).ready(function() {


				$('#file_upload').uploadify({
					'formData'     : {
						'timestamp' : '<?=$timestamp;?>',
						'token'     : '<?=md5('unique_salt' . $timestamp);?>',
						'data_id' 	: '<?=$model->id;?>' 	
					},
					'swf'      : '/js/support/uploadify/uploadify.swf',
					'uploader' : '/support/ajax/uploadify?className=Gallery',
					'onUploadSuccess' : function(file, data, response) { 

			           	data = jQuery.parseJSON(data);  

						if(data.success == 1){

							$('#photos-table tr:last').after(data.html); 

							/*
							$(".chzn-select").chosen({
					      		no_results_text: "Нажмите enter или space чтобы добавить новый элемент",
								allow_add_by_enter: true, 
								allow_add_by_space: true
							}); */

							$("input").not('.nostyle').uniform.restore().uniform();
			            }  

			        }  
				});
			});
		</script>
	<?php endif;?>	


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gallery-form',
	'enableAjaxValidation'=>false, 
	
	'htmlOptions'=>array(
          'enctype'=>'multipart/form-data',
          'class' => 'admin-form',
     ),

	
)); 
?>

	<div class="model-description">
		<p class="note">Поля с <span class="required">*</span> обязательны </p>
	</div>
	

	<?php echo $form->errorSummary($model); ?>
	<button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button> 
 

 	<div class="row-top">  

 		<div class="block admin-image">
			<?php echo $form->label($model,'gif_filename'); ?>

			<?php if($this->getAction()->id == 'update' && !empty($model->gif_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Gallery::PATH_IMAGE_GIF.$model->gif_filename) ):
		               
		        echo CHtml::image(Gallery::PATH_IMAGE_GIF.$model->gif_filename).'<br />';  
		    	
		    	echo $form->checkBox($model, 'gif_delete');
	            echo $form->labelEx($model,'gif_delete', array('style'=>'display: inline-block;')).'<br /><br />';
	            echo $form->error($model,'gif_delete'); 
		   	else:  
		    	echo CHtml::image('http://placehold.it/200x200').'<br />';   
		    endif; ?>

	    	<?php echo $form->fileField($model,'gif'); ?> 
		</div>  

 		<div class="block admin-image">
			<?php echo $form->label($model,'image_filename'); ?>

			<?php if($this->getAction()->id == 'update' && !empty($model->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Gallery::PATH_IMAGE.$model->image_filename) ):
		               
		        echo CHtml::image(Gallery::PATH_IMAGE.$model->image_filename).'<br />';  
		    	
		    	echo $form->checkBox($model, 'image_delete');
	            echo $form->labelEx($model,'image_delete', array('style'=>'display: inline-block;')).'<br /><br />';
	            echo $form->error($model,'image_delete'); 
		   	else:  
		    	echo CHtml::image('http://placehold.it/200x200').'<br />';   
		    endif; ?>

	    	<?php echo $form->fileField($model,'image'); ?>

		</div>

        <div class="block admin-image">
            <?php echo $form->label($model,'shareimage'); ?>

            <?php if($this->getAction()->id == 'update' && !empty($model->share_image) && file_exists($_SERVER['DOCUMENT_ROOT'].Gallery::PATH_SHARE_IMAGE.$model->share_image) ):

                echo CHtml::image(Gallery::PATH_SHARE_IMAGE.$model->share_image).'<br />';

                echo $form->checkBox($model, 'shareimage_delete');
                echo $form->labelEx($model,'shareimage_delete', array('style'=>'display: inline-block;')).'<br /><br />';
                echo $form->error($model,'shareimage_delete');
            else:
                echo CHtml::image('http://placehold.it/450x280').'<br />';
            endif; ?>

            <?php echo $form->fileField($model,'shareimage'); ?>
        </div>

		<div class="block" style="display: none;">
			<?php echo $form->labelEx($model,'code_name'); ?>
			<?php echo $form->textField($model,'code_name',array('size'=>60,'maxlength'=>50, 'class' => 'alias', 'model' => get_class($model), 'id_note' => $model->id)); ?>
			<?php echo $form->error($model,'code_name'); ?>
		</div>    


		<div class="block">
	        <?php echo $form->label($model,'datetime'); ?>  
				<?php echo $form->textField($model,'datetime', array('id' => 'datetime')); ?>
	        <?php echo $form->error($model,'datetime'); ?>
	    </div> 

 		<div class="block">
            <?php echo $form->label($model ,'changefreq'); ?>
            <?php echo $form->dropDownList($model ,'changefreq', 
                                            $model->getChangefreqList(),  
                                            array('class' => '')); ?>
            <?php echo $form->error($model ,'changefreq'); ?> 
        </div>

        <div class="block">
            <?php echo $form->label($model,'priority'); ?>
            <?php echo $form->textField($model,'priority'); ?>
            <?php echo $form->error($model,'priority'); ?>
        </div>  

	 
		<div class="block">
			<?php echo $form->label($model,'order_num'); ?>
			<?php echo $form->textField($model,'order_num'); ?>
			<?php echo $form->error($model,'order_num'); ?>
		</div>

		<div class="block"> 
			<?php echo CHtml::dropDownList(get_class($model).'[authors][]',  											
											CHtml::listData(GalleryHasAuthors::model()->withGallery($model->id)->findAll(), 'id', 'authors_id'),
											CHtml::listData(Authors::model()->published()->findAll('t.photographer = 1'), 'id', 'transfer.name'), 
											array('multiple'=>'multiple','class' => 'chzn-select nostyle','data-placeholder' => 'Выберите авторов') ); ?> 			
		</div> 

		 
		<div class="block">
			<?php echo $form->labelEx($model,'in_article'); ?>
			<?php echo $form->checkBox($model,'in_article',array('class' => 'ibutton nostyle')); ?>
			<?php echo $form->error($model,'in_article'); ?>
		</div>  

		<div class="block">
			<?php echo $form->labelEx($model,'active'); ?>
			<?php echo $form->checkBox($model,'active',array('class' => 'ibutton nostyle')); ?>
			<?php echo $form->error($model,'active'); ?>
		</div>  

	</div>	
	<div class="clear"></div>
	<div class=" " id="tabs-tags" style=" ">

		<div class="row">  
            <div class="block" style="width: 250px;"> 
            рус. вер.
            </div>
            <div class="block" style="width: 250px;"> 
            укр. вер.
            </div>
        </div>
        
        <?php $tagsItems = GalleryHasTegs::model()->withGallery($model->id)->findAll();

            if(count($tagsItems) > 0): ?> 

                <?php foreach($tagsItems as $HasTags): ?>  
                    <?php if(count($this->languageList) > 0): ?>
                        

                        <div class="row">  

                            <?php foreach ($this->languageList as $lang_id=>$lang_name): 
                                
                                $tagsTransferItems = TagsTransfer::model()->findAll('parent_id = :parent_id AND language_id = :language_id', 
                                                                                   array(':parent_id' => $HasTags->teg_id, ':language_id' => $lang_id)); 


                                if(count($tagsTransferItems) > 0):?> 
                                    <?php foreach($tagsTransferItems as $TagsTransfer):?>

                                        <div class="block" style="width: 250px;background: #c4c4c4;font-weight: bold;padding-left: 10px;">
                                            <?php //echo CHtml::label('TagsTransfer', ''); ?>
                                            <?php //echo CHtml::textField('TagsTransfer['.$TagsTransfer->id.']', $TagsTransfer->name ,array('size'=>60,'maxlength'=>255, 'class' => 'tag-autocomplete')); ?>
                                            <?php echo $TagsTransfer->name; ?>
                                        </div>

                                    <?php endforeach;?>

                                <?php else: ?>

                                    <div class="block" style="width: 250px;">
                                        <?php //echo CHtml::label('TagsTransfer', ''); ?>
                                        <?php echo CHtml::textField('TagsTransfer['.$TagsTransfer->id.']', $TagsTransfer->name ,array('size'=>60,'maxlength'=>255, 'class' => 'tag-autocomplete')); ?> 
                                    </div>

                                <?php endif;?>
                                
                            <?php endforeach;?>
                            <button type-data="gallery-tag" data-id="<?=$HasTags->id?>" class="del-tag btn btn-danger btn-mini" href="#">Удалить</button>
                        </div>
                        
                    <?php endif;?>        
                <?php endforeach;?>
            <?php endif;?> 

        <div class="clear"></div>
        <button class="add-new-tag btn btn-primary btn-mini" href="#">добавить</button>
    </div>


	<?php if(!$model->isNewRecord): ?>

		<?php 

			$photosItems = Photos::model()->orderByOrderNum()->withGallery($model->id)->findAll(); 

		?> 
		<div class="span11">
	 		
			<table id="photos-table" class="responsive table table-bordered"> 
				<tr>
					<th width="1%">ID</th>
					<th width="5%"></th> 
					<th  width="7%">order_num</th>
					<th  width="5%">active</th>
					<th width="1%"></th>
				</tr> 

				<?php if(count($photosItems) > 0): ?>
					<?php foreach($photosItems as $Photos):?>
							<tr>
		                        <td><?=$Photos->id;?></td>

		                        <td>
		                        	<?php if(!empty($Photos->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Photos::PATH_IMAGE.$Photos->image_filename) ):
		               
								        echo CHtml::image(Photos::PATH_IMAGE.$Photos->image_filename);   
								    endif; ?>
		                        </td>
 
		                        <td> 
		                            <input name="<?=get_class($model)?>[photosRow][<?=$Photos->id;?>][order_num]" maxlength="5" size="3" type="text" value="<?=$Photos->order_num;?>" style=" width: 45px;" />
		                        </td>
		                        <td>
		                            <input <?=(($Photos->active == 1)? 'checked' : '')?> name="<?=get_class($model)?>[photosRow][<?=$Photos->id;?>][active]" size="1" type="checkbox" value="1" />
		                        </td>
		                        <td><span type="salesToolkit" id-file="<?=$Photos->id;?>" id-model="<?=$model->id;?>" class="del-photo minia-icon-close"></span> </td>
		                    </tr>
					<?php endforeach;?>
				<?php endif;?>
			</table>
		</div>
	<?php endif;?>



		
	<div class="row-bottom">

		<?php if($this->languageList): ?>

				<ul id="myTab" class="nav nav-tabs pattern"> 
					<?php  $key = 0; 

						foreach($this->languageList as $lang_id=>$lang_name): 

							$activeClass = '';

						if($key == 0){
							$activeClass = 'active';
						} 
					?> 
							<li class="<?=$activeClass;?>" ><a data-toggle="tab"  href="#tabs-<?=$lang_id;?>"><?=$lang_name;?></a></li> 
					<?php $key++; endforeach;?>  
				</ul>

				<div class="tab-content">

				<?php $key = 0; 

					foreach($this->languageList as $lang_id=>$lang_name):  


						if(!$model->isNewRecord)
							$model_transfer = $this->loadTransferModel(get_class($model_transfer), $model->id, $lang_id);

						$nameToAlias = '';
						if($lang_id == 1){
							$nameToAlias = 'nameToAlias';
						}

						$activeClass = '';

						if($key == 0){
							$activeClass = 'in active';
						}
				?>

					<div class="tab-pane fade <?=$activeClass;?>" id="tabs-<?=$lang_id;?>">

						<div class="row-elements">
							<?php echo $form->label($model_transfer,'name'); ?>
							<?php echo $form->textField($model_transfer,'name['.$lang_id.']',array('size'=>60,'maxlength'=>255, 'value' => $model_transfer->name, 'class' => $nameToAlias)); ?>
							<?php echo $form->error($model_transfer,'name'); ?>
						</div> 


						<div class="row-elements">
							<?php echo $form->label($model_transfer,'place'); ?>
							<?php echo $form->textField($model_transfer,'place['.$lang_id.']',array('size'=>60,'maxlength'=>255, 'value' => $model_transfer->place )); ?>
							<?php echo $form->error($model_transfer,'place'); ?>
						</div>  

						
  						
  						<div class="row-elements">
							<?php echo $form->label($model_transfer,'description'); ?>
							<?php echo $form->textArea($model_transfer,'description['.$lang_id.']',array('rows'=>10, 'cols'=>60,  'value' => $model_transfer->description )); ?>
							<?php echo $form->error($model_transfer,'description'); ?>
						</div>
						
  						<div class="row-elements">
							<?php echo $form->label($model_transfer,'content'); ?>
							<?php echo $form->textArea($model_transfer,'content['.$lang_id.']',array('rows'=>30, 'cols'=>80 , 'class'=>'tiny_mce', 'value' => $model_transfer->content )); ?>
							<?php echo $form->error($model_transfer,'content'); ?>
						</div>
  						
  						<div class="row-elements">

							<div class="block">
								<?php echo $form->labelEx($model_transfer,'meta_keywords'); ?>
								<?php echo $form->textField($model_transfer,'meta_keywords['.$lang_id.']',array('size'=>60,'maxlength'=>255, 'value' => $model_transfer->meta_keywords )); ?>
								<?php echo $form->error($model_transfer,'meta_keywords'); ?>
							</div>

							<div class="block">
								<?php echo $form->labelEx($model_transfer,'meta_description'); ?>
								<?php echo $form->textField($model_transfer,'meta_description['.$lang_id.']',array('size'=>60,'maxlength'=>255, 'value' => $model_transfer->meta_description  )); ?>
								<?php echo $form->error($model_transfer,'meta_description'); ?>
							</div>

							<div class="block">
								<?php echo $form->labelEx($model_transfer,'page_title'); ?>
								<?php echo $form->textField($model_transfer,'page_title['.$lang_id.']',array('size'=>60,'maxlength'=>255, 'value' => $model_transfer->page_title)); ?>
								<?php echo $form->error($model_transfer,'page_title'); ?>
							</div>
						</div>	

					</div>	

				<?php $key++; endforeach;?>

			</div> 
		<?php endif;?>
	</div>

	<div class="clear"></div>
 

<?php $this->endWidget(); ?>

</div><!-- form -->