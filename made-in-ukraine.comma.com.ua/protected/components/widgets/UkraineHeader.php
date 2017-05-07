<?php
class UkraineHeader extends CWidget {

	public $activeSection;
	public $activeSubRubricsId;
	public $icon_class;
	public $activeRubric;

	public function run(){

		if(Yii::app()->language != 'ru')
			$language = '/'.Yii::app()->language;
		else
			$language = '';

		$this->icon_class = Yii::app()->controller->icon_class;
	
		$Rubrics = Rubrics::model()->published()->withCodeName("made_in_ukraine")->find();
		$subRubrics = Rubrics::model()->published()->orderByOrderNum()->find('parent_id = :parent_id', 
																						array(':parent_id' => $Rubrics->id)); 
		//$result = Article::getItems(array($subRubrics->id), 0, $page, 100); 

		$articleItems = Article::model()->published()->orderByOrderNum()->findAll("rubric_id = :rubric_id", array(":rubric_id" => $subRubrics->id));
		$this->render('ukraine_header', array("articles" => $articleItems));
	}
} 