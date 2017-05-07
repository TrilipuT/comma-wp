<?php

class C_juryController extends FrontEndController {

	public function urlRules(){
		return array(
            array('c_jury/index',
				'pattern'=>'{this}'//\.html
			), 
		);
	} 

	public function actionIndex(){
		$juryItems = Jury::model()->published()->orderByOrderNum()->findAll('image_filename != ""');
		$this->render('index', array('juryItems' => $juryItems));
	}
}