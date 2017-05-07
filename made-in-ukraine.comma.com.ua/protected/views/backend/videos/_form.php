 

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'news-form',
	'enableAjaxValidation'=>false, 
	
	'htmlOptions'=>array(
          'enctype'=>'multipart/form-data',
          'class' => 'admin-form',
     ),

	
)); 
?>

	<div class="model-description">
		<p class="note">Поля с <span class="required">*</span> обязательны. <br/> 
			Кодовое имя автоматический берется транслитом с русского имени, если поле пустое то подставляется, если нет то внизу вы увиделе предлогаемый вариант. <br/>
			Так же после сохранении статьи, кодовое имя будет переведенно в транслит</p>
        <p>
            Для вставки галереи в тело статьи, вставьте код - ###GALLERY_ID### (где ID - это id галереи) в нужное вам место, после сохранения она появится на сайте</p>
	</div>
	

	<?php echo $form->errorSummary($model); ?>
	<button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button> 
 	<?php /*if(!$model->isNewRecord): ?>
        <button id="show-preview" class="btn btn-warning" target="_blank">Предпросмотр</button>
    <?php endif;*/?>
 

 	<div class="row-top">  
 	<ul  class="nav nav-tabs pattern"> 
        <li class="active"><a data-toggle="tab" href="#tabs-info">Информация</a></li>
        <?php if(!$model->isNewRecord):?>
            <li><a data-toggle="tab" href="#tabs-image">Изображения</a></li> 
	    <?php endif;?> 
	    <li class=""><a data-toggle="tab" href="#tabs-tags">Теги</a></li>
    </ul>
    <div class="tab-content">

    	<div class="tab-pane fade in active" id="tabs-info" style="height1: 400px;">
    		
   

			<div class="block admin-image">
				<?php echo $form->label($model,'image_filename'); ?>

				<?php if($this->getAction()->id == 'update' && !empty($model->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Videos::PATH_IMAGE.$model->image_filename) ):
			               
			        echo CHtml::image(Videos::PATH_IMAGE.$model->image_filename).'<br />';  
			    	
			    	echo $form->checkBox($model, 'image_delete');
		            echo $form->labelEx($model,'image_delete', array('style'=>'display: inline-block;')).'<br /><br />';
		            echo $form->error($model,'image_delete'); 
			   	else:  
			    	echo CHtml::image('http://placehold.it/450x280').'<br />';   
			    endif; ?>

		    	<?php echo $form->fileField($model,'image'); ?>

			</div>


            <div class="block admin-image">
                <?php echo $form->label($model,'shareimage'); ?>

                <?php if($this->getAction()->id == 'update' && !empty($model->share_image) && file_exists($_SERVER['DOCUMENT_ROOT'].Videos::PATH_SHARE_IMAGE.$model->share_image) ):

                    echo CHtml::image(Videos::PATH_SHARE_IMAGE.$model->share_image).'<br />';

                    echo $form->checkBox($model, 'shareimage_delete');
                    echo $form->labelEx($model,'shareimage_delete', array('style'=>'display: inline-block;')).'<br /><br />';
                    echo $form->error($model,'shareimage_delete');
                else:
                    echo CHtml::image('http://placehold.it/450x280').'<br />';
                endif; ?>

                <?php echo $form->fileField($model,'shareimage'); ?>
            </div>
 
			<div class="block">
	            <?php echo $form->label($model ,'gallery_id'); ?>
	            <?php echo $form->dropDownList($model ,'gallery_id', 
	                                            CHtml::listData(Gallery::model()->orderByOrderNum()->findAll('in_article = 1'), 'id', 'transfer.name'),  
	                                            array(	'class' => '', 
	                                            		'data-placeholder' => 'Выберите галерею',
	                                            		'empty' => array( 0 => 'Выберите галерею') )); ?>
	            <?php echo $form->error($model ,'gallery_id'); ?> 
	        </div>

 
			<div class="block">
	            <?php echo $form->label($model ,'category_id'); ?>
	            <?php echo $form->dropDownList($model ,'category_id', 
	                                            CHtml::listData(VideoCats::model()->orderByOrderNum()->findAll(), 'id', 'transfer.name'),  
	                                            array('data-placeholder' => 'Выберите категорию', 'empty' => array( 0 => 'Выберите категорию'), 'class' => '')); ?>
	            <?php echo $form->error($model ,'category_id'); ?> 
	        </div>

	        <div class="block">
		        <?php echo $form->label($model,'datetime'); ?>  
					<?php echo $form->textField($model,'datetime', array('id' => 'datetime')); ?>
		        <?php echo $form->error($model,'datetime'); ?>
		    </div> 

	        <div class="block">
				<?php echo $form->label($model,'views_num'); ?>
				<?php echo $form->textField($model,'views_num'); ?>
				<?php echo $form->error($model,'views_num'); ?>
			</div>


			<div class="block">
				<?php echo $form->labelEx($model,'code_name'); ?>
				<?php echo $form->textField($model,'code_name',array('size'=>60,'maxlength'=>50, 'class' => 'alias', 'model' => get_class($model), 'id_note' => $model->id)); ?>
				<?php echo $form->error($model,'code_name'); ?>
				<div class="Message"></div> 
			</div>     
			

			<div class="row-elements">
				<?php echo $form->label($model,'video_code'); ?>
				<?php echo $form->textArea($model,'video_code',array('rows'=>10, 'cols'=>60  )); ?>
				<?php echo $form->error($model,'video_code'); ?>
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

			<!--
			<div class="block"> 
				<?php echo CHtml::dropDownList(get_class($model).'[authors][]',  											
												CHtml::listData(VideosHasAuthors::model()->withVideos($model->id)->findAll(), 'id', 'authors_id'),
												CHtml::listData(Authors::model()->published()->findAll(), 'id', 'transfer.name'), 
												array('multiple'=>'multiple','class' => 'chzn-select nostyle','data-placeholder' => 'Выберите авторов') ); ?> 			
			</div>
			--> 
			 
			<div class="block">
				<?php echo $form->labelEx($model,'active'); ?>
				<?php echo $form->checkBox($model,'active',array('class' => 'ibutton')); ?>
				<?php echo $form->error($model,'active'); ?>
			</div>  
		</div> 


		<?php if(!$model->isNewRecord):?>

	        <div class="tab-pane fade" id="tabs-image">
	            <div class="form" id="block_thumbnail">

	                <h4>иконка</h4>
	                <?php echo DBHtml::activeCropper($model, 'image'); ?>
	                
	                <?php echo $form->checkBox($model, 'image_delete'); ?>
	                <?php echo $form->labelEx($model,'image_delete', array('style'=>'display: inline-block;')).'<br /><br />'; ?>  

	            </div>
	        </div>
	    <?php endif;?>

		    
		<div class="tab-pane fade" id="tabs-tags" style="min-height: 400px;">
                <?php $tagsItems = VideosHasTegs::model()->withVideos($model->id)->findAll();

                    if(count($tagsItems) > 0): ?>

	                    <div class="row">  
	                        <div class="block" style="width: 250px;"> 
	                        рус. вер.
	                        </div>
	                        <div class="block" style="width: 250px;"> 
	                        укр. вер.
	                        </div>
	                    </div>

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
	                                <button type-data="video-tag" data-id="<?=$HasTags->id?>" class="del-tag btn btn-danger btn-mini" href="#">Удалить</button>
	                            </div>
	                            
	                        <?php endif;?>        
	                    <?php endforeach;?>
	                <?php endif;?> 

                <div class="clear"></div>
                <button class="add-new-tag btn btn-primary btn-mini" href="#">добавить</button>
            </div> 
        </div>    
	</div>	    

	<div class="row-bottom">

		<?php if(count($this->languageList) > 0): ?>

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
							<?php echo $form->textField($model_transfer,'name['.$lang_id.']',array('size'=>60, 'maxlength'=>255, 'value' => $model_transfer->name, 'class' => $nameToAlias)); ?>
							<?php echo $form->error($model_transfer,'name'); ?>
						</div>

						<div class="row-elements">
							<?php echo $form->label($model_transfer,'sub_name'); ?>
							<?php echo $form->textField($model_transfer,'sub_name['.$lang_id.']',array('size'=>60, 'maxlength'=>255, 'value' => $model_transfer->sub_name)); ?>
							<?php echo $form->error($model_transfer,'sub_name'); ?>
						</div>

						<div class="row-elements">
							<?php echo $form->label($model_transfer,'description'); ?>
							<?php echo $form->textArea($model_transfer,'description['.$lang_id.']',array('rows'=>10, 'cols'=>60 , 'maxlength'=>255,   'value' => $model_transfer->description )); ?>
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


