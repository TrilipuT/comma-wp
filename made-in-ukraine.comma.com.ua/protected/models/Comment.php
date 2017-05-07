<?php 
class Comment extends BaseModel{
 
	public 	$update = false;
	private $_userList;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comment the static model class
	 */
	public static function model($className=__CLASS__){

		return parent::model($className);
	}

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

		return $this->tablePrefix().'comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('datetime, content', 'required'),
			array('update', 'default'),
			array('news_id, article_id, video_id, gallery_id,  comment_id, user_id, votes_pro, order_num, active', 'numerical', 'integerOnly'=>true),

			array('id, news_id, article_id, gallery_id, count_childrens, comment_id, is_new, 
					user_id, datetime, content, votes_pro, added_time, edited_time, order_num, active', 'safe' ),
		);
	}
 

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()	{
		return array(
			'id' 			=> 'ID', 
			'news_id' 		=> 'ID новости', 
			'event_id' 		=> 'ID события', 
			'video_id' 		=> 'ID видео', 
			'quiz_id' 		=> 'ID опроса', 
			'program_id' 	=> 'ID программы', 
			'expert_id' 	=> 'ID эксперта', 
			'comment_id' 	=> 'ID родительского комментария', 
			'user_id' 		=> 'ID автора комментария', 
			'datetime' 		=> 'дата и время публикации', 
			'content' 		=> 'комментарий',
			'votes_pro' 	=> 'количество голосов за',
			'votes_con' 	=> 'количество голосов против',
			'order_num' 	=> 'порядковый номер',
			'added_time' 	=> 'время добавления',
			'edited_time' 	=> 'время редактирования',
			'active' 		=> 'актив'
		);
	} 

	public function orderByDate(){

		$this->getDbCriteria()->mergeWith(array(
			'order'	=> $this->tableAlias.'.datetime DESC, '.$this->tableAlias.'.order_num, '.$this->tableAlias.'.id'
		));
		return $this;
	}
 	 
 	 


    public function withNews($news_id=0){

		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.news_id = :news_id',
			'params'	=> array(':news_id' => $news_id)
		));
		return $this;
	} 

	 

	public function withVideo($video_id=0){

		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.video_id = :video_id',
			'params'	=> array(':video_id' => $video_id)
		));
		return $this;
	} 
 

	public function withParent($comment_id=0){

		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.comment_id = :comment_id',
			'params'	=> array(':comment_id' => $comment_id)
		));
		return $this;
	}

	public function withUser($user_id=0){

		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.user_id = :user_id',
			'params'	=> array(':user_id' => $user_id)
		));
		return $this;
	} 
 

	public function withComment($comment_id=0){

		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.comment_id = :comment_id',
			'params'	=> array(':comment_id' => $comment_id)
		));
		return $this;
	} 

	public static function getComments($type,$dataId){

		switch ($type) {
            case 'news':
                $Comment = Comment::model()->published()->withNews($dataId)->findAll();  
                break; 
        } 

        return $Comment;
	}

	public static function recountAllComments($comment_id = 0, $data_id = 0, $type = NULL){

		$criteria               = new CDbCriteria;  
        $criteria->select       = 't.*';
        $criteria->condition    = 't.active = 1';    

        if($data_id > 0 && $type != NULL){

        	switch ($type) {
                case 'news': 
                    $criteria->condition .= ' AND t.news_id = :data_id';  
                    break;
                case 'article': 
                    $criteria->condition .= ' AND t.article_id = :data_id';  
                    break;
                case 'video': 
                    $criteria->condition .= ' AND t.video_id = :data_id';  
                    break;
             	case 'gallery':
             		$criteria->condition .= ' AND t.gallery_id = :data_id';  
                   	break;
                default :
                	return false;
            } 
 
        	$criteria->params = array(':data_id' => $data_id);
        }

        $criteria_main 				= new CDbCriteria;  
        $criteria_main->condition   = 't.comment_id = :comment_id';    
        $criteria_main->params 		= array(':comment_id' => $comment_id);
    	$criteria_main->mergeWith($criteria);

		$parentCommentsItems = Comment::model()->findAll($criteria_main);  
		if($parentCommentsItems){
			
			$mainCount = 0;
			foreach ($parentCommentsItems as $ParentComment) {	

				$count_childrens = Comment::model()->withComment($ParentComment->id)->count($criteria);  
 				//----------------------------------------------------------
				$sql = 'UPDATE '.$ParentComment->tableName().' AS t 
							SET t.`count_childrens` = '.$count_childrens.' 
		  					WHERE t.id = '.$ParentComment->id;  
				Yii::app()->db->createCommand($sql)->execute();   
				//----------------------------------------------------------
				Comment::reCountSourse($ParentComment); // слабое место
				$mainCount += $count_childrens;
				//----------------------------------------------------------
				//рекурсия--------------------------------------------------
				Comment::recountAllComments($ParentComment->id, $data_id, $type);
			}
		}
		return false;
	}

/*
	public static function recountAllComments(){

		$parentCommentsItems = Comment::model()->published()->findAll();  
		if($parentCommentsItems){
			foreach ($parentCommentsItems as $ParentComment) {
				
				$ParentComment->recountComments();  
			}
		}
	}
*/
	
	public function recountComments(){ 
		 
		$count_childrens = Comment::model()->published()->withComment($this->id)->count();   
			 
		$this->count_childrens = $count_childrens;
		$this->update = true;
		$this->update(array('count_childrens'));     

		Comment::reCountSourse($this);
		return true;
	}

	public static function reCountSourse(Comment $Comment){ 

		if($Comment){ 

			if($Comment->news_id > 0){ 
				News::recountComments($Comment->news_id); 
			} else if($Comment->video_id > 0){
 				Videos::recountComments($Comment->video_id); 
			} else if($Comment->article_id > 0){
 				Article::recountComments($Comment->article_id); 
			} else if($Comment->gallery_id > 0){
 				Gallery::recountComments($Comment->gallery_id); 
			}   
		}  
	}


	public function getTime(){
        $time = strtotime($this->datetime);
        $date = date('H:i', $time);
        return $date;
    }

    public function getUser(){
  
    	$Users = false; 
    	$session = Yii::app()->session;
    	if($session['users'] != NULL){
    		if($session['users'][$this->user_id] != NULL){
    			$Users = $session['users'][$this->user_id];
    		}  
    	}

    	if(!$Users){

    		$Users = Users::model()->findByPk($this->user_id);
    		if($Users){
    			$session['users'][$Users->id] = $Users;
    		}
    	}
 		
 		return $Users;
    }

 	public function getUserUrl(){

 		if($this->user_id == 0) return false; 
 		$Users = $this->getUser(); 
		if($Users){
    		return $Users->getItemUrl();
    	}  
 	}

    public function getUserName(){

    	if($this->user_id == 0) return false; 
    	
    	$Users = $this->getUser();

		if($Users){
    		return $Users->name;
    	}  
    }

    public function getUserPhoto(){

    	if($this->user_id == 0) return false; 

		$Users = $this->getUser();
    	if($Users){
    		if($Users->file_photo != NULL && file_exists($_SERVER['DOCUMENT_ROOT'].Users::PATH_IMAGE.$Users->file_photo)){
    			
    			return Users::PATH_IMAGE.$Users->file_photo;
    			//return $Users->file_photo;
    		}  
    	} 

    	return '/img/ava_clear.png';   
    }

	protected function afterSave(){

		parent::afterSave();  

		//удаление маркера новой записи 
  		if($this->isNewRecord)
  			unset($this->isNewRecord); 
 		
 		if(!$this->update)
			$this->recountComments(); 

		
  		return true;
	}

	public function getDateTime(){
        $time = strtotime($this->datetime);
        if(date('Y-m-d', $time) == date('Y-m-d', time())){
            $date = date('H:i', $time);
        }
        else{
            //$date = date('j', $time).' '.Yii::t('app', 'm'.date('m', $time)).' '.date('Y', $time).' '.date('H:i', $time);
            
            if(date('Y', $time) < date('Y'))
        		$date = date('j.m.Y', $time).' '.date('H:i', $time); 
        	else 
        		$date = date('j.m', $time).' '.date('H:i', $time);
        }
        return $date;
    }


	protected function afterDelete(){

  		parent::afterDelete();   

		$count_childrens = Comment::model()->withComment($this->id)->findAll();
		if(count($count_childrens) > 0){
			foreach ($count_childrens as $key=>$Comment) {
				$Comment->delete();
			}
		}

		$this->recountComments(); 

        return true;
  	}

  	public static function changeRate($id){

  		$sql = 'UPDATE '.parent::tablePrefix().'comment AS t
                    SET t.`votes_pro` = (
                                            (SELECT COUNT(t2.id) as count
                                                FROM comma_likes_count AS t2
                                                WHERE t2.comment_id = t.id AND t2.active = 1)
                                                -
                                            (SELECT COUNT(t3.id) as count
                                                FROM comma_likes_count AS t3
                                                WHERE t3.comment_id = t.id AND t3.active = 0)
                                         )
  		            WHERE t.id = :id';
		Yii::app()->db->createCommand($sql)->bindParam(":id", $id, PDO::PARAM_STR)->execute();
		
		//------------------------------------------------------------------------------------------------
		$sql = 'SELECT t.votes_pro FROM '.parent::tablePrefix().'comment AS t WHERE t.id = :id';
		$dataReader = Yii::app()->db->createCommand($sql)->bindParam(":id", $id, PDO::PARAM_STR)->query();
		$row = $dataReader->read();
  		return $row['votes_pro'];	 
  	}

  	public function getDate($monthType=''){
    	
        $time = strtotime($this->datetime); 
        
        if($monthType == 'name'){
        	$date = date('j', $time).' '.Yii::t('app', 'm'.date('m', $time));
        } else {
        	$date = date('j.m', $time);
        }
        
        $date .= ' '.Yii::t('app','v').' '.date('H:i', $time);

        if(date('Y', $time) < date('Y'))
        	$date .= ' '.date('Y', $time);
         
        return $date;
    }  



}

