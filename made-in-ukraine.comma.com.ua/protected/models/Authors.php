<?php

/**
 * This is the model class for table "comma_authors".
 *
 * The followings are the available columns in table 'comma_authors':
 * @property integer $id
 * @property string $code_name
 * @property string $image_filename
 * @property integer $order_num
 * @property string $changefreq
 * @property string $priority
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $blocked
 * @property string $added_time
 * @property string $edited_time
 * @property integer $active
 */  
 class Authors extends BaseModel {
	
	public static $SectionUrl;

	const 	PATH_IMAGE_SRC  = '/graphics/authors/src/',
		 	PATH_IMAGE 		= '/graphics/authors/',
		 	PATH_IMAGE_SMALL= '/graphics/authors/small/',
		 	PATH_IMAGE_MINI = '/graphics/authors/mini/';  

	public 	$image_delete = 0,
			$image;

	public $transfer_type = true;

	public function thumbnailsRules(){  
    	return array('image' => array('150x150' => array('method'=>'crop','canPinch'=>true, 'path' => self::PATH_IMAGE),
    								  '100x100' => array('method'=>'crop','canPinch'=>true, 'path' => self::PATH_IMAGE_SMALL),
    								  '70x70' => array('method'=>'crop','canPinch'=>true, 'path' => self::PATH_IMAGE_MINI) )  );	
    }

	/**
	 * @return string the associated database table name
	 */  
	public function tableName(){
		return parent::tablePrefix().'authors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code_name', 'required'),
			array('order_num, created_by, modified_by, blocked, active', 'numerical', 'integerOnly'=>true),
			array('code_name', 'length', 'max'=>255),
			array('image_filename', 'length', 'max'=>50),
			array('changefreq', 'length', 'max'=>10),
			array('priority', 'length', 'max'=>3),
			array('added_time, edited_time', 'safe'),
			//array('image_delete, image, SectionUrl', 'default'), 
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, photographer, code_name, image_filename, order_num, changefreq, priority, created_by, modified_by, blocked, added_time, edited_time, active', 'safe'), //, 'on'=>'search'
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'transfer' => array(self::HAS_ONE, 
										__CLASS__.'Transfer', 
										'parent_id',
										'condition' => 'transfer.language_id = :lang_id',
										'params' 	=> array(':lang_id' => Language::getActiveLanguageId())) 
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'code_name' => 'Code Name',
			'image_filename' => 'фото',
			'order_num' => 'порядковый номер',
			'changefreq' => 'changefreq (sitemap)',
			'priority' => 'priority (sitemap)',
			'created_by' => 'кто создал',
			'modified_by' => 'кто последний редактировал',
			'blocked' => 'Blocked',
			'added_time' => 'Added Time',
			'edited_time' => 'Edited Time',
			'active' => 'Active',
			'photographer' => 'Фотограф'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */

	 /*
	public function search(){
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('code_name',$this->code_name,true);
		$criteria->compare('image_filename',$this->image_filename,true);
		$criteria->compare('order_num',$this->order_num);
		$criteria->compare('changefreq',$this->changefreq,true);
		$criteria->compare('priority',$this->priority,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('blocked',$this->blocked);
		$criteria->compare('added_time',$this->added_time,true);
		$criteria->compare('edited_time',$this->edited_time,true);
		$criteria->compare('active',$this->active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	} */

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Authors the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

 	//---------------------------------------------------------------------------------------- 
  	public static function getSectionUrl(){

  		if(self::$SectionUrl == ''){

  			if(Yii::app()->language != 'ru')
  				self::$SectionUrl = '/'.Yii::app()->language;

  			self::$SectionUrl .= '/'.Base::findControllerAlias('C_authors');  
  		}
  		return self::$SectionUrl;
  	}

    public function getItemUrl(){  

    	

    	if($this->photographer == 1){
    		 $section = Gallery::getSectionUrl();
    	}  else {
    		$section = self::getSectionUrl();
    	}
    	
        return $section.'/'.$this->code_name; //.'.html'
    } 

    public function getPhotografUrl(){  

        return Gallery::getSectionUrl().'/'.$this->code_name; //.'.html'
    } 

    public function getAuthorUrl() {
    	return self::getSectionUrl(). "/". $this->code_name;
    }

  	//----------------------------------------------------------------------------------------

	protected function beforeSave(){

		parent::beforeSave(); 

		if($this->image_delete == 1 && $this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SMALL.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_MINI.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename);
  			$this->image_filename = '';
  		} 

  		return true;
	}


	protected function afterSave(){ 

  		parent::afterSave();  
  		
  		//удаление маркера новой записи 
  		if($this->isNewRecord)
  			unset($this->isNewRecord); 
 
  			if($_FILES[__CLASS__]['tmp_name']['image'] != NULL){

	  			if($this->image_filename != ''){ 
		  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
		  			$this->fileDelete(self::PATH_IMAGE_SMALL.$this->image_filename);
		  			$this->fileDelete(self::PATH_IMAGE_MINI.$this->image_filename);
		  			$this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename);
		  		}  

	  	  		$doc = CUploadedFile::getInstance($this,'image');
				if($doc){
					//$type = $doc->getType();
					//$doc->getSize();
					
					Yii::import('application.components.Image');

					$tmp_image = $doc->getTempName();  

					$Image = new Image(true);  
					$Image->load($tmp_image);   

					$width = 150; 
					if ($Image->getWidth() < $width){
						$this->addError('thumbnail', 'Картинка слишком маленькая. Загрузите, пожалуйста, картинку большего разрешения');
						return false;
					}

					$Image->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE_SRC.$this->id);	 
					$Image->crop(array(150,150))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE.$this->id); 
					$Image->crop(array(100,100))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE_SMALL.$this->id); 
					$this->image_filename = basename($Image->crop(array(70,70))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE_MINI.$this->id));   

					$this->addUploadedFile($this->image_filename);   

					Image::createMask(self::PATH_IMAGE,$this->id,'author150x150');
					Image::createMask(self::PATH_IMAGE_SMALL,$this->id,'author100x100');
					Image::createMask(self::PATH_IMAGE_MINI,$this->id,'author70x70');
					 
				 	$_FILES[__CLASS__]['tmp_name']['image'] = NULL;
					$this->update(array('image_filename'));  
		 		}
	  	  	}	   
	  	  	//echo '<pre>'; var_dump($_POST['Thumbnail']); echo '</pre>'; 
		  	//--------------------------------------------------------------------------------------------------------------------------------------
		  	if (isset($_POST['Thumbnail']) && $this->image_filename != NULL){ 

		  		foreach ($_POST['Thumbnail'] as $type => $item) {
		  			
		  			if(count($item) > 0){
		  				foreach ($item as $resolution => $rules){
					  
							//if ($_POST['Thumbnail'][$resolution]['delete'] == 1)
								//$this->deleteThumbnail($resolution);
								
							// Ищем исходник иконки
							if($type == 'image'){
								$src = self::PATH_IMAGE_SRC.$this->image_filename; 
								$img = $this->image_filename;
							}  
							
							
							if ($img != null && file_exists($_SERVER['DOCUMENT_ROOT'].$src) ){

								Yii::import('application.components.Image');
								$Image = new Image(true);
								$Image->load($_SERVER['DOCUMENT_ROOT'].$src);
								 
								// Ищем область выделения
								$selection = $_POST['Thumbnail'][$type][$resolution]['selection'];

								if($selection == '0;0;805;537')
									$selection = '';
								else if (empty($selection) && isset($rules['selection']))
									$selection = $_POST['Thumbnail'][$rules['selection']]['selection'];
								 

							   //var_dump($selection);
								// Область есть, значит пережимали или иконку или ее родителя
								if (!empty($selection)){

									$selection = explode(';', $selection);
									if (count($selection) != 4)
										$selection = null;
									
									$Image->select($selection);
								} else {
									continue;
								}

								// Определяем конечный размер иконки
								$size = array();
								list($width, $height) = explode('x', $resolution);
								if (empty($height))
									$size = array('w',intval($width));
								else if (empty($width))
									$size = array('h',intval($height));
								else
									$size = array(intval($width),intval($height));
								

								//var_dump($size, $this->thumbnailsRules[$resolution]['method'], $this->thumbnailsRules[$resolution]['path']); echo '<br>';

								$thumbnailsRules = $this->thumbnailsRules();

								// Определяем метод сжатия
								if ($thumbnailsRules[$type][$resolution]['method'] == 'scale')
									$Image->scale($size);
								else
									$Image->crop($size);
								
								// Сохранение происходит только если
								// есть исходник и
								// есть область выделения


								$Image->save($_SERVER['DOCUMENT_ROOT'].$thumbnailsRules[$type][$resolution]['path'].$this->id);  
								Image::createMask($thumbnailsRules[$type][$resolution]['path'],$this->id,'author'.$resolution);  
							}// end if image isset  
						} // end foreach  
		  			}
		  		}  // end foreach 
			}  
  	  	 

		return true;
  	} 

  	protected function beforeDelete(){

  		parent::beforeDelete();   

  		ArticleHasAuthors::model()->deleteAll('authors_id = :authors_id',array(':authors_id' => $this->id));
  		GalleryHasAuthors::model()->deleteAll('authors_id = :authors_id',array(':authors_id' => $this->id)); 
  		 
        return true;
  	}
 
	protected function afterDelete(){

  		parent::afterDelete();   

  		if($this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SMALL.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_MINI.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename);
  			$this->image_filename = '';
  		}  
  		 
        return true;
  	}

	public static function getList(){

		$items = Authors::model()->published()->findAll();
		$result= array();

		if($items){
			foreach ($items as $key => $model) { 
				$result[$model->id] = $model->transfer->name.' '.$model->transfer->last_name;
			}
		} 
		return $result;
	}
}
