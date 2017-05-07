<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		 
		'enableAjaxValidation'=>false,
		'htmlOptions'=>array(
	        // 'enctype'=>'multipart/form-data',
	     ),
	)); ?>

		<div class="model-description">
			<p class="note">Поля с <span class="required">*</span> обязательны. <br/ >
			Если нет желания менять пароль - оставьте поля не заполненными </p>
		</div>
		

		<?php echo $form->errorSummary($model); ?>
		<button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button> 

		<?php echo $form->errorSummary($model); ?>

		<div class="row-top"> 


            <div class="row-fluid block">
                <label class="form-label" for="username">Avatar:</label>
                <img src="/img/support/default-ava.jpg" alt="" class="image marginR10"/>
                <input type="file" name="fileinput" id="file"/>
            </div>
        

			<div class="row-fluid row-elements block">
				<?php echo $form->label($model,'fullname'); ?>
				<?php echo $form->textField($model,'fullname'); ?>
				<?php echo $form->error($model,'fullname'); ?>
			</div>  

			<div class="row-fluid row-elements block">
				<?php echo $form->label($model,'email'); ?>
				
				<?php if($model->isNewRecord):?>
					<?php echo $form->textField($model,'email'); ?>
				<?php else: ?>
					<?=$model->email;?>
				<?php endif;?>

				<?php echo $form->error($model,'email'); ?>
			</div> 

			<div class="row-fluid row-elements block">
				<?php echo $form->label($model,'password_new'); ?>
				<?php echo $form->PasswordField($model,'password_new'); ?>
				<?php echo $form->error($model,'password_new'); ?>
			</div> 

			<div class="row-fluid row-elements block">
				<?php echo $form->label($model,'confirm_password'); ?>
				<?php echo $form->PasswordField($model,'confirm_password'); ?>
				<?php echo $form->error($model,'confirm_password'); ?>
			</div> 

			
			<div class="row-fluid row-elements block">
				<?php echo $form->label($model,'active'); ?>
				<?php echo $form->checkBox($model,'active', $model->isNewRecord ? array('checked'=>'checked') : null ); ?>
				<?php echo $form->error($model,'active'); ?>
			</div>

			<?php if($this->role == 'superAdmin'):?>
				<div class=" block">
					<?php echo $form->label($model ,'role'); ?>
					<?php echo $form->dropDownList($model ,'role', 
													$model->getRolesList(),
													 
													array('class' => 'chzn-select nostyle', 'data-placeholder' => 'Выберите роль', 'empty' => 'Выберите роль')); ?>
					<?php echo $form->error($model ,'role'); ?>
				</div>
			<?php endif;?> 
	 	</div>  
	 	<div class="clear"></div>  
        <button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button>
	<?php $this->endWidget(); ?>

</div><!-- form -->