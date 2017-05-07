<?php

$controllers = array();
$dir = opendir('protected/controllers/frontend');
while ($file = readdir($dir))
{
	if ($file == '.' || $file == '..' || !is_file('protected/controllers/frontend/'.$file))
		continue;
	
	$controllers[$file] = $file;
}
closedir($dir);	

?> 

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'section-form',

	'enableAjaxValidation'=>false, 
	
	'htmlOptions'=>array(
         'enctype'=>'multipart/form-data',
         'class' => 'admin-form',
     ),

	
)); ?>
	
	<div class="model-description">
		<p class="note">Поля с <span class="required">*</span> обязательны. <br/> 
			Кодовое имя автоматический берется транслитом с русского имени, если поле пустое то подставляется, если нет то внизу вы увиделе предлогаемый вариант. <br/>
			Так же после сохранении статьи, кодовое имя будет переведенно в транслит <br/>
			Для вставки галереи в тело статьи, вставьте код - ###GALLERY_ID### (где ID - это id галереи) в нужное вам место, после сохранения она появится на сайте
		</p>
	</div>
	

	<?php echo $form->errorSummary($model); ?>
	<button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button> 
 
 	<div class="row-top">
		<div class="block admin-image">
			<?php echo $form->label($model,'shareimage'); ?>

			<?php if($this->getAction()->id == 'update' && !empty($model->share_image) && file_exists($_SERVER['DOCUMENT_ROOT'].Section::PATH_SHARE_IMAGE.$model->share_image) ):

				echo CHtml::image(Section::PATH_SHARE_IMAGE.$model->share_image, null ,array('style' => 'max-width: 450px; max-height:280px')).'<br />';

				echo $form->checkBox($model, 'shareimage_delete');
				echo $form->labelEx($model,'shareimage_delete', array('style'=>'display: inline-block;')).'<br /><br />';
				echo $form->error($model,'shareimage_delete');
			else:
				echo CHtml::image('http://placehold.it/450x280' ).'<br />';
			endif; ?>

			<?php echo $form->fileField($model,'shareimage'); ?>
		</div>
 		<div class="row-elements" style="min-width: 500px;width: auto;">
		 
			<div class="block">
				<?php echo $form->labelEx($model,'code_name'); ?>
				<?php echo $form->textField($model,'code_name',array('size'=>60,'maxlength'=>50, 'class' => 'alias', 'model' => get_class($model), 'id_note' => $model->id)); ?>
				<?php echo $form->error($model,'code_name'); ?>
			
				<div class="Message"></div>
			</div>

			<div class="block">
				<?php echo $form->label($model ,'domain_id'); ?>
				<?php echo CHtml::activeListBox($model, 'domain_id', Section::getSubDomainList(), array('size'=>1)); ?>
				<?php echo $form->error($model ,'domain_id'); ?>
			</div>
			<!--
			<div class="block">
				<?php echo $form->label($model ,'controller'); ?>
				<?php echo CHtml::activeListBox($model, 'controller', $controllers, array('size'=>1)); //, 'prompt'=>'Без контроллера' ?>
				<?php echo $form->error($model ,'controller'); ?>
			</div>   -->
			<div class="block">
				<?php echo $form->label($model ,'controller'); ?>
				<?php echo CHtml::activeListBox($model, 'controller', $controllers, array('size'=>1)); //, 'prompt'=>'Без контроллера' ?>
				<?php echo $form->error($model ,'controller'); ?>
			</div>
			<?/*
		<div class="block">
				<?php echo $form->label($model ,'controller'); ?>
				<?php echo CHtml::activeListBox($model, 'controller', $controllers, array('size'=>1)); //, 'prompt'=>'Без контроллера' ?>
				<?php echo $form->error($model ,'controller'); ?>
			</div>
			<div class="block">
				<?php echo $form->label($model,'image_filename'); ?>

				<?php if($this->getAction()->id == 'update' && !empty($model->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Section::PATH_IMAGE.$model->image_filename) ):
			               
			        echo CHtml::image(Section::PATH_IMAGE.$model->image_filename).'<br />';  
			    	
			    	echo $form->checkBox($model, 'image_delete');
		            echo $form->labelEx($model,'image_delete', array('style'=>'display: inline-block;')).'<br /><br />';
		            echo $form->error($model,'image_delete'); 
			    endif; ?>

		    	<?php echo $form->fileField($model,'image'); ?>  
			</div>	 
			-->  


			<div class="block">
	            <?php echo $form->label($model ,'gallery_id'); ?>
	            <?php echo $form->dropDownList($model ,'gallery_id', 
	                                            CHtml::listData(Gallery::model()->orderByOrderNum()->findAll('in_article = 1'), 'id', 'transfer.name'),  
	                                            array(	'class' => '', 
	                                            		'data-placeholder' => 'Выберите галерею',
	                                            		'empty' => array( 0 => 'Выберите галерею') )); ?>
	            <?php echo $form->error($model ,'gallery_id'); ?> 
	        </div>
			

	<!--
			<div class="block">
				<?php echo $form->label($model,'ico'); ?>
				<?php if($this->getAction()->id == 'update' && !empty($model->ico_filename) && file_exists($_SERVER['DOCUMENT_ROOT'].Section::PATH_ICON.$model->ico_filename) ):
			               
			        echo CHtml::image(Section::PATH_ICON.$model->ico_filename).'<br />';  
			    	
			    	echo $form->checkBox($model, 'ico_delete');
		            echo $form->labelEx($model,'ico_delete', array('style'=>'display: inline-block;')).'<br /><br />';
		            echo $form->error($model,'ico_delete'); 
			    endif; ?>

		    	<?php echo $form->fileField($model,'ico'); ?>

			</div>	 
		--> */?>
		</div>
		<div class="row-elements" style="min-width: 500px;width: auto;">

			<div class="block">
				<?php echo $form->label($model,'order_num'); ?>
				<?php echo $form->textField($model,'order_num'); ?>
				<?php echo $form->error($model,'order_num'); ?>
			</div>

			<div class="block">
				<?php echo $form->label($model,'menu_top'); ?>
				<?php echo $form->checkbox($model,'menu_top'); ?>
				<?php echo $form->error($model,'menu_top'); ?>
			</div>

			<div class="block">
				<?php echo $form->label($model,'menu_main'); ?>
				<?php echo $form->checkbox($model,'menu_main'); ?>
				<?php echo $form->error($model,'menu_main'); ?>
			</div>
			<!--
			<div class="block">
				<?php echo $form->label($model,'home'); ?>
				<?php echo $form->checkbox($model,'home'); ?>
				<?php echo $form->error($model,'home'); ?>
			</div>-->
		</div>
		<div class="row-elements" style="min-width: 500px;width: auto;">
			<div class="block">
				<?php echo $form->label($model ,'color_class'); ?>
				<?php echo $form->dropDownList($model ,'color_class',
					$model->colorArray(),
					array('class' => '', 'empty' => 'выберите цвет')); ?>
				<?php echo $form->error($model ,'color_class'); ?>
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
				<?php echo $form->labelEx($model,'active'); ?>
				<?php echo $form->checkBox($model,'active',array('class' => 'ibutton nostyle')); ?>
				<?php echo $form->error($model,'active'); ?>
			</div>
		</div>
	</div>

	<div class="row-bottom"> 

		<?php if($this->languageList): ?>
 
				<ul id="myTab" class="nav nav-tabs pattern"> 
					<?php $key = 0; 

						foreach($this->languageList as $lang_id=>$lang_name): 

								$activeClass = '';

								if($key == 0){
									$activeClass = 'active';
								}
							?> 
							
							<li class="<?=$activeClass;?>" ><a href="#tabs-<?=$lang_id;?>" data-toggle="tab" ><?=$lang_name;?></a></li> 

					<?php $key++; endforeach;?>  
				</ul>

				<div class="tab-content">

					<?php 
						$key = 0;

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

								<div class="block">
									<?php echo $form->label($model_transfer,'name'); ?>
									<?php echo $form->textField($model_transfer,'name['.$lang_id.']',array('size'=>60,'maxlength'=>255, 'value' => $model_transfer->name, 'class' => $nameToAlias)); ?>
									<?php echo $form->error($model_transfer,'name'); ?>
								</div>

								<div class="block">
									<?php echo $form->label($model_transfer,'menu_name'); ?>
									<?php echo $form->textField($model_transfer,'menu_name['.$lang_id.']',array('size'=>60,'maxlength'=>255,  'value' => $model_transfer->menu_name )); ?>
									<?php echo $form->error($model_transfer,'menu_name'); ?>
								</div>

								<div class="block">
									<?php echo $form->label($model_transfer,'description'); ?>
									<?php echo $form->textArea($model_transfer,'description['.$lang_id.']',array('blocks'=>30, 'cols'=>80 , 'value' => $model_transfer->description )); ?>
									<?php echo $form->error($model_transfer,'description'); ?>
								</div>


							</div>
							
							<div class="block">
								<?php echo $form->label($model_transfer,'content'); ?>
								<?php echo $form->textArea($model_transfer,'content['.$lang_id.']',array('blocks'=>30, 'cols'=>80 , 'class'=>'tiny_mce', 'value' => $model_transfer->content )); ?>
								<?php echo $form->error($model_transfer,'content'); ?>
							</div>

							<div class="block">
								<?php echo $form->label($model_transfer,'seo_text'); ?>
								<?php echo $form->textArea($model_transfer,'seo_text['.$lang_id.']',array('blocks'=>30, 'cols'=>80 , 'class'=>'tiny_mce', 'value' => $model_transfer->seo_text )); ?>
								<?php echo $form->error($model_transfer,'seo_text'); ?>
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
    <button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button> 

<?php $this->endWidget(); ?>

</div><!-- form -->