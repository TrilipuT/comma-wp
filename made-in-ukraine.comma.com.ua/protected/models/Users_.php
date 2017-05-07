<?php   
class Users extends BaseModel{ 
 	
 	const 	PATH_IMAGE 	= '/graphics/users/';
	
	public 	$image_delete = 0,
			$image;

	public static function model($className=__CLASS__){

		return parent::model($className);
	}  

	public function tableName()	{

		return $this->tablePrefix().'users';
	}

	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array( 
			array('image_delete, image', 'default'),
			array('id, parent_id, change_password, password, name, nick, image_filename, mail, soc_id, access_token, provider, town, sfere, birthday, image_filename, added_time, edited_time, order_num, active, is_subscribed', 'safe' ),
		);
	}  

	protected function beforeSave(){

		parent::beforeSave(); 

		if($this->image_delete == 1 && $this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->image_filename = '';
  		} 

		 
        return true;
    }

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

				$Image 					= new Image();  
				 
				$this->image_filename 	= basename($Image->load($tmp_image)->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE.$this->id));  
				 
			 	unset($_FILES[__CLASS__]);
				$this->update(array('image_filename'));  
	 		}
  	  	}	  
  	  	 

		return true;
  	}
 
	protected function afterDelete(){

  		parent::afterDelete();   

  		if($this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->image_filename = '';
  		}  
  		 
        return true;
  	}
} 