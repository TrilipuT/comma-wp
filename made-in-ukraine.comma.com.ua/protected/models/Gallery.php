<?php

/**
 * This is the model class for table "sf_gallery".
 *
 * The followings are the available columns in table 'sf_gallery':
 * @property integer $id
 * @property integer $order_num
 * @property string $changefreq
 * @property string $priority
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $added_time
 * @property string $edited_time
 * @property integer $active
 */
class Gallery extends BaseModel {

	public static $SectionUrl;

	const 	PATH_IMAGE_SRC  = '/graphics/gallery/src/',
			PATH_IMAGE 		= '/graphics/gallery/',
			PATH_IMAGE2 	= '/graphics/gallery/2/',
            PATH_IMAGE_GIF  = '/graphics/gallery/gif/',
            IMAGE_80x80     = '/graphics/gallery/80x80/' ,
			//PATH_IMAGE_ICON = '/graphics/gallery/icon/',
            PATH_SHARE_IMAGE= '/graphics/gallery/share_img/';
			

	public  $transfer_type = true;

	public  $photosRow, 
			$authors,
			$image_delete = 0,
			$image,
			$gif_delete = 0,
			$gif,
			$update = false; 


	public function init(){

		parent::init(); 

		if($this->isNewRecord){
			$this->datetime = date('Y-m-d H:i:s');
		} 
		return true;
	}

	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()	{
		return parent::tablePrefix().'gallery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_num, created_by, modified_by, active', 'numerical', 'integerOnly'=>true),
			array('changefreq', 'length', 'max'=>10),
			array('shareimage_delete, gif_delete, gif, photosRow, authors, image_delete, image, update', 'default'),
			//array('priority', 'length', 'max'=>1), 
			array('id, share_image, gif_filename, in_article, gallery_id, order_num, code_name, image_filename, datetime, changefreq, priority, created_by, modified_by, added_time, edited_time, active', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(

			'photos'   	=> array(self::HAS_MANY, 'Photos', 'gallery_id'),
 			'tags' 		=> array(self::HAS_MANY, 'GalleryHasTegs', 'gallery_id'),
			'transfer' 	=> array(self::HAS_ONE, 
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
			'order_num' => 'порядковый номер',
			'datetime' => 'дата',
			'changefreq' => 'sitemap',
			'priority' => 'sitemap',
			'created_by' => 'кто создал',
			'modified_by' => 'кто последний редактировал',
			'added_time' => 'Added Time',
			'edited_time' => 'Edited Time',
			'active' => 'Active',
			'in_article' => 'публиковать в статье'
		);
	}

	public static function recountComments($id){
 
    	$Gallery = Gallery::model()->findByPk($id);     
    	if($Gallery){
    		$Gallery->update = true;
    		$Gallery->save();
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
  			$this->fileDelete(self::PATH_IMAGE2.$this->image_filename);
            $this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename);
            $this->fileDelete(self::IMAGE_80x80.$this->image_filename);
  			$this->image_filename = '';
  		} 

  		if($this->update){ 
  			$sql = 'UPDATE '.$this->tableName().' AS t 
						SET t.`comments_num` = 
	  						(SELECT COUNT(*) FROM '.parent::tablePrefix().'comment AS com 
	  								WHERE com.`gallery_id` = t.id)
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

	  	  		$doc = CUploadedFile::getInstance($this,'image');
				if($doc){ 
					
					Yii::import('application.components.Image');

					$tmp_image = $doc->getTempName();  

					$Image = new Image();  
					$Image->load($tmp_image);

					$Image->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE_SRC.$this->id);
					$Image->scale(array(200,200))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE.$this->id);
					$Image->scale(array(80,80))->save($_SERVER['DOCUMENT_ROOT'].self::IMAGE_80x80.$this->id);

					$this->image_filename = basename($Image->scale(array(250,250))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE2.$this->id));   
 
					 
				 	$_FILES[__CLASS__]['tmp_name']['image'] = NULL;
					$this->update(array('image_filename'));  
		 		}
	  	  	}	   

			//---------------------------------------------------------------------------------------------------------
			if(count($this->photosRow) > 0){ 
	 			foreach ($this->photosRow as $photoId => $values) {
	 				$Photos = Photos::model()->findByPk($photoId);
	 				if($Photos){ 
	 					$Photos->active    = $values['active'];
	 					$Photos->order_num = $values['order_num'];
	 					$Photos->update(array('order_num','active'));
	 				}
	 			}
	  	  	}

	  	  	//---------------------------------------------------------------------------------------------------------
  			GalleryHasAuthors::model()->deleteAll('gallery_id = :gallery_id',array(':gallery_id' => $this->id));
  			//-------------------------------
  			if(count($this->authors) > 0){
	  			foreach($this->authors as $idt){ 
	  					
					$GalleryHasAuthors 		       = new GalleryHasAuthors();
					$GalleryHasAuthors->authors_id = $idt;
					$GalleryHasAuthors->gallery_id = $this->id;

					$GalleryHasAuthors->save(); 
	  			}//end foreach
  			}
  			//------------------------------------------------------------\
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

					$GalleryHasTegs 			= new GalleryHasTegs();
					$GalleryHasTegs->gallery_id = $this->id;
	  				$GalleryHasTegs->teg_id 	= $TagsTransferRu->parent_id;
	  				$GalleryHasTegs->save(); 
				}
				 
			}

  		} 

		return true;
  	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Gallery the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected function beforeDelete(){

  		parent::beforeDelete();   

  		GalleryHasAuthors::model()->deleteAll('gallery_id = :gallery_id',array(':gallery_id' => $this->id));
  		GalleryHasTegs::model()->deleteAll('gallery_id = :gallery_id',array(':gallery_id' => $this->id));
	 	Comment::model()->deleteAll('gallery_id = :gallery_id',array(':gallery_id' => $this->id));
	 	
        return true;
  	}

	protected function afterDelete(){

  		parent::afterDelete();   

  		$PhotosItems = Photos::model()->findAll(array('condition' => 'gallery_id = :gallery_id',
                                    				  'params'   => array(':gallery_id' => $this->id))); 

	    if($PhotosItems){
	        foreach ($PhotosItems as $Photos) {
	           $Photos->delete();
	        }
	    }   

	    if($this->gif_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE_GIF.$this->gif_filename); 
  			$this->gif_filename = '';
  		} 

	    if($this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE2.$this->image_filename);
            $this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename);
            $this->fileDelete(self::IMAGE_80x80.$this->image_filename);
  			$this->image_filename = '';
  		}  
  		 
        return true;
  	} 

  	public function getList() { 

 		return CHtml::listData(Gallery::model()->orderByOrderNum()->published()->findAll(), 'id', 'transfer.name');

 		/*
		$result = Yii::app()->db->createCommand()
				->select('id, title_'.Yii::app()->language.' AS title')
				->from($this->tableName())  
				->queryRow();*/ 
	}

 

    //---------------------------------------------------------------------------------------- 
  	public static function getSectionUrl(){

  		if(self::$SectionUrl == ''){

  			if(Yii::app()->language != 'ru')
  				self::$SectionUrl = '/'.Yii::app()->language;

  			self::$SectionUrl .= '/'.Base::findControllerAlias('C_gallery');   
  		}
  		return self::$SectionUrl;
  	}

    public function getItemUrl(){  

        return self::getSectionUrl().'/'.$this->code_name; //.'.html'
    } 
  	//----------------------------------------------------------------------------------------
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
        $criteria->join    		= ' LEFT JOIN '.GalleryHasTegs::model()->tableName().' tags ON t.id = tags.gallery_id';
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

	public function getDate($monthType=''){
    	
        $time = strtotime($this->datetime); 
      	
      	if($monthType == 'name'){
        	$date = date('j', $time).' '.Yii::t('app', 'm'.date('m', $time));
        } else {
        	$date = date('d.m.Y', $time);
        } 
        
        return $date;
    } 

	public static function getItems($author_id = 0, $page = 1, $limit = 8){
 
        // общее
        $criteria               = new CDbCriteria;  
        $criteria->select       = 't.*';
        $criteria->condition    = 't.active = 1 AND t.datetime <= NOW() AND in_article = 0';    

       
        if($author_id > 0){
        	$criteria->join 		= 'LEFT JOIN `'.GalleryHasAuthors::model()->tableName().'` AS GA ON `t`.`id` = `GA`.`gallery_id`'; 
        	$criteria->condition 	.= ' AND `GA`.authors_id = :authors_id';
        	$criteria->params 		+= array(":authors_id" => $author_id);
        }

       /*
        if($notId != ''){ 
        	$criteria->addCondition("t.id != :id");
        	$criteria->params += array(":id" => $notId);
        } */		  	 

        $total      		= self::model()->with('transfer:nameNoEmpty')->orderByDateDesc()->count($criteria);     //
        $offset     		= ( ($page-1) * $limit );   

        $criteria->limit 	= $limit;
		$criteria->offset 	= $offset;
  
        $items       		= self::model()->with('transfer:nameNoEmpty')->orderByDateDesc()->findAll($criteria);  //->with('transfer:nameNoEmpty')
        $itemsCount   		= count($items);  

        $total_pages 		= ceil($total / $limit);
        $remains     		= 0;

        if($total_pages > 1){
            $remains = $total - ($page * $limit);
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


}
