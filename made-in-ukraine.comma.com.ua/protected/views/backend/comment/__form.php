  
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
            <?php echo $form->label($model ,'news_id'); ?>
            <?php echo $form->dropDownList($model ,'news_id', 
                                            CHtml::listData(News::model()->orderByOrderNum()->findAll(), 'id', 'transfer.name'),  
                                            array('class' => 'chzn-select nostyle', 'data-placeholder' => 'Новость', 'empty' => array(0 => 'Новость'))); ?>
            <?php echo $form->error($model ,'news_id'); ?>
        </div> 
	 
        <div class="block">
            <?php echo $form->label($model ,'event_id'); ?>
            <?php echo $form->dropDownList($model ,'event_id', 
                                            CHtml::listData(Event::model()->orderByOrderNum()->findAll(), 'id', 'transfer.name'),  
                                            array('class' => 'chzn-select nostyle', 'data-placeholder' => 'Событие', 'empty' => array(0 => 'Событие'))); ?>
            <?php echo $form->error($model ,'event_id'); ?>
        </div> 
	 
 
	 
        <div class="block">
            <?php echo $form->label($model ,'video_id'); ?>
            <?php echo $form->dropDownList($model ,'video_id', 
                                            CHtml::listData(Video::model()->orderByOrderNum()->findAll(), 'id', 'transfer.name'),  
                                            array('class' => 'chzn-select nostyle', 'data-placeholder' => 'Видео', 'empty' => array(0 => 'Видео'))); ?>
            <?php echo $form->error($model ,'video_id'); ?>
        </div> 
	 
	 
        <div class="block">
            <?php echo $form->label($model ,'program_id'); ?>
            <?php echo $form->dropDownList($model ,'program_id', 
                                            CHtml::listData(Program::model()->orderByOrderNum()->findAll(), 'id', 'transfer.name'),  
                                            array('class' => 'chzn-select nostyle', 'data-placeholder' => 'Программа', 'empty' => array(0 => 'Программа'))); ?>
            <?php echo $form->error($model ,'program_id'); ?>
        </div> 
	 
        <div class="block">
            <?php echo $form->label($model ,'expert_id'); ?>
            <?php echo $form->dropDownList($model ,'expert_id', 
                                            CHtml::listData(Expert::model()->orderByOrderNum()->findAll(), 'id', 'transfer.name'),  
                                            array('class' => 'chzn-select nostyle', 'data-placeholder' => 'Эксперт', 'empty' => array(0 => 'Эксперт'))); ?>
            <?php echo $form->error($model ,'expert_id'); ?>
        </div> 
	 
        <div class="block">
            <?php echo $form->label($model ,'comment_id'); ?>
            <?php echo $form->dropDownList($model ,'comment_id', 
                                            CHtml::listData(Comment::model()->orderByOrderNum()->findAll(), 'id', 'content'),  
                                            array('class' => 'chzn-select nostyle', 'data-placeholder' => 'Родительский комментарий', 'empty' => array(0 => 'Родительский комментарий'))); ?>
            <?php echo $form->error($model ,'comment_id'); ?>
        </div> 
	 
        <div class="block">
            <?php /*echo $form->label($model ,'user_id'); ?>
            <?php echo $form->dropDownList($model ,'user_id', 
                                            CHtml::listData(User::model()->orderByOrderNum()->findAll(), 'id', 'transfer.name'),  
                                            array('class' => 'chzn-select', 'data-placeholder' => 'Новость', 'empty' => array(0 => 'Новость'))); ?>
            <?php echo $form->error($model ,'user_id');*/ ?>
        </div> 
	 
		<div class="block">
			<?php echo $form->label($model,'votes_pro'); ?>
			<?php echo $form->textField($model,'votes_pro'); ?>
			<?php echo $form->error($model,'votes_pro'); ?>
		</div>
	 
		<div class="block">
			<?php echo $form->label($model,'votes_con'); ?>
			<?php echo $form->textField($model,'votes_con'); ?>
			<?php echo $form->error($model,'votes_con'); ?>
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