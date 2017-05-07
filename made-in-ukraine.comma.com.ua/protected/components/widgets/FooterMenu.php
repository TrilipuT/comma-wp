<?php
class FooterMenu extends CWidget {

	public function run(){
       
        $sectionItems = Section::model()->published()->orderByOrderNum()->mainMenu()->onlyDomain()->findAll();
        $this->render('footerMenu', array('sectionItems' => $sectionItems));

    }
} 