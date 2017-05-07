  
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
	</div>
	

	<?php echo $form->errorSummary($model); ?>
	<button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button> 
 

 	<div class="row-top">    
	 
	 
		<div class="block">
			<?php echo $form->label($model,'order_num'); ?>
			<?php echo $form->textField($model,'order_num'); ?>
			<?php echo $form->error($model,'order_num'); ?>
		</div>

		 
		<div class="block">
			<?php echo $form->labelEx($model,'active'); ?>
			<?php echo $form->checkBox($model,'active',array('class' => 'ibutton nostyle')); ?>
			<?php echo $form->error($model,'active'); ?>
		</div>  

	</div>	 
 

	<div class="row-bottom">

		<?php if(Language::model()->getLanguageList()): ?>

				<ul id="myTab" class="nav nav-tabs pattern"> 
					<?php  $key = 0; 

						foreach(Language::model()->getLanguageList() as $lang_id=>$lang_name): 

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

					foreach(Language::model()->getLanguageList() as $lang_id=>$lang_name):  


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
							<?php echo $form->textField($model_transfer,'name['.$lang_id.']',array('size'=>60,'maxlength'=>50, 'value' => $model_transfer->name, 'class' => $nameToAlias)); ?>
							<?php echo $form->error($model_transfer,'name'); ?>
						</div> 
		 
					</div>	

				<?php $key++; endforeach;?>

			</div> 
		<?php endif;?>
	</div>

	<div class="clear"></div>
 

<?php $this->endWidget(); ?>

</div><!-- form -->