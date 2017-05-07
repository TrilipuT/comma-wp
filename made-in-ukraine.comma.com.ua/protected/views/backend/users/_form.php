 

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Article-form',
	'enableAjaxValidation'=>false, 
	
	'htmlOptions'=>array(
          'enctype'=>'multipart/form-data',
          'class' => 'admin-form',
     ),

	
)); 
?>

	<div class="model-description">
		<p class="note">Поля с <span class="required">*</span> обязательны. <br/ >
			Если нет желания менять пароль - оставьте поля не заполненными </p>
	</div>
	

	<?php echo $form->errorSummary($model); ?>
	<button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button> 
  
	<div class="">

		<div class="block admin-image">
			<?php echo $form->label($model,'file_photo'); ?>

			<?php if($this->getAction()->id == 'update' && !empty($model->file_photo) && file_exists($_SERVER['DOCUMENT_ROOT'].Users::PATH_IMAGE.$model->file_photo) ):
		               
		        echo CHtml::image(Users::PATH_IMAGE.$model->file_photo).'<br />';  
		    	
		    	echo $form->checkBox($model, 'image_delete');
	            echo $form->labelEx($model,'image_delete', array('style'=>'display: inline-block;')).'<br /><br />';
	            echo $form->error($model,'image_delete'); 
		   	else:  
		    	echo CHtml::image('http://placehold.it/50x50').'<br />';   
		    endif; ?>

	    	<?php echo $form->fileField($model,'image'); ?>

		</div>	
 		
 		<div class="row-fluid row-elements block">
			<?php echo $form->label($model,'mail'); ?>
			
			<?php if($model->isNewRecord):?>
				<?php echo $form->textField($model,'mail'); ?>
			<?php else: ?>
				<?=$model->mail;?>
			<?php endif;?>

			<?php echo $form->error($model,'mail'); ?>
		</div>  


		
		<div class="block">
			<?php echo $form->label($model,'name'); ?>
			<?php echo $form->textField($model,'name'); ?>
			<?php echo $form->error($model,'name'); ?>
		</div> 
		 
					 

		<div class="block">
			<?php echo $form->labelEx($model,'active'); ?>
			<?php echo $form->checkBox($model,'active',array('class' => 'ibutton')); ?>
			<?php echo $form->error($model,'active'); ?>
		</div>   
  
		     
 
	</div>	 
 
 

<?php $this->endWidget(); ?>



</div><!-- form -->


