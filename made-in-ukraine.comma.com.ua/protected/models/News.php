<?php

/**
 * This is the model class for table "comma_news".
 *
 * The followings are the available columns in table 'comma_news':
 * @property integer $id
 * @property string $code_name
 * @property string $datetime
 * @property string $image_filename
 * @property string $icon_filename
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
 class News extends BaseModel {
	
	public static $SectionUrl;

	const 	PATH_IMAGE_SRC  = '/graphics/news/src/',
			PATH_IMAGE 		= '/graphics/news/',
		 	PATH_IMAGE_GIF  = '/graphics/news/gif/',
            PATH_SHARE_IMAGE= '/graphics/news/share_img/';


	public 	$image, $update = false;

	public $date, $countNews; // для выборки с бд

	public $transfer_type = true;

	public function thumbnailsRules(){
    	return array('image' => array( '80x80' => array('method'=>'crop','canPinch'=>true,'selection'=>'80x80', 'path' => self::PATH_IMAGE) )  );	
    }

	/**
	 * @return string the associated database table name
	 */  
	public function tableName(){
		return parent::tablePrefix().'news';
	}

	public function init(){

		parent::init(); 

		if($this->isNewRecord){
			$this->datetime = date('Y-m-d H:i:s');
		} 
		return true;
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
			array('image_filename, icon_filename', 'length', 'max'=>50),
			array('changefreq', 'length', 'max'=>10),
			array('priority', 'length', 'max'=>3),
			array('shareimage_delete, gif_delete, image_delete, SectionUrl, update, date, countNews', 'default'),
			array('id, share_image,  gallery_id,  gif_filename, code_name, datetime, image_filename, icon_filename, views_num, comments_num, order_num, changefreq, priority, created_by, modified_by, blocked, added_time, edited_time, active', 'safe'), //, 'on'=>'search'
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

			'tags'=>array(self::HAS_MANY, 'NewsHasTegs', 'news_id'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'code_name' => 'Code Name',
			'datetime' => 'дата публикации',
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
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return News the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

 	//---------------------------------------------------------------------------------------- 
  	public static function getSectionUrl(){

  		if(self::$SectionUrl == ''){

  			if(Yii::app()->language != 'ru')
  				self::$SectionUrl = '/'.Yii::app()->language;

  			self::$SectionUrl .= '/'.Base::findControllerAlias('C_news');    
  		}
  		return self::$SectionUrl;
  	}

    public function getItemUrl(){  

        return self::getSectionUrl().'/'.$this->code_name; //.'.html'
    } 
  	//----------------------------------------------------------------------------------------
    public static function recountComments($id){
 
    	$News = News::model()->findByPk($id);     
    	if($News){
    		$News->update 		= true;
    		$News->save();
    	}
    }
	protected function beforeSave(){

		parent::beforeSave(); 

		if($this->image_delete == 1 && $this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename); 
  			$this->image_filename = '';
  		} 

  		if($this->update){ 
  			$sql = 'UPDATE '.$this->tableName().' AS t 
						SET t.`comments_num` = 
	  						(SELECT COUNT(*) FROM '.parent::tablePrefix().'comment AS com 
	  								WHERE com.`news_id` = t.id)
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

			if($_FILES[__CLASS__]['tmp_name']['image'] != NULL){

	  			if($this->image_filename != ''){ 
		  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
		  			$this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename);
		  		}  

	  	  		$doc = CUploadedFile::getInstance($this,'image');
				if($doc){
					//$type = $doc->getType();
					//$doc->getSize();
					
					Yii::import('application.components.Image');

					$tmp_image = $doc->getTempName();  

					$Image = new Image();  
					$Image->load($tmp_image);   

					$width = 80; 
					if ($Image->getWidth() < $width){
						$this->addError('thumbnail', 'Картинка слишком маленькая. Загрузите, пожалуйста, картинку большего разрешения');
						return false;
					}

					$Image->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE_SRC.$this->id);	 
					$this->image_filename = basename($Image->crop(array(80,80))->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE.$this->id));   

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

					$NewsHasTegs 			= new NewsHasTegs();
					$NewsHasTegs->news_id 	= $this->id;
	  				$NewsHasTegs->teg_id 	= $TagsTransferRu->parent_id;
	  				$NewsHasTegs->save(); 
				}
				 
			}
		} 

		return true;
  	}

  	protected function beforeDelete(){

  		parent::beforeDelete();   

  		NewsHasTegs::model()->deleteAll('news_id = :news_id',array(':news_id' => $this->id));
  		Comment::model()->deleteAll('news_id = :news_id',array(':news_id' => $this->id));

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
  			$this->fileDelete(self::PATH_IMAGE_SRC.$this->image_filename); 
  			$this->image_filename = '';
  		}  
  		 
        return true;
  	}

  	public function getTime(){

  		$time = strtotime($this->datetime); 
        
        $date = date('j.m', $time);

        return $date;
  	}

	public function getDate($monthType = ''){
    	
        $time = strtotime($this->datetime); 
        
        $date = date('j.m', $time);

        if(date('Y', $time) < date('Y'))
        	$date .= ' '.date('Y', $time);
         
        return $date;
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
        $criteria->join    		= ' LEFT JOIN '.NewsHasTegs::model()->tableName().' tags ON t.id = tags.news_id';
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
	        			'condition'=>'tags.teg_id = '.$TagsTransfer->parent_id,
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

	/*
	public static function getItems($lastId = 0, $page = 1, $limit = 8){
 
        // общее (находим даты)
        $condition = '';

        if($lastId > 0){
    		$News = News::model()->published()->findByPk($lastId);
    		if($News){
        		$condition = ' AND t.datetime <= "'.$News->datetime.'" AND t.id != '.$News->id.' ';
        	}
        }   

        //так пришлось сделать из за date_format
        $newsDates = News::model()->findAllBySql('SELECT date_format(t.datetime, "%Y-%m-%d") AS `date` 
													FROM '.News::tableName().' as t
													WHERE t.active = 1 AND t.datetime <= NOW() '.$condition.'
													GROUP BY `date` 
													ORDER BY t.datetime DESC, t.order_num
													LIMIT 0,'.$limit
												); 
        //-----------------------------------------------------------------------------------------------------
        $countRow = 0;
        $items    = array();
        $itemCount= 0;
        if($newsDates){  

        	foreach ($newsDates as $key=>$newsDate) {
        		
        		if($countRow == $limit) break;

        		$criteria               = new CDbCriteria;  
		        $criteria->select       = 't.*';
		        $criteria->condition    = ' t.active = 1 AND t.datetime LIKE "'.$newsDate->date.'%" ';    
        		$newsItems = News::model()->with('transfer:nameNoEmpty')->orderByDateDesc()->findAll($criteria);
        		if($newsItems){
        			$countNews = count($newsItems); 


        			foreach ($newsItems as $j => $News) {
        				 
        				$issetCount = count($items[$newsDate->date][$countRow]);
        				if($issetCount == 2){
        					$countRow++;
        				} 

        				$items[$newsDate->date][$countRow][] = $News; 
        				$itemCount++;
        			}// endforeach

        			$countRow++;

        			//var_dump($newsDate->date, '=>', $countNews); echo '<br/>'; exit; 
        		} 

        	}// endforeach
        }
        //-----------------------------------------------
        //так пришлось сделать из за date_format (расчет сколько будет страниц)
        $newsDates = News::model()->findAllBySql('SELECT date_format(t.datetime, "%Y-%m-%d") AS `date`,
        												(SELECT COUNT(*) 
    														FROM '.News::tableName().' as t2
    														WHERE t2.active = 1 AND
    															  t2.datetime LIKE CONCAT(date ,"%") ) as countNews
													FROM '.News::tableName().' as t
													WHERE t.active = 1 AND t.datetime <= NOW() '.$condition.'
													GROUP BY `date` 
													ORDER BY t.datetime DESC, t.order_num'
												); 
        $total = 0;
        if($newsDates){
        	foreach ($newsDates as $key => $News) {
        		if($News->countNews == 0) continue;

        		$countNews = $News->countNews;
        		if($countNews%2 == 1) $countNews ++;

        		$total = $total+($countNews/2);  
        	} 
        }  
   
        $total_pages 		= ceil($total / $limit); 
        $remains     		= 0;

        if($total_pages > 1){
            $remains = $total - ($page * $limit);
        }  

        //var_dump($total, $total_pages);  
 
        $result = array('total'         => $total,
                        'total_pages'   => $total_pages,
                        'itemsCount'    => $itemCount,
                        'page'          => $page, 
                        'remains'       => $remains, 
                        'items'         => $items 
        ); 
          
        return  $result; 
    } */


    public static function getItems($page = 1, $limit = 8){
 
        // общее
        $criteria               = new CDbCriteria;  
        $criteria->select       = 't.*';
        $criteria->condition    = 't.active = 1 AND t.datetime <= NOW() ';    
	 

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

        $itemaArray = array();
        if($itemsCount > 0){ 
        	foreach ($items as $key => $News) {
        		
        		$itemaArray[date('Y-m-d',strtotime($News->datetime))][] = $News;
        	}
        }
 
        $result = array('total'         => $total,
                        'total_pages'   => $total_pages,
                        'itemsCount'    => $itemsCount,
                        'page'          => $page, 
                        'remains'       => $remains,
                        'q'             => $q,
                        'items'         => $itemaArray 
        ); 
          
        return  $result; 
    }

    public static function getMainDate($date){

    	$_data = explode(' ', $date);


    	$_nowDate = date('Y-m-d');
    	$_sub_data = explode('-', $_nowDate);   
 
    	
    	if($_data[0] == $_nowDate){
    		return Yii::t('app','now_day');
    	}else if($_sub_data[0].'-'.$_sub_data[1].'-'.($_sub_data[2]-1) == $_data[0]){
    		return Yii::t('app','back_day');
    	}

 		$time = strtotime($date);  
        $date = date('j', $time).' '.Yii::t('app', 'm'.date('m', $time)).' '.date('Y', $time);

        return $date; 
    }


    public function getOther(){

        $countItems = 4;
        $result     = array();
        if(count($this->tags) > 0){

            $_ids   = CHtml::listData($this->tags, 'id', 'teg_id');
            $items = Yii::app()->db->createCommand()
                ->select('t.name')
                ->from(TagsTransfer::model()->tableName().' AS t')
                ->where('t.parent_id IN('.implode(',', $_ids).')',
                    array(':id' => $this->id))
                ->queryAll();

            if(count($items) > 0){
                foreach ($items as $value) {

                    $q = $value['name'];

                    $news 		= News::model()->actual()->published()->searchByTag($q)->orderByDateDesc()->findAll("t.id != :id", array(':id' => $this->id));
                    $videos 	= Videos::model()->actual()->published()->searchByTag($q)->orderByDateDesc()->findAll();
                    $photos     = Gallery::model()->actual()->published()->searchByTag($q)->orderByDateDesc()->findAll();
                    $articles   = Article::model()->actual()->published()->searchByTag($q)->orderByDateDesc()->findAll('blog = 0');
                    $blogs 	    = Article::model()->actual()->published()->searchByTag($q)->orderByDateDesc()->findAll('blog = 1');

                    //-----------------------------------
                    foreach($news as $News){
                        $result[] = $News;
                    }
                    foreach($videos as $Videos){
                        $result[] = $Videos;
                    }
                    foreach($photos as $Gallery){
                        $result[] = $Gallery;
                    }
                    foreach($articles as $Article){
                        $result[] = $Article;
                    }
                    foreach($blogs as $Article){
                        $result[] = $Article;
                    }

                    if(count($result) < 4){
                        $countItems = 4-count($result);
                    } else {
                        array_splice($result, 4);
                        $countItems = 0;
                    }
                }
            }
        }





        /*
        if(count($this->tags) > 0){
            $_ids   = CHtml::listData($this->tags, 'id', 'teg_id');
            $items = Yii::app()->db->createCommand()
                ->select('t.id')
                ->from($this->tableName().' AS t')
                ->leftjoin(NewsHasTegs::model()->tableName().' AS nt', 't.id = nt.news_id')
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
        */


        if($countItems > 0){
            // общее
            $criteria               = new CDbCriteria;
            $criteria->select       = 't.*';
            $criteria->condition    = 't.active = 1 AND t.datetime <= NOW() AND t.id != :id';
            $criteria->params 	    = array(':id' => $this->id);
            $criteria->limit        = $countItems;
            $itemsList              = self::model()->with('transfer:nameNoEmpty')->orderByDateDesc()->limit(4)->findAll($criteria);

            if($itemsList){
                foreach($itemsList as $items){
                    $result[] = $items;
                }
            }
        }


        /*
    	if(count($this->tags) > 0){
    		$_ids   = CHtml::listData($this->tags, 'id', 'teg_id'); 
    		$items = Yii::app()->db->createCommand()
				    ->select('t.id')
				    ->from($this->tableName().' AS t')
				    ->leftjoin(NewsHasTegs::model()->tableName().' AS nt', 't.id = nt.news_id')
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
						    			AND t.datetime <= NOW() 
						    			AND t.id != :id'.$dopSql, 
					    			array(':id' => $this->id))  
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
        */

    	return $result; 
    }
	
}
