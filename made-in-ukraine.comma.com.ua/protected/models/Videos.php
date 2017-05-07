<?php

/**
 * This is the model class for table "comma_videos".
 *
 * The followings are the available columns in table 'comma_videos':
 * @property integer $id
 * @property string $code_name
 * @property string $video_code
 * @property string $datetime
 * @property string $image_filename
 * @property integer $views_num
 * @property integer $comments_num
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
 class Videos extends BaseModel {
	
	public static $SectionUrl;

	const 	PATH_IMAGE_SRC  = '/graphics/videos/src/',
		 	PATH_IMAGE 		= '/graphics/videos/',
		 	PATH_IMAGE_SMALL= '/graphics/videos/small/',
		 	PATH_IMAGE_ICON = '/graphics/videos/icon/',
		 	PATH_IMAGE_GIF  = '/graphics/videos/gif/',
            PATH_SHARE_IMAGE= '/graphics/videos/share_img/';

	public 	$image_delete = 0,
			$image,
			$authors,
			$gif_delete = 0,
			$gif,
			$update = false;

	public $transfer_type = true;

	public function init(){

		parent::init(); 

		if($this->isNewRecord){
			$this->datetime = date('Y-m-d H:i:s');
		} 


		return true;
	}

	public function thumbnailsRules(){  
    	return array('image' => array('190x105' => array('method'=>'crop','canPinch'=>true, 'path' => self::PATH_IMAGE_SMALL),
    								  '490x270' => array('method'=>'crop','canPinch'=>true, 'path' => self::PATH_IMAGE),
    								  '80x80' => array('method'=>'crop','canPinch'=>true, 'path' => self::PATH_IMAGE_ICON) ));	
    }

	/**
	 * @return string the associated database table name
	 */  
	public function tableName(){
		return parent::tablePrefix().'videos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('code_name', 'required'),
			array('views_num, comments_num, order_num, created_by, modified_by, blocked, active', 'numerical', 'integerOnly'=>true),
			array('code_name', 'length', 'max'=>255),
			array('image_filename', 'length', 'max'=>50),
			array('changefreq', 'length', 'max'=>10),
			array('priority', 'length', 'max'=>3), 
			array('shareimage_delete, gif_delete, gif, image_delete, image, SectionUrl, authors, update', 'default'),
			array('id, share_image, gif_filename, gallery_id, code_name, category_id, video_code, datetime, image_filename, views_num, comments_num, order_num, changefreq, priority, created_by, modified_by, blocked, added_time, edited_time, active', 'safe'), //, 'on'=>'search'
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
										'params' 	=> array(':lang_id' => Language::getActiveLanguageId())),

			'tags'=>array(self::HAS_MANY, 'VideosHasTegs', 'video_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'code_name' => 'Code Name',
			'video_code' => 'код плеера',
			'datetime' => 'дата публикации',
			'image_filename' => 'фото превью',
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
		$criteria->compare('video_code',$this->video_code,true);
		$criteria->compare('datetime',$this->datetime,true);
		$criteria->compare('image_filename',$this->image_filename,true);
		$criteria->compare('views_num',$this->views_num);
		$criteria->compare('comments_num',$this->comments_num);
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
	 * @return Videos the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

 	//---------------------------------------------------------------------------------------- 
  	public static function getSectionUrl(){

  		if(self::$SectionUrl == ''){

  			if(Yii::app()->language != 'ru')
  				self::$SectionUrl = '/'.Yii::app()->language;

  			self::$SectionUrl .= '/'.Base::findControllerAlias('C_videos');   
  		}
  		return self::$SectionUrl;
  	}

    public function getItemUrl(){  

        return self::getSectionUrl().'/'.$this->code_name; //.'.html'
    } 
  	//----------------------------------------------------------------------------------------
    public static function recountComments($id){
 
    	$Videos = Videos::model()->findByPk($id);     
    	if($Videos){
    		$Videos->update 		= true;
    		$Videos->save();
    	}
    }

	protected function beforeSave(){

		parent::beforeSave(); 

		if($this->gif_delete == 1 && $this->gif_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE_GIF.$this->gif_filename); 
  			$this->gif_filename = '';
  		} 

		if($this->image_delete == 1 && $this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SMALL.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_ICON.$this->image_filename);
  			$this->image_filename = '';
  		} 

  		if($this->update){ 
  			$sql = 'UPDATE '.$this->tableName().' AS t 
						SET t.`comments_num` = 
	  						(SELECT COUNT(*) FROM '.parent::tablePrefix().'comment AS com 
	  								WHERE com.`video_id` = t.id)
	  					WHERE t.id = :id'; 
 
			Yii::app()->db->createCommand($sql)->bindParam(":id", $this->id, PDO::PARAM_STR)->execute();
  			//------------------------------------------
  			$sql = 'SELECT t.comments_num FROM '.$this->tableName().' AS t WHERE t.id = :id';
			$dataReader = Yii::app()->db->createCommand($sql)->bindParam(":id", $this->id, PDO::PARAM_STR)->query();
			$row = $dataReader->read();
	  		$this->comments_num = $row['comments_num'];	 
  		} 
  		
  		return true;
	}


	protected function afterSave(){ 

  		parent::afterSave();  
  		
  		//удаление маркера новой записи 
  		if($this->isNewRecord)
  			unset($this->isNewRecord); 
 	
	 	
	 	if(!$this->update){


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
	 		
	 		if($_FILES[__CLASS__]['tmp_name']['image'] != NULL){

	  			if($this->image_filename != ''){ 
		  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
		  			$this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename);
		  			$this->fileDelete(self::PATH_IMAGE_SMALL.$this->image_filename);
		  			$this->fileDelete(self::PATH_IMAGE_ICON.$this->image_filename);
		  		}  

	  	  		$doc = CUploadedFile::getInstance($this,'image');
				if($doc){
					//$type = $doc->getType();
					//$doc->getSize();
					
					Yii::import('application.components.Image');

					$tmp_image = $doc->getTempName();  

					$Image = new Image();  
					$Image->load($tmp_image);   

					$width = 490; 
					if ($Image->getWidth() < $width){
						$this->addError('thumbnail', 'Картинка слишком маленькая. Загрузите, пожалуйста, картинку большего разрешения');
						return false;
					}

					$Image->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE_SRC.$this->id);	 
					$Image->crop(array(490,270))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE.$this->id); 
					$Image->crop(array(80,80))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE_ICON.$this->id); 
					$this->image_filename = basename($Image->crop(array(190,105))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE_SMALL.$this->id));   

					$this->addUploadedFile($this->image_filename);   
					 
				 	$_FILES[__CLASS__]['tmp_name']['image'] = NULL;
					$this->update(array('image_filename'));  
		 		}
	  	  	}	   
	  	  	//echo '<pre>'; var_dump($_POST['Thumbnail']); echo '</pre>'; exit;
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

	  	  	//---------------------------------------------------------------------------------------------------------
  			VideosHasAuthors::model()->deleteAll('video_id = :video_id',array(':video_id' => $this->id));
  			//-------------------------------
  			if(count($this->authors) > 0){
	  			foreach($this->authors as $idt){ 
	  					
					$VideosHasAuthors 		      = new VideosHasAuthors();
					$VideosHasAuthors->authors_id = $idt;
					$VideosHasAuthors->video_id   = $this->id;

					$VideosHasAuthors->save(); 
	  			}//end foreach
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

					$VideosHasTegs 				= new VideosHasTegs();
					$VideosHasTegs->video_id 	= $this->id;
	  				$VideosHasTegs->teg_id 		= $TagsTransferRu->parent_id;
	  				$VideosHasTegs->save(); 
				}
				 
			}
	 	} 

		return true;
  	}

  	protected function beforeDelete(){

  		parent::beforeDelete();   

  		VideosHasAuthors::model()->deleteAll('video_id = :video_id',array(':video_id' => $this->id));
  		VideosHasTegs::model()->deleteAll('video_id = :video_id',array(':video_id' => $this->id));
	 	Comment::model()->deleteAll('video_id = :video_id',array(':video_id' => $this->id));

	 	
        return true;
  	}
 
	protected function afterDelete(){

  		parent::afterDelete();   

  		if($this->gif_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE_GIF.$this->gif_filename); 
  			$this->gif_filename = '';
  		} 

  		if($this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SMALL.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_ICON.$this->image_filename);
  			$this->image_filename = '';
  		}  
  		 
        return true;
  	}
 

    public function searchByKeywords($k=''){  
		$k = mb_strtolower($k, 'utf-8');

		$params = array();

		$tmp_condition = array();
		$tmp_condition[] = '(transfer.name LIKE :keys OR transfer.description LIKE :keys)';
		$params  += array(':keys' => '%'.$k.'%'); 

		$k = explode(" ", $k);
		
		$tmp_params = array();
		$i = 0; 

		foreach ($k as $kw) { 

			$tag = mb_strtolower($tag, 'utf-8');
        	$TagsTransfer = Tags::findByName($kw);
        	if($TagsTransfer){
        		$tmp_condition[] = '(transfer.name LIKE :key'.$i.' OR transfer.description LIKE :key'.$i.' OR tags.teg_id = '.$TagsTransfer->parent_id.')';
        	} else {
        		$tmp_condition[] = '(transfer.name LIKE :key'.$i.' OR transfer.description LIKE :key'.$i.')';
        	} 
			$params  += array(':key'.$i => '%'.$kw.'%'); 
			$i++;
		} 

		$criteria               = new CDbCriteria;  
        $criteria->select       = 't.*';
        $criteria->condition	= implode(' OR ', $tmp_condition);
		$criteria->params 		= $params; 
        $criteria->join    		= ' LEFT JOIN '.VideosHasTegs::model()->tableName().' tags ON t.id = tags.video_id';
        $criteria->with 		= array('transfer');
        
		$this->getDbCriteria()->mergeWith($criteria);  
 
		return $this;
	} 
    
    public function searchByTag($tag='', $status = true){
		$tag = mb_strtolower($tag, 'utf-8');
        $TagsTransfer = Tags::findByName($tag);
 

		if($TagsTransfer){
			$this->getDbCriteria()->mergeWith(array(
				'with'	=> array(
			    	'tags'=>array(
	        			'joinType'=>'RIGHT JOIN',
	        			'condition'=>'tags.teg_id = \''.$TagsTransfer->parent_id.'\'',
	    			),
				)
			));
		} else if($status){ //делаем так что б ничего не нашло
			$this->getDbCriteria()->mergeWith(array(
				'with'	=> array(
			    	'tags'=>array(
	        			'joinType'=>'RIGHT JOIN',
	        			'condition'=>'tags.teg_id = -1',
	    			),
				)
			));
		} 

		return $this;
	}
	
	public function withCat($category_id){

		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.category_id = :category_id',
			'params'	=> array(':category_id' => $category_id)
		));
		return $this;
	}

	

	public static function getForMain($page = 1){

		$result = false;

		$videoCatsItems = VideoCats::model()->published()->orderByOrderNum()->findAll();
		if($videoCatsItems){

			$result = array();
			foreach ($videoCatsItems as $VideoCats) {
				
				$Videos = Videos::model()->published()->orderByDateDesc()->withCat($VideoCats->id)->page($page, 1)->find();
				if(!$Videos){
					$Videos = Videos::model()->published()->orderByDateDesc()->withCat($VideoCats->id)->find();
				}  

				$result[$VideoCats->id] = array('cat' => $VideoCats, 'video' => $Videos, 'main' => $VideoCats->main);
			}// end foreach
		}

		return $result;
	}

	public static function getVideos($catId= 0 , $notId = 0, $page = 1, $limit = 8, $start = 0){
 
        // общее
        $criteria               = new CDbCriteria;  
        $criteria->select       = 't.*';
        $criteria->condition    = 't.active = 1 AND t.datetime <= NOW() ';    


        if($notId != ''){
        	$criteria->addCondition("t.id != :id");
        	$criteria->params += array(":id" => $notId);
        }

		if($catId > 0){
			$criteria->condition .= ' AND category_id = :category_id';
			$criteria->params 	 += array(":category_id" => $catId);
		}   	 

        $total      		= Videos::model()->with('transfer:nameNoEmpty')->orderByDateDesc()->count($criteria); 


        $offset     		= ( ($page-1) * $limit );   

        $criteria->limit 	= $limit;
		$criteria->offset 	= $start+$offset;
  
        $items       		= Videos::model()->with('transfer:nameNoEmpty')->orderByDateDesc()->findAll($criteria);  
        $itemsCount   		= count($items);  

        $total_pages 		= ceil($total / $limit);
        $remains     		= 0;
       	 

        if($total_pages > 1){
        
        	$remains = $total - ($start+($page * $limit));
        }

        $result = array('total'         => $total,
                        'total_pages'   => $total_pages,
                        'itemsCount'    => $itemsCount,
                        'page'          => $page, 
                        'remains'       => $remains,
                        'q'             => $q,
                        'items'         => $items 
        ); 
          
        return  $result; 
    }

    public function getOther($blog = 0){ 
 
    	// общее
        $criteria               = new CDbCriteria;  
        $criteria->select       = 't.*';
        $criteria->condition    = 't.active = 1 AND t.datetime <= NOW()'; // AND t.id != :id
        //$criteria->params 	    = array(':id' => $this->id);  
        $result 				= array();
        $countItems 			= 4;
        $ids 					= array();

    	if(count($this->tags) > 0){
    		$_ids   = CHtml::listData($this->tags, 'id', 'teg_id'); 
    		$items = Yii::app()->db->createCommand()
				    ->select('t.id')
				    ->from($this->tableName().' AS t')
				    ->leftjoin(VideosHasTegs::model()->tableName().' AS vt', 't.id = vt.video_id')
				    ->where('t.active = 1 
				    			AND t.datetime <= NOW() 
				    			AND t.id != :id
				    			AND t.id IN('.implode(',', $_ids).')',
				    		array(':id' => $this->id))
				    ->limit(4)
				    ->order('t.datetime DESC')
				    ->queryAll();
				
		    if(count($items) > 0){
		    	foreach ($items as $value) {
		    		$ids[] = $value['id'];
		    	}
		    	$countItems = $countItems-count($items);
		    }  
    	}  
    	//---------------------------------------------------------------------------
    	if($countItems > 0){

    		$dopSql = '';
    		if(count($ids) > 0){
    			$dopSql = ' AND t.id NOT IN('.implode(',', $ids).')';
    		} 
    		//---------------------------------- 
			$items = Yii::app()->db->createCommand()
						    ->select('t.id')
						    ->from($this->tableName().' AS t')
						    ->where('t.active = 1 
						    			AND t.category_id = :category_id
						    			AND t.datetime <= NOW() 
						    			AND t.id != :id'.$dopSql, 
					    			array(':id' => $this->id, ':category_id' => $this->category_id))  
						    ->limit($countItems)
						    ->order('t.datetime DESC')
						    ->queryAll();   
    		//-----------
    		if(count($items) > 0){  
		    	foreach ($items as $value) {
		    		$ids[] = $value['id'];
		    	}
		    } 
    	} 
		//---------------------------------------------------------------------------
		if(count($ids) > 0){

			$criteria->condition .= ' AND t.id IN('.implode(',', $ids).')';
			$result = self::model()->with('transfer:nameNoEmpty')->orderByDateDesc()->limit(4)->findAll($criteria);
		}  

    	return $result; 
    }

}
