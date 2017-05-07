<?php  
    $start = time();  

    $languageList = Language::model()->getLanguageList();


/*
$time = microtime(true) - $start;
printf('%.4F start.', $time);
echo '<br>';
*/
?>  
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'video-form',
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

        <div class="tab-content"> 

         		<div class="row-elements">
 

                    <div class="block">
                        <?php echo $form->label($model ,'type'); ?>
                        <?php echo $form->dropDownList($model ,'type', 
                                                        $model->getTypeList(),  
                                                        array('class' => '')); ?>
                        <?php echo $form->error($model ,'type'); ?> 
                    </div>
 
            
                    <div class="block admin-image">
                        <?php echo $form->label($model,'file_banner_static'); ?>
            
                        <?php if($this->getAction()->id == 'update' && !empty($model->file_banner_static) && file_exists($_SERVER['DOCUMENT_ROOT'].Banners::PATH_IMAGE.$model->file_banner_static) ):
                                   
                            echo CHtml::image(Banners::PATH_IMAGE.$model->file_banner_static, null,array('style' => 'max-width: 400px;') ).'<br />';  
                            
                            echo $form->checkBox($model, 'image_delete');
                            echo $form->labelEx($model,'image_delete', array('style'=>'display: inline-block;')).'<br />';
                            echo $form->error($model,'image_delete'); 
                        endif; ?>
            
                        <?php echo $form->fileField($model,'image'); ?>
            
                    </div>	 
            
                    <div class="block admin-image">
                        <?php echo $form->label($model,'file_banner'); ?>
            
                        <?php if($this->getAction()->id == 'update' && !empty($model->file_banner) && file_exists($_SERVER['DOCUMENT_ROOT'].Banners::PATH_BANNER.$model->file_banner) ):
                                   
                            echo CHtml::link($model->file_banner, Banners::PATH_BANNER.$model->file_banner).'<br />';  
                            
                            echo $form->checkBox($model, 'banner_delete');
                            echo $form->labelEx($model,'banner_delete', array('style'=>'display: inline-block;')).'<br />';
                            echo $form->error($model,'banner_delete'); 
                        endif; ?>
            
                        <?php echo $form->fileField($model,'banner'); ?>
            
                    </div>	 

                    <div class="block">
                        <?php echo $form->label($model, 'htmlcode'); ?>
                        <?php echo $form->textArea($model,'htmlcode', array('rows'=>10, 'cols'=>90,  'value' => $model->htmlcode )); ?>
                    </div>
             
            
            	</div>

         		<div class="row-elements">
 
                    <div class="block">
                        <?php echo $form->label($model,'value'); ?>
                        <?php echo $form->textField($model,'value'); ?>
                        <?php echo $form->error($model,'value'); ?>
                    </div>

                    <div class="block">
                        <?php echo $form->label($model,'name'); ?>
                        <?php echo $form->textField($model,'name'); ?>
                        <?php echo $form->error($model,'name'); ?>
                    </div>
          
                 
                    <div class="block">
                        <?php echo $form->label($model,'width'); ?>
                        <?php echo $form->textField($model,'width', array('max-length' => 5) ); ?>
                        <?php echo $form->error($model,'width'); ?>
                    </div>

                    <div class="block">
                        <?php echo $form->label($model,'height'); ?>
                        <?php echo $form->textField($model,'height', array('max-length' => 5) ); ?>
                        <?php echo $form->error($model,'height'); ?>
                    </div>


                 
                    <div class="block">
                        <?php echo $form->label($model,'target_url'); ?>
                        <?php echo $form->textField($model,'target_url'); ?>
                        <?php echo $form->error($model,'target_url'); ?>
                    </div> 
          
                    <div class="block">
                        <?php echo $form->label($model,'current_url'); ?>
                        <?php echo $form->textField($model,'current_url'); ?>
                        <?php echo $form->error($model,'current_url'); ?>
                    </div>

                    <div class="block">
                        <?php echo $form->label($model,'views_num'); ?>
                        <?php echo $model->views_num; ?> 
                    </div>

                    <div class="block">
                        <?php echo $form->label($model,'hits_num'); ?>
                        <?php echo $model->hits_num; ?> 
                    </div>




                    <div class="block">
                        <?php   echo $form->label($model ,'sections'); ?>
                        
                        <?php echo CHtml::dropDownList(get_class($model).'[sections][]',  
                                                        CHtml::listData(BannerHasSections::model()->withBanner($model->id)->findAll(), 'id', 'section_id'),                                            
                                                        CHtml::listData(Section::model()->orderByOrderNum()->published()->findAll(), 'id', 'transfer.name'), 
                                                        array('multiple'=>'multiple','class' => 'chzn-select nostyle', 'data-placeholder' => 'Выберите раздел', 'empty' => 'Выберите раздел') ); ?>           
                    </div>


					<div class="block">
						<?php echo $form->label($model,'url_counter_shows'); ?>
						<?php echo $form->textField($model,'url_counter_shows'); ?>
						<?php echo $form->error($model,'url_counter_shows'); ?>
					</div>
            	</div>

         		<div class="row-elements">

                    <div class="block">
                        <?php echo $form->label($model,'date_start'); ?>
                        <?php 
                        if($model->date_start != NULL){
                            $date_start = $model->date_start;
                            //$date_start = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $model->date_start);
                        } 
                        else{
                            $date_start = date('Y-m-d H:i:s');
                            //$date_start = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', date('Y-m-d H:i:s'));
                        }
 
            
                        $this->widget('zii.widgets.jui.CJuiDatePicker', 
                                            array(
                                                'name'      => get_class($model).'[date_start]',
                                                'options'   =>  array(
                                                    'showAnim'  =>'fold',
                                                    'dateFormat'=>'yy-mm-dd 00:00:00',
                                                ),
                                                'htmlOptions'   =>  array(
                                                    'style' => 'height:20px;' 
                                                ),
                                                'value' => $date_start
                                            )
                                         ); ?>
                        <?php // $form->error($model,'date_start'); ?>
                        <?php //echo $form->textField($model,'date_start',array('size'=>60, 'id' => 'date_start', 'class' => 'date')); ?>   
                    </div>

                    <div class="block">
                        <?php echo $form->label($model,'date_end'); ?>
                        <?php 
                        if($model->date_end != NULL){
                            $date_end = $model->date_end;
                            //$date_end = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $model->date_end);
                        } 
                        else{
                            //$date_end = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', date('Y-m-d H:i:s'));
                            $date_end = date('Y-m-d H:i:s');
                        }
            
                        $this->widget('zii.widgets.jui.CJuiDatePicker', 
                                            array(
                                                'name'      => get_class($model).'[date_end]',
                                                'options'   =>  array(
                                                    'showAnim'  =>'fold',
                                                    'dateFormat'=>'yy-mm-dd 00:00:00',
                                                ),
                                                'htmlOptions'   =>  array(
                                                    'style' => 'height:20px;' 
                                                ),
                                                'value' => $date_end
                                            )
                                         ); ?>
                        <?php // $form->error($model,'date_end'); ?>
                        <?php //echo $form->textField($model,'date_end',array('size'=>60, 'id' => 'date_end', 'class' => 'date')); ?>    
                    </div>

                    <div class="block">
                        <?php echo $form->label($model,'background_color'); ?>
                        <input type="text" id="color" name="<?=get_class($model);?>[background_color]" value="<?=$model->background_color?>" /><div class="picker"></div>
                        <?php echo $form->error($model,'background_color'); ?>
                    </div>

                     
                    <div class="block">
                        <?php echo $form->labelEx($model,'first'); ?>
                        <?php echo $form->checkBox($model,'first',array('class' => 'ibutton nostyle')); ?>
                        <?php echo $form->error($model,'first'); ?>
                    </div> 
 
                    <div class="block">
                        <?php echo $form->labelEx($model,'everywhere'); ?>
                        <?php echo $form->checkBox($model,'everywhere',array('class' => 'ibutton nostyle')); ?>
                        <?php echo $form->error($model,'everywhere'); ?>
                    </div>  
                     
                    <div class="block">
                        <?php echo $form->labelEx($model,'active'); ?>
                        <?php echo $form->checkBox($model,'active',array('class' => 'ibutton nostyle')); ?>
                        <?php echo $form->error($model,'active'); ?>
                    </div>  

        		</div> 
        
	</div>	 
  

	<div class="clear"></div>
    <button class="btn btn-primary"><?php echo ($model->isNewRecord ? 'Добавить' : 'Сохранить') ?></button>

<?php $this->endWidget(); ?>

</div><!-- form -->