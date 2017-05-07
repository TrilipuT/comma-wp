<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'language-form',
		'enableAjaxValidation'=>false,
	)); ?>

		<div class="model-description">
			<p class="note">Поля с <span class="required">*</span> обязательны.</p>
		</div>

		<button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button> 

		<?php echo $form->errorSummary($model); ?>

		<div class="row-top "> 

			<div class="block">
				<?php echo $model->code_name; ?>  
			</div>

			<div class="block">
				<?php echo $form->labelEx($model,'name'); ?>
				<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>

			<div class="block">
				<?php echo $form->labelEx($model,'order_num'); ?>
				<?php echo $form->textField($model,'order_num'); ?>
				<?php echo $form->error($model,'order_num'); ?>
			</div>

			<div class="block">
				<?php echo $form->labelEx($model,'active'); ?>
				<?php echo $form->checkBox($model,'active',array('class' => 'ibutton nostyle')); ?>
				<?php echo $form->error($model,'active'); ?>
			</div>  
		</div>
		<div class="clear"></div>
        <button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button>
	<?php $this->endWidget(); ?>

</div><!-- form -->