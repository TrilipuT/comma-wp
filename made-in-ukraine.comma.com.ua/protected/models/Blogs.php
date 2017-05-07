<?php
/* 
 Исключительно для удобного добавления в админку 
 */
 class Blogs extends BaseModel { 

 	public static 	$SectionUrl, 
					$SectionBlogUrl;

	const 	PATH_IMAGE_SRC  = '/graphics/article/img/src/',
	 		PATH_IMAGE 		= '/graphics/article/img/',
	 	 	PATH_ICON_SRC	= '/graphics/article/icon/src/',
	 	 	PATH_ICON 		= '/graphics/article/icon/', 
	 	 	PATH_ICON_MINI  = '/graphics/article/icon/mini/',
			PATH_ICON_SMALL	= '/graphics/article/icon/small/',
			PATH_IMAGE_GIF  = '/graphics/article/gif/',
            PATH_SHARE_IMAGE= '/graphics/article/share_img/';

	public 	$image_delete = 0,
			$image,
			$icon_delete = 0,
			$icon,
			$gif_delete = 0,
			$gif; 

	public  $transfer_type = true;

	public function init(){

		parent::init(); 

		if($this->isNewRecord){
			$this->datetime = date('Y-m-d H:i:s');
		} 
		return true;
	}

	public static function getSectionBlogUrl(){
  		
  		if(self::$SectionBlogUrl == ''){

  			if(Yii::app()->language != 'ru')
  				self::$SectionBlogUrl = '/'.Yii::app()->language;

  			self::$SectionBlogUrl .= '/'.Base::findControllerAlias('C_blogs'); 
  		}

  		return self::$SectionBlogUrl;
  	} 

	public function getItemUrl(){  
    	if(!$this->blog){ 
        	return self::getSectionUrl().'/'.$this->code_name; //.'.html'
        } else {
        	return self::getSectionBlogUrl().'/'.$this->bloger->code_name.'/'.$this->code_name; //.'.html'
        }
    } 
	
	/**
	 * @return string the associated database table name
	 */  
	public function tableName(){
		return parent::tablePrefix().'article';
	}

	public function thumbnailsRules(){
    	return array('image' => array( '700x' => array('method'=>'scale','canPinch'=>true,'selection'=>'700x', 'path' => self::PATH_IMAGE)),
    				 'icon'  => array( '488x423' => array('method'=>'scale','canPinch'=>true,'selection'=>'488x423', 'path' => self::PATH_ICON),
    				 				   '190x' => array('method'=>'scale','canPinch'=>true,'selection'=>'190x', 'path' => self::PATH_ICON_SMALL),
    				 				   '80x80' => array('method'=>'scale','canPinch'=>true, 'path' => self::PATH_ICON_MINI)));	
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('code_name', 'required'),
			array('blog, rubric_id, views_num, comments_num, order_num, created_by, modified_by, blocked, active', 'numerical', 'integerOnly'=>true),
			array('code_name', 'length', 'max'=>255),
			array('color, changefreq', 'length', 'max'=>10),
			array('shareimage_delete, image_filename, icon_filename', 'length', 'max'=>50),
			array('priority', 'length', 'max'=>3), 
			array('gif_delete, gif, image_delete, image, icon, icon_delete', 'default'), 

			array('id, share_image, gallery_id, interview, gif_filename, bloger_id, light,  code_name, blog, rubric_id, datetime, color, image_filename, icon_filename, views_num,
					comments_num, order_num, changefreq, priority, created_by, modified_by, blocked, added_time, edited_time, active', 'safe'), //, 'on'=>'search'
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'bloger' 	=> array(self::BELONGS_TO, 'Blogers', 'bloger_id' ),
			'transfer' => array(self::HAS_ONE, 
										'ArticleTransfer', 
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
			'blog' => 'Blog',
			'rubric_id' => 'рубрика',
			'datetime' => 'дата публикации',
			'color' => 'цвет',
			'image_filename' => 'фото в статье',
			'icon_filename' => 'иконка превью',
			'views_num' => 'количество просмотров',
			'comments_num' => 'количество коментов',
			'order_num' => 'порядковый номер',
			'changefreq' => 'changefreq (sitemap)',
			'priority' => 'priority (sitemap)',
			'created_by' => 'кто создал',
			'modified_by' => 'кто последний редактировал',
			'blocked' => 'Blocked',
			'added_time' => 'Added Time',
			'edited_time' => 'Edited Time',
			'active' => 'Active',
			'bloger_id' => 'Блогер',
			'light'  => 'светлый блок в статье',
			'interview' => 'интервью'
		);
	}
 

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Article the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
 
  	//----------------------------------------------------------------------------------------

	protected function beforeSave(){

		parent::beforeSave(); 

		$this->blog = 1;

		if($this->gif_delete == 1 && $this->gif_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE_GIF.$this->gif_filename); 
  			$this->gif_filename = '';
  		} 

		if($this->image_delete == 1 && $this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename);
  			$this->image_filename = '';
  		} 

  		if($this->icon_delete == 1 && $this->icon_filename != ''){ 
  			$this->fileDelete(self::PATH_ICON.$this->icon_filename);
  			$this->fileDelete(self::PATH_ICON_SRC.$this->icon_filename);
  			$this->fileDelete(self::PATH_ICON_SMALL.$this->icon_filename);
  			$this->icon_filename = '';
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
	  			$this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename);
	  		}  

  	  		$doc = CUploadedFile::getInstance($this,'image');
			if($doc){
				//$type 	= $doc->getType();
				//$doc->getSize();
				
				Yii::import('application.components.Image');

				$tmp_image = $doc->getTempName();  

				$Image  	= new Image();  
				$Image->load($tmp_image);   

				$width  = 700; 
				if ($Image->getWidth() < $width){
					$this->addError('thumbnail', 'Картинка слишком маленькая. Загрузите, пожалуйста, картинку большего разрешения');
					return false;
				}

				$Image->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE_SRC.$this->id);	
				 
				$this->image_filename 	= basename($Image->scale(array('w',700))
														 ->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE.$this->id));  


				$this->addUploadedFile($this->image_filename);   
				 
			 	$_FILES[__CLASS__]['tmp_name']['image'] = NULL;
				$this->update(array('image_filename'));  
	 		}
  	  	}	  

  	  	if($_FILES[__CLASS__]['tmp_name']['gif'] != NULL){

  	  		$doc = CUploadedFile::getInstance($this,'gif');
			if($doc){
				//$type 	= $doc->getType();
				//$doc->getSize();
				$ext = explode('.',$doc->getName());
				$ext = $ext[count($ext)-1];

				$tmp_image  = $doc->getTempName(); 

				copy($tmp_image, $_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE_GIF.$this->id.'.'.$ext); 
				$this->gif_filename = $this->id.'.'.$ext; 

			 	$_FILES[__CLASS__]['tmp_name']['gif'] = NULL;
				$this->update(array('gif_filename'));  
	 		}
  	  	}	


  	  	if($_FILES[__CLASS__]['tmp_name']['icon'] != NULL){

  	  		if($this->icon_filename != ''){ 
	  			$this->fileDelete(self::PATH_ICON.$this->icon_filename);
	  			$this->fileDelete(self::PATH_ICON_SRC.$this->icon_filename);
	  			$this->fileDelete(self::PATH_ICON_SMALL.$this->icon_filename); 
	  		}

  	  		$doc = CUploadedFile::getInstance($this,'icon');
			if($doc){
				//$type 	= $doc->getType();
				//$doc->getSize();
				
				Yii::import('application.components.Image');

				$tmp_image 				= $doc->getTempName();  

				$Image  	= new Image();  
				$Image->load($tmp_image);   

				$width  = 400; 
				$height = 200;
				if ($Image->getWidth() < $width || $Image->getHeight() < $height ){
					$this->addError('thumbnail', 'Картинка слишком маленькая. Загрузите, пожалуйста, картинку большего разрешения');
					return false;
				}

				$Image->save($_SERVER['DOCUMENT_ROOT'].self::PATH_ICON_SRC.$this->id);	
				$Image->crop(array(488,423))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_ICON.$this->id);
				 
				$this->icon_filename = basename($Image->scale(array('w',190))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_ICON_SMALL.$this->id));  
			 	
			 	$this->addUploadedFile($this->icon_filename);   

			 	$_FILES[__CLASS__]['tmp_name']['icon'] = NULL;
				$this->update(array('icon_filename'));  
	 		}
  	  	}	  
	  	 
  	  	//echo '<pre>'; var_dump($_POST['Thumbnail']); echo '</pre>'; exit;
	  	//--------------------------------------------------------------------------------------------------------------------------------------
	  	if (isset($_POST['Thumbnail']) && ($this->image_filename != NULL || $this->icon_filename != NULL )){ 

	  		foreach ($_POST['Thumbnail'] as $type => $item) {
	  			
	  			if(count($item) > 0){
	  				foreach ($item as $resolution => $rules){
				  
						//if ($_POST['Thumbnail'][$resolution]['delete'] == 1)
							//$this->deleteThumbnail($resolution);
							
						// Ищем исходник иконки
						if($type == 'image'){
							$src = self::PATH_IMAGE_SRC.$this->image_filename; 
							$img = $this->image_filename;
						} else {
							$src = self::PATH_ICON_SRC.$this->icon_filename; 
							$img = $this->icon_filename;
						}
						
						
						if ($img != null && file_exists($_SERVER['DOCUMENT_ROOT'].$src) ){

							Yii::import('application.components.Image');
							$Image = new Image();
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
							  
						}// end if image isset  
					} // end foreach  
	  			}
	  		}  // end foreach 
		}   
			 
		//добавление  
		$postnewTagsTransfer = Yii::app()->request->getPost('newTagsTransfer'); 
		if(count($postnewTagsTransfer) > 0){ 			
			
			foreach ($postnewTagsTransfer as $tagData){

				if($tagData['ru_name'] == NULL || $tagData['ua_name'] == NULL)
					continue;

				//var_dump($tagData);
				$TagsTransferRu = TagsTransfer::model()->find('language_id = 1 AND name = :name', array(':name' => $tagData['ru_name']));
				$TagsTransferUa = TagsTransfer::model()->find('language_id = 2 AND name = :name', array(':name' => $tagData['ua_name']));

				if(!$TagsTransferRu || !$TagsTransferUa){

					$Tags = new Tags();
  					$Tags->save(); 

					$TagsTransferUa 				= new TagsTransfer();
			 		$TagsTransferUa->parent_id 		= $Tags->id;
			 		$TagsTransferUa->language_id  	= 2;
			 		$TagsTransferUa->name 			= $tagData['ua_name'];
			 		$TagsTransferUa->save();

			 		$TagsTransferRu 				= new TagsTransfer();
			 		$TagsTransferRu->parent_id 		= $Tags->id;
			 		$TagsTransferRu->language_id  	= 1;
			 		$TagsTransferRu->name 			= $tagData['ru_name'];
			 		$TagsTransferRu->save();  

				}   

				$ArticleHasTegs 			= new ArticleHasTegs();
				$ArticleHasTegs->article_id = $this->id;
  				$ArticleHasTegs->teg_id 	= $TagsTransferRu->parent_id;
  				$ArticleHasTegs->save(); 
			}
			 
		} 

		return true;
  	}
 
  	
 
	protected function beforeDelete(){

  		parent::beforeDelete();   
 
  		ArticleHasTegs::model()->deleteAll('article_id = :article_id',array(':article_id' => $this->id));
  		Comment::model()->deleteAll('article_id = :article_id',array(':article_id' => $this->id));
  		 
        return true;
  	}

  	protected function afterDelete(){

  		parent::afterDelete();   

  		if($this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename); 
  		} 

  		if($this->icon_filename != ''){ 
  			$this->fileDelete(self::PATH_ICON.$this->icon_filename);
  			$this->fileDelete(self::PATH_ICON_SRC.$this->icon_filename);
  			$this->fileDelete(self::PATH_ICON_SMALL.$this->icon_filename); 
  		}

  		if($this->gif_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE_GIF.$this->gif_filename); 
  			$this->gif_filename = '';
  		} 
  		 
        return true;
  	} 
 
    public function blog($status){

		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.blog = :status',
			'params' 	=> array(':status' => $status) 
		));
		return $this;
	}
	
}
