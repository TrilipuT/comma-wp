<?php

/**
 * This is the model class for table "sf_photos".
 *
 * The followings are the available columns in table 'sf_photos':
 * @property integer $id
 * @property integer $gallery_id
 * @property integer $order_num
 * @property string $changefreq
 * @property string $priority
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $added_time
 * @property string $edited_time
 * @property integer $active
 */
class Photos extends BaseModel{

	const 	PATH_IMAGE 			= '/graphics/photos/image/';
	const 	PATH_IMAGE_MEDIUM	= '/graphics/photos/article/';
	const 	PATH_IMAGE_SMALL 	= '/graphics/photos/small/';
	
	public 	$image_delete = 0,
			$image; 

	/**
	 * @return string the associated database table name
	 */
	public function tableName()	{
		return parent::tablePrefix().'photos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gallery_id, order_num, created_by, modified_by, active', 'numerical', 'integerOnly'=>true),
			array('changefreq', 'length', 'max'=>10),
			//array('priority', 'length', 'max'=>1),
			array('image_delete, image', 'default'),
			array('id, size, type, gallery_id, image_filename, order_num, changefreq, priority, created_by, modified_by, added_time, edited_time, active', 'safe'),
		);
	} 

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'gallery_id' => 'Gallery',
			'order_num' => 'порядковый номер',
			'changefreq' => 'sitemap',
			'priority' => 'sitemap',
			'created_by' => 'кто создал',
			'modified_by' => 'кто последний редактировал',
			'added_time' => 'Added Time',
			'edited_time' => 'Edited Time',
			'active' => 'Active',
		);
	} 
	 

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Photos the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected function beforeSave(){

		parent::beforeSave(); 

		if($this->image_delete == 1 && $this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_MEDIUM.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SMALL.$this->image_filename);
  			$this->image_filename = '';
  		} 

  		return true;
	}


	protected function afterSave(){ 

  		parent::afterSave();  
  		
  		//удаление маркера новой записи 
  		if($this->isNewRecord)
  			unset($this->isNewRecord); 
 		  

  		if($_FILES['Filedata']['tmp_name'] != NULL){ 
  			 
			
			Yii::import('application.components.Image');

			$tmp_image				= $_FILES['Filedata']['tmp_name'];  

			$Image 					= new Image();   

			$Image->load($tmp_image)->scale(array(300,300))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE_SMALL.$this->id);
			$Image->load($tmp_image)->scale(array(650,350))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE_MEDIUM.$this->id);

			$this->image_filename 	= basename($Image->load($tmp_image)
														->scale(array('w',1040))
														->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE.$this->id));  
			 
		 	unset($_FILES['Filedata']['tmp_name']);
			$this->update(array('image_filename'));  
	 		 
  	  	}	  
  	  	 

		return true;
  	}
 
	protected function afterDelete(){

  		parent::afterDelete();   

  		if($this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_MEDIUM.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SMALL.$this->image_filename);
  			$this->image_filename = '';
  		}  
  		 
        return true;
  	}


  	public function withGallery($gallery_id){ 

		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.gallery_id = :gallery_id',
			'params'	=> array(':gallery_id' => $gallery_id)
		));
		return $this;
	}


}
