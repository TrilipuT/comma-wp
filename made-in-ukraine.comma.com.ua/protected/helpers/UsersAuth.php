<?php   
/*
UsersAuth::isLogin()
*/
class UsersAuth {

	private $serviceName;
	private $eauth_attributes;
	private $userId; 
	private $lk = false;
	private $mainRecord; 

	public function __construct($serviceName, $login = '', $passworg = '', $lk = ''){
		
		$this->serviceName = $serviceName;

		if($lk != ''){
			$this->lk = true;
			$this->mainRecord = UsersAuth::getMainRecord();
		}	


		switch ($this->serviceName) {
			case 'site':
				$Users = $this->checkSiteUser($login, $passworg);
				break; 
			default:
				$session = new CHttpSession;
				$session->open();
				$this->eauth_attributes = $session['eauth_attributes'];

				$Users = $this->checkSocUser();
				break;
		}	 	
		
		if($Users){ 
			
			if(!$this->lk){
				$this->userId = $Users->id;
				$this->login();
			}

			return true;
		} else {
			return false;
		}  
	}

	public static function logOut(){

		$cookie = new CHttpCookie('user_login', 0);
		$cookie->expire = time()-60*60*24; 
		Yii::app()->request->cookies['user_login'] = $cookie;
	}

	public static function isLogin(){

		$userId = (int)Yii::app()->request->cookies['user_login']->value;
  
		$Users = Users::model()->published()->findByPk($userId);
		if($Users && $Users->active == 1){
			return $Users;
		}

		return false;
	}

	private function login(){

		$cookie = new CHttpCookie('user_login', $this->userId);
		$cookie->expire = time()+60*60*24; 
		Yii::app()->request->cookies['user_login'] = $cookie;

		$session = new CHttpSession;
		$session->open();
		$session['UsersAuth'] = 1;
	}

	public function getAttributes($attribut){

		if($this->serviceName != 'site'){
			return $this->eauth_attributes[$attribut];
		} else {

		}

		return NULL;
	}

	public function getUserId(){
		if($this->userId > 0){
			return $this->userId;
		} else {
			return 0;
		}
	}


	public function checkSocUser(){  

		$Users = Users::model()->find('soc_id = :soc_id AND provider = :provider', 
									  array(':soc_id'   => $this->getAttributes('id'),
									  		':provider' => $this->serviceName)); 

		if(!$Users){    
			
			$Users              = new Users();    
	        $Users->name        = $this->getAttributes('name');
	        $Users->nick        = $this->getAttributes('nick');
	        $Users->soc_id      = $this->getAttributes('id');
	        $Users->provider    = $this->serviceName;
	       
	        //$Users->file_photo  = $this->getAttributes('file_photo'); 
 
	        $Users->town  		= $this->getAttributes('town');
	        $Users->birthday  	= $this->getAttributes('birthday');

	        $Users->active      = 1; 
	        $Users->save(); 
		} 


		if($this->lk && $this->mainRecord){ 
			$Users->parent_id = $this->mainRecord->id;
			$Users->update(array('parent_id')); 
		}

		$Users->photoFromUrl= $this->getAttributes('file_photo');
		$Users->save(); 

		
		return $Users; 
	}

	public function checkSiteUser($email, $password){

		$Users = Users::model()->find('mail = :mail 
										AND password = :password 
										AND active = 1', 

									  array(':mail'     => $email,
									  		':password' => md5($password)));  

		return $Users;
	}

	public static function getMainRecord(){

		$Users = UsersAuth::isLogin(); 
		if($Users){
			if($Users->parent_id != 0){

				$_Users = Users::model()->published()->findByPk($Users->parent_id, '`provider` = "site"');  
				if($_Users){
					return $_Users; 
				} else {
					return false;
				} 
			} else { 
				if($Users->provider != 'site'){
					return false;
				} else {
					return $Users; 
				}
			}   
		} else {
			return false;
		}
	}

} 