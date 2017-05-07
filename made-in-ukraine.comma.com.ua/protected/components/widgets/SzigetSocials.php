<?php
class SzigetSocials extends CWidget {
	public $url;
	public $ajax = false;

	public function run() {

		if(!empty($this->url)){
			$this->url = 'http://' . $_SERVER['HTTP_HOST'] . $this->url;
		}
		//http://<?=$_SERVER['HTTP_HOST'].parse_url(Yii::app()->request->url, PHP_URL_PATH)
        $this->render('sziget_socials');
    } 
} 