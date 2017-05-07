   
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'comment-form',
    'enableAjaxValidation'=>false, 
    
    'htmlOptions'=>array(
          'class' => 'admin-form',
     ),
)); 
?>  
    <button class="btn btn-primary"><?php echo 'Ответить' ?></button>
    
    <p class="note"><?=$error;?></p>

    <div class="row-top">  
        <div class="block"> 
            <p>Комментарий</p>
            <p><?=$Comment->content;?></p>
        </div> 

        <div class="clear"></div>
        <br>
        <p><b>Отвечает</b></p>
        <div class="block"> 
            <p>Експерт</p>
            <?php echo CHtml::dropDownList('expert_id', 
                                            array(),
                                            CHtml::listData(Expert::model()->orderByOrderNum()->findAll('is_answer = 1'), 'id', 'transfer.name'),  
                                            array('class' => 'chzn-select nostyle', 'data-placeholder' => 'Эксперт', 'empty' => array(0 => 'Эксперт'))); ?> 
        </div> 
        <div class="clear"></div>

        <div class="block"> 
            <p>Ответ</p>
            <?php echo CHtml::textArea('content', NULL,array('style' => 'width: 600px;margin-left:0;padding-top:0;height:230px;min-height:230px;')); ?> 
        </div> 
    </div>  


    <div class="clear"></div>
    <button class="btn btn-primary"><?php echo 'Ответить' ?></button>

<?php $this->endWidget(); ?>

</div><!-- form -->