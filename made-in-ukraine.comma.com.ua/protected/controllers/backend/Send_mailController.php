<?php

class Send_mailController extends BackEndController {

	public function init(){ 
 		
 		$this->activeModule = 'send_mail';
		$this->moduleName 	= 'Рассылка'; 

		return true;
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex() {
		

		$this->render('index');
	}

	 
}
