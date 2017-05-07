<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'servise-form',
		//'enableAjaxValidation'=>false, 
		
		'htmlOptions'=>array(
	          //'enctype'=>'multipart/form-data',
	          'class' => 'admin-form',
	     )  
		
	)); 
	?>   	 
	<button class="btn btn-primary">Сохранить</button>

 

		<?php if($settingsItems): ?>
			
			<div class="row-top">
				<div class="block">
						
					<div class="">
						<?php echo $form->label($settingsItems[0],$settingsItems[0]->parameter); ?> 
					</div>  

					<div class="">
						<input name="Settings[<?=$settingsItems[0]->parameter;?>]" value="<?=$settingsItems[0]->value?>" /> 
					</div>  

				</div>  

				<div class="block">
						
					<div class="">
						<?php echo $form->label($settingsItems[1],$settingsItems[1]->parameter); ?> 
					</div>  

					<div class="">
						<input name="Settings[<?=$settingsItems[1]->parameter;?>]" value="<?=$settingsItems[1]->value?>" /> 
					</div>  

				</div>  

				<div class="block">
						
					<div class="">
						<?php echo $form->label($settingsItems[2],$settingsItems[2]->parameter); ?> 
					</div>  

					<div class="">
						<input name="Settings[<?=$settingsItems[2]->parameter;?>]" value="<?=$settingsItems[2]->value?>" /> 
					</div>  

				</div>   

			</div>    


			<div class="row-top">
				<div class="block">
						
					<div class="">
						<?php echo $form->label($settingsItems[3],$settingsItems[3]->parameter); ?> 
					</div>  

					<div class="">
						<input name="Settings[<?=$settingsItems[3]->parameter;?>]" value="<?=$settingsItems[3]->value?>" /> 
					</div>  

				</div>  

				<div class="block">
						
					<div class="">
						<?php echo $form->label($settingsItems[4],$settingsItems[4]->parameter); ?> 
					</div>  

					<div class="">
						<input name="Settings[<?=$settingsItems[4]->parameter;?>]" value="<?=$settingsItems[4]->value?>" /> 
					</div>  

				</div>  

				<div class="block">
						
					<div class="">
						<?php echo $form->label($settingsItems[5],$settingsItems[5]->parameter); ?> 
					</div>  

					<div class="">
						<input name="Settings[<?=$settingsItems[5]->parameter;?>]" value="<?=$settingsItems[5]->value?>" /> 
					</div>  

				</div>   

				<div class="block">
						
					<div class="">
						<?php echo $form->label($settingsItems[6],$settingsItems[6]->parameter); ?> 
					</div>  

					<div class="">
						<input name="Settings[<?=$settingsItems[6]->parameter;?>]" value="<?=$settingsItems[6]->value?>" /> 
					</div>  

				</div>  

				<div class="block">
						
					<div class="">
						<?php echo $form->label($settingsItems[7],$settingsItems[7]->parameter); ?> 
					</div>  

					<div class="">
						<input name="Settings[<?=$settingsItems[7]->parameter;?>]" value="<?=$settingsItems[7]->value?>" /> 
					</div>  

				</div>   

			</div>    


			<div class="row-top">
				<div class="block">
						
					<div class="">
						<?php echo $form->label($settingsItems[8],$settingsItems[8]->parameter); ?> 
					</div>  

					<div class="">
						<input name="Settings[<?=$settingsItems[8]->parameter;?>]" value="<?=$settingsItems[8]->value?>" /> 
					</div>  

				</div>   

			</div>    


			<?php /*foreach($settingsItems as $key=>$model):?>
					 
					<div class="row-top block">

						<div class="row">
							<?php echo $form->label($model,$model->parameter); ?> 
						</div>  

						<div class="row">
							<input name="Settings[<?=$model->parameter;?>]" value="<?=$model->value?>" /> 
						</div>    
					</div>    

			<?php endforeach;*/?>
		<?php endif; ?>   
			   
  
	<div class="clear"></div>
			
		
	<?php $this->endWidget(); ?> 
</div><!-- form -->