<?php
class UkraineAboutPopup extends CWidget {

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
	
		$Rubrics = Rubrics::model()->published()->withCodeName("made_in_ukraine")->findAll();
		$Rubric = $Rubrics[1];
		$criteria = new CDbCriteria();
		$criteria->addInCondition("code_name", array("marina_sidorenko","irina_matviuk"));
		$authors = Authors::model()->findAll($criteria);
		$this->render('ukraineAboutPopup', array("authors" => $authors, "title" => $Rubric->transfer->description, "text" => $Rubric->transfer->content));
	}
} 