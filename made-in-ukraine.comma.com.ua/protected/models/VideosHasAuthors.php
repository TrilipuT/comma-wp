<?php
class VideosHasAuthors extends BaseModel { 
  
	public function tableName(){
		return parent::tablePrefix().'videos_has_authors';
	}
 
	public function rules()	{ 
		return array( 
			array('id, video_id, authors_id', 'safe'),
		);
	}
	
	public function withVideos($video_id){  
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.video_id = :video_id',
			'params'	=> array(':video_id' => $video_id)
		));
		return $this;
	}
	
	public function withAuthors($authors_id){  
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.authors_id = :authors_id',
			'params'	=> array(':authors_id' => $authors_id)
		));
		return $this;
	}
  
	public static function model($className=__CLASS__){
		return parent::model($className);
	} 
}
