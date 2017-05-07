<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	'Добавить',
);
?>

<h2>Добавить</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model  )); ?>