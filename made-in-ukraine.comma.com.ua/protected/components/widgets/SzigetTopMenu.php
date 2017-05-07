<?php
class SzigetTopMenu extends CWidget {

	public function run(){
       
        $sectionItems = Section::model()->published()->orderByOrderNum()->topMenu()->onlyDomain(2)->findAll();
        $this->render('szigetTopMenu', array('sectionItems' => $sectionItems));

    }
} 