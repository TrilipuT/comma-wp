<?php
class Banner extends CWidget { 

	public function run(){	
		
		$Section = $this->Getcontroller()->Section;  
        
        $Banners = Banners::getBannerByType(2, 0, $Section->id);   

		$this->render('banner', array('Banners' => $Banners));
	}
} 