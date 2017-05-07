<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	//'Login',
);
?> 
<!-- <h1>Login</h1> --> 

<div class="loginContainer">
    
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'loginForm',
		'action' => 'login',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
			'class' => 'form-horizontal'
		),
	)); ?> 

        <div class="form-row row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span12" for="username">
                        Username:
                        <span class="icon16 icomoon-icon-user-3 right gray marginR10"></span>
                    </label>
                    <?php echo $form->textField($model,'username', array('id' => 'username', 'class' => 'span12')); ?>
					<?php echo $form->error($model,'username'); ?> 
                </div>
            </div>
        </div>

        <div class="form-row row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span12" for="password">
                        Password:
                        <span class="icon16 icomoon-icon-locked right gray marginR10"></span>
                        <span class="forgot"><a href="#">Forgot your password?</a></span>
                    </label>
                    <?php echo $form->passwordField($model,'password', array('id' => 'password', 'class' => 'span12', 'autocomplete' => 'off')); ?>
					<?php echo $form->error($model,'password'); ?>  
                </div>
            </div>
        </div>
        <div class="form-row row-fluid">                       
            <div class="span12">
                <div class="row-fluid">
                    <div class="form-actions">
                    <div class="span12 controls"> 
                        <?php echo $form->checkBox($model,'rememberMe', array('id' => 'keepLoged', 'class' => 'styled')); ?>
						<?php echo $form->error($model,'rememberMe'); ?> Keep me logged in
                        <button type="submit" class="btn btn-info left" id="loginBtn">
                        	<span class="icon16 icomoon-icon-enter white"></span> Login
                        </button>
                    </div>
                    </div>
                </div>
            </div> 
        </div>
	<?php $this->endWidget(); ?>
</div>
