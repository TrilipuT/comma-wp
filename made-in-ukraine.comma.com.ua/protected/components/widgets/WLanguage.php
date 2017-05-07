<?php
class WLanguage extends CWidget {
 

	public function run() {

 
        $activeLanguage= Language::model()->getActiveLanguage();
        $languageItems = CHtml::listData(Language::model()->orderByOrderNum()->published()->findAll(), 'code_name', 'code');                    

        $this->render('language', array('languageItems' => $languageItems,
        								'activeLanguage'=> $activeLanguage));
    }
}  