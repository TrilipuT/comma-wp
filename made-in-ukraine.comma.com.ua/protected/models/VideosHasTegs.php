<?php
class VideosHasTegs extends BaseModel { 
  
	public function tableName(){
		return parent::tablePrefix().'video_has_tegs';
	}
 
	public function rules()	{ 
		return array( 
			array('id, video_id, teg_id', 'safe'),
		);
	}
	
	public function withVideos($video_id){  
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.video_id = :video_id',
			'params'	=> array(':video_id' => $video_id)
		));
		return $this;
	}
	
	public function withTegs($teg_id){  
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.teg_id = :teg_id',
			'params'	=> array(':teg_id' => $teg_id)
		));
		return $this;
	}
  
	public static function model($className=__CLASS__){
		return parent::model($className);
	} 
}
