  
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false, 
	
	'htmlOptions'=>array(
          'enctype'=>'multipart/form-data',
          'class' => 'admin-form',
     ),

	
)); 
?>

	<div class="model-description">
		<p class="note">Поля с <span class="required">*</span> обязательны.</p>
	</div>
	

	<?php echo $form->errorSummary($model); ?>
	<button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button> 
 

 	<div class="row-top">  

		<div class="block">
	        <?php echo $form->label($model,'datetime'); ?>
	        <?php 
	        if($model->datetime != NULL){
	            $datetime = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $model->datetime);
	        } 
	        else{
	            $datetime = date('Y-m-d H:i:s');
	        }

	        $this->widget('zii.widgets.jui.CJuiDatePicker', 
						        array(
						            'name'		=> get_class($model).'[date]',
						            'options'	=>	array(
						                'showAnim'	=>'fold',
						                'dateFormat'=>'yy-mm-dd 00:00:00',
						            ),
						            'htmlOptions'	=>	array(
						                'style'	=> 'height:20px;' 
						            ),
						            'value' => $datetime
						        )
	                         ); ?>
	        <?php echo $form->error($model,'datetime'); ?>
	    </div>

		<div class="Message"></div>  
 
	 
        <div class="block">
            <?php /*echo $form->label($model ,'user_id'); ?>
            <?php echo $form->dropDownList($model ,'user_id', 
                                            CHtml::listData(User::model()->orderByOrderNum()->findAll(), 'id', 'transfer.name'),  
                                            array('class' => 'chzn-select', 'data-placeholder' => 'Новость', 'empty' => array(0 => 'Новость'))); ?>
            <?php echo $form->error($model ,'user_id');*/ ?>
        </div> 
 
	 
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

        <div class="tab-content">
        
			<div class="tab-pane in active">
    
                <div class="row-elements">
                    <?php echo $form->label($model,'content'); ?>
                    <?php echo $form->textArea($model,'content',array('rows'=>30, 'cols'=>80 , 'value' => $model->content)); ?>
                    <?php echo $form->error($model,'content'); ?>
                </div>
                
	        </div>
            
		</div>
        

	</div>

	<div class="clear"></div>
    <button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button>

<?php $this->endWidget(); ?>

</div><!-- form -->