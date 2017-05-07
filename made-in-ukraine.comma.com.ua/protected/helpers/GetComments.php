<?php 
class GetComments{ 
	
	private $type;
	private $CDbCriteria;

	public function __construct($type){
  		$this->type 	   			  = $type;
  		$this->CDbCriteria 			  = new CDbCriteria;  
	}

	public function setUser($userId){
		$this->CDbCriteria->addCondition("user_id = :user_id"); 
		$this->CDbCriteria->params += array(':user_id' => $userId);
	}

	public function withType($dataId){

		if($this->type == NULL) return false;

		switch ($this->type) {
            case 'news':
                $this->CDbCriteria->addCondition("news_id = :dataId");
                $this->CDbCriteria->params += array(':dataId' => $dataId);
                break;  
            case 'article':
                $this->CDbCriteria->addCondition("article_id = :dataId");
                $this->CDbCriteria->params += array(':dataId' => $dataId);
                break;   
            case 'video' :
            	$this->CDbCriteria->addCondition("video_id = :dataId");
                $this->CDbCriteria->params += array(':dataId' => $dataId);
                break;
            case 'gallery' :
            	$this->CDbCriteria->addCondition("gallery_id = :dataId");
                $this->CDbCriteria->params += array(':dataId' => $dataId);  
                break;
            default :
            	$this->CDbCriteria->addCondition("id = -1");
        }   
    	$this->CDbCriteria->order  = 'datetime DESC';
	} 

	public function setActive($active = 0){
		$this->CDbCriteria->addCondition('active = :active'); 
		$this->CDbCriteria->params += array(':active' => $active);
	}

	public function checkLastAdd($text,$time = 1000){
 
		$this->CDbCriteria->addCondition("content = :content");
		$this->CDbCriteria->params += array(':content' => $text);

		$this->CDbCriteria->order  = 'datetime DESC';

		$Comment = Comment::model()->find($this->CDbCriteria); 
		if($Comment){
			$datetime = strtotime($Comment->datetime);
 			
			if($datetime+$time > time()){
				return false;
			} 
		}

		return true;
	}

	public function getCommentsRecursive($parrentId = 0){

		$CDbCriteria = new CDbCriteria;
		 
		$CDbCriteria->addCondition('comment_id = :comment_id'); 
		$CDbCriteria->params += array(':comment_id' => $parrentId);
		
		$CDbCriteria->mergeWith($this->CDbCriteria);
		/*
		if($parrentId > 0){
			var_dump($CDbCriteria->toArray()); exit;
		}*/

        if($parrentId > 0){
            $CDbCriteria->order = 'added_time ASC, order_num ASC, id ASC';
        } else {
            $CDbCriteria->order = 'added_time DESC, order_num DESC, id DESC';
        }

		$result = array(); 

		$commentsItems = Comment::model()->findAll($CDbCriteria);  
		if(count($commentsItems) > 0){  //var_dump($commentsItems);
 
			//$result = $commentsItems;
			foreach ($commentsItems as $key=>$Comment) { 
 				
 				$result[$key]['item'] = $Comment;

				if($Comment->count_childrens > 0){

					$result[$key]['items'] = $this->getCommentsRecursive($Comment->id);
				}  
			} 
			
			return $result;

		}  else {
			return $result;
		}

		

	}
}

