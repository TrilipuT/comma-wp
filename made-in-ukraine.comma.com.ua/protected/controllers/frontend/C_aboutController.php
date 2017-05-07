<?php

class C_aboutController extends FrontEndController {

	public function urlRules(){
		return array(
            array('c_about/index',
				'pattern'=>'{this}'//\.html
			), 
		);
	} 

	public function actionIndex(){ 
		
		$this->activeSection = $this->Section; 

		$authorsItems = Authors::model()->published()->orderByOrderNum()->only(3)->findAll();

		$this->render('index', array('Section' 		=> $this->Section,
									 'authorsItems' => $authorsItems));
	}
}