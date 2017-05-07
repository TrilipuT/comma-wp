<?php
class SzigetFooterMenu extends CWidget {

	public function run(){
        $sectionItems = Section::model()->published()->orderByOrderNum()->mainMenu()->onlyDomain(2)->findAll();
        $this->render('szigetFooterMenu', array('sectionItems' => $sectionItems));
    }
} 