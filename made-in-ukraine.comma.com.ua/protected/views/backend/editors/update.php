<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	'Редактировать',
);
?>

<h2>Редактировать</h2>

<?php echo $this->renderPartial('_form', array( 'model'=>$model )); ?>