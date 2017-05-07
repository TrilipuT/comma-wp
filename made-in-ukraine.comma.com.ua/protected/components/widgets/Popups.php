<?php
class Popups extends CWidget {

    public $code_name;

	public function run(){


		$session = new CHttpSession;
		$session->open();
		$UsersAuth = $session['UsersAuth']; 
		$session['UsersAuth'] = 0;

        $this->render('popups', array('UsersAuth' => $UsersAuth));
    }
} 