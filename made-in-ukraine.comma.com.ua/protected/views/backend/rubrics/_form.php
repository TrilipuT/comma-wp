 

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
			Так же после сохранении статьи, кодовое имя будет переведенно в транслит<br/> 
			Родителями выбирать можно рубрики только первого уровня!

		</p>
	</div>
	

	<?php echo $form->errorSummary($model); ?>
	<button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button> 
 

 	<div class="row-top">  

 
<? /*
		<div class="block">
            <?php echo $form->label($model ,'gallery_id'); ?>
            <?php echo $form->dropDownList($model ,'gallery_id', 
                                            Gallery::getList(),  
                                            array(	'class' => '', 
                                            		'data-placeholder' => 'Выберите галерею',
                                            		'empty' => array( 0 => 'Выберите галерею') )); ?>
            <?php echo $form->error($model ,'gallery_id'); ?> 
        </div>

*/?> 	

		<div class="block">
			<?php echo $form->label($model,'color'); ?>
			<input type="text" id="color" name="<?=get_class($model);?>[color]" value="<?=$model->color?>" /><div class="picker"></div>
			<?php echo $form->error($model,'color'); ?>
		</div>


		<div class="block">
			<?php echo $form->labelEx($model,'code_name'); ?>
			<?php echo $form->textField($model,'code_name',array('size'=>60,'maxlength'=>50, 'class' => 'alias', 'model' => get_class($model), 'id_note' => $model->id)); ?>
			<?php echo $form->error($model,'code_name'); ?>
		</div>    

		<div class="Message"></div>  
 		

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
            <?php echo $form->label($model ,'color_class'); ?>
            <?php echo $form->dropDownList($model ,'color_class', 
                                            $model->colorArray(),  
                                            array('class' => '', 'empty' => 'выберите цвет')); ?>
            <?php echo $form->error($model ,'color_class'); ?> 
        </div>
 
	 
		<div class="block">
			<?php echo $form->label($model,'order_num'); ?>
			<?php echo $form->textField($model,'order_num'); ?>
			<?php echo $form->error($model,'order_num'); ?>
		</div>

		 
		<div class="block">
			<?php echo $form->labelEx($model,'active'); ?>
			<?php echo $form->checkBox($model,'active',array('class' => 'ibutton')); ?>
			<?php echo $form->error($model,'active'); ?>
		</div>   



		<div class="block">
            <?php echo $form->label($model ,'parent_id'); 

            	if($model->isNewRecord){
            		$id = 0;
            	} else {
            		$id = $model->id;
            	}

            ?>
            <?php echo $form->dropDownList($model ,'parent_id', 
                                            CHtml::listData(Rubrics::model()->orderByOrderNum()->findAll('id != :id AND parent_id = 0', 
                                            															array(':id' => $id)), 'id', 'transfer.name'),  
                                            array('data-placeholder' => 'Выберите родителя', 'empty' => array( 0 => 'Выберите родителя'), 'class' => '')); ?>
            <?php echo $form->error($model ,'parent_id'); ?> 
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


