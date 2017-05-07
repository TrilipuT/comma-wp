<?php   
class Users extends BaseModel{ 
		
	const 	PATH_IMAGE 	= '/graphics/users/';
	
	public 	$image_delete = 0,
			$image;

	public  $photoFromUrl = '';

 	public 	$confirm_password,
			$password_new;

	//public static $SectionUrl; 

	public static function model($className=__CLASS__){

		return parent::model($className);
	}  

	public function tableName()	{

		return parent::tablePrefix().'users';
	}

	protected function beforeSave(){

		parent::beforeSave(); 

		if($this->image_delete == 1 && $this->file_photo != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->file_photo);
  			$this->file_photo = '';
  		} 

		if($this->isNewRecord){

			$result = $this->find('mail = :mail', array(':mail' => $this->mail));

			if($result){
				return false;
			} 
		}

		if($this->password_new != '' && $this->password_new == $this->confirm_password){
			$this->password = md5($this->password_new);
		} 
        return true;
    }

	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array( 
			array('confirm_password, password_new, image_delete, image, photoFromUrl', 'default'),
			array('id, parent_id, change_password, password, name, nick, file_photo, mail, soc_id, access_token, provider, town, sfere, birthday, added_time, edited_time, order_num, active, is_subscribed', 'safe' ),
		);
	}  
 	
 	//----------------------------------------------------------------------------------------
  	/*
  	public function getSectionUrl(){ 

  		if(self::$SectionUrl == ''){
  			self::$SectionUrl = '/'.Yii::app()->language.'/'.Base::findControllerAlias('C_authors'); 
  		}
  		return self::$SectionUrl;
  	}*/

    public function getItemUrl(){   

    	if(!$this->provider || !$this->nick){
    		return false;
    	}

    	$link = "";
    	switch ($this->provider) {
    		case 'facebook':
    			$link = "https://www.facebook.com/";
    			break;

			case 'vkontakte':
    			$link = "https://vk.com/";
    			break;
    		
    		default:
    			return false;
    			break;
    	}

        return $link.$this->nick; //.'.html'
    }

  	//----------------------------------------------------------------------------------------

	protected function afterSave(){ 

  		parent::afterSave();  
  		
  		//удаление маркера новой записи 
  		if($this->isNewRecord)
  			unset($this->isNewRecord); 
 
  		if($_FILES[__CLASS__] != NULL){

  	  		$doc = CUploadedFile::getInstance($this,'image');
			if($doc){
				//$type 	= $doc->getType();
				//$doc->getSize();
				
				Yii::import('application.components.Image');

				$tmp_image = $doc->getTempName();  

				try {

					$Image 				  = new Image(true);  				 
					$this->file_photo 	  = basename($Image->load($tmp_image)->crop(array(50,50))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE.$this->id)); 
				 	$this->file_photo 	  = $this->createMask(self::PATH_IMAGE, $this->file_photo); 
			 	} catch (CException $e) {
	  	  			//echo $e->getMessage();
	  	  			//exit;
	  	  		} 
			 	
			 	unset($_FILES[__CLASS__]);
				$this->update(array('file_photo'));  
	 		}

  	  	} else if($this->photoFromUrl != ''){


   
  	  		Yii::import('application.components.Image');

  	  		try {
  	  			$Image = new Image(true);  				 
	  	  		$Image->loadFromURL($this->photoFromUrl);  

	  	  		$this->file_photo = basename($Image->crop(array(50,50))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE.$this->id)); 
			 	$this->file_photo = $this->createMask(self::PATH_IMAGE, $this->file_photo);  
			 	$this->photoFromUrl = '';
			 	$this->update(array('file_photo')); 
  	  		} catch (CException $e) {
  	  			//echo $e->getMessage();
  	  			//exit;
  	  		} 
  	  	}	  
  	 	


		return true;
  	}

  	private function createMask($path,$filename){

  		$file  		= $_SERVER['DOCUMENT_ROOT'].$path.$filename;
		$marker_path= $_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE.'50x50.png'; 
 

		if($file){

			$imgInfo = getimagesize($file);	        
		    switch($imgInfo[2]) {
		        case 2: //JPG
		            $img = imagecreatefromjpeg($file);
		            break;
		        case 3: //PNG
		            $img = imagecreatefrompng($file);
		            break; 
		    }   
		         
			$mask = imagecreatefrompng($marker_path); 

			Yii::import('application.components.Image');
			Image::image_mask($img, $mask);  

			$this->fileDelete($path.$filename); 

			imagepng($img, $_SERVER['DOCUMENT_ROOT'].$path.$this->id.'.png');  					
			return $this->id.'.png';  

		} // end fileexist
  	}
 
	protected function afterDelete(){

  		parent::afterDelete();   

  		if($this->file_photo != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->file_photo);
  			$this->file_photo = '';
  		}  

  		$blogItems = Blogs::model()->withUser($this->id)->findall();
  		if($blogItems){
			foreach ($blogItems as $Blogs) {
				$Blogs->delete();	  
			}
  		} 

  		Comment::model()->deleteAll('user_id = :user_id',array(':user_id' => $this->id)); 

        return true;
  	}

  	/*SELECT TRIM( SUBSTRING_INDEX(  `name` ,  ' ', -1 ) ) AS a, name
	FROM  `sf_users` 
	ORDER a
	
  	public static function getLetters(){ 

  		$sql 		= 'SELECT DISTINCT LEFT(`name`,1) AS `letter` 
  						FROM '.parent::tablePrefix().'users 
  						WHERE `author` = 1 AND `active` = 1
  						ORDER BY `name`';
		$letters 	= Yii::app()->db->createCommand($sql)->queryAll();

		return $letters;
  	}*/

  	 
} 