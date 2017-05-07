<!DOCTYPE html>
<html lang="<?=Yii::app()->language?>">
<head>
	<meta charset="utf-8" />
	<title><?=CHtml::encode($this->pageTitle) ?></title>
	<link rel="stylesheet" href="<?=Yii::app()->theme->baseUrl?>/css/style<?=(Yii::app()->params['css_version'] ? '.v.'.Yii::app()->params['css_version'] : '')?>.css"/>
</head>
<body>
<section class="main">
	<?=$content ?>
</section>
<footer class="foot_error">
	<div class="wrapper">
		<div class="footer_top">
			<div class="footer_logo"><a href="/"></a></div>
		</div>
	</div>
</footer>
</body>
</html>