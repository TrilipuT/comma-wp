<?php

$this->breadcrumbs=array(
	$this->module->id,
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	//'id'=>'section-form',
	'enableAjaxValidation'=>false, 
	'action' => '/support/parseExcel/ottenki_add',
	'htmlOptions'=>array(
          'enctype'=>'multipart/form-data',

     ),

	
)); ?>

<div class="row">
	<?php echo  'ottenki' ; ?>  
	<?php echo CHtml::fileField( 'ottenki'); ?> 
</div>	


<div class="row buttons">
	<?php echo CHtml::submitButton(  'Добавить' ); ?>
</div>

<?php $this->endWidget(); ?>