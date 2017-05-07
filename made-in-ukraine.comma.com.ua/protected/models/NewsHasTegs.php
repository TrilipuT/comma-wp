<?php
class NewsHasTegs extends BaseModel { 
  
	public function tableName(){
		return parent::tablePrefix().'news_has_tegs';
	}
 
	public function rules()	{ 
		return array( 
			array('id, news_id, teg_id', 'safe'),
		);
	}
	
	public function withNews($news_id){  
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.news_id = :news_id',
			'params'	=> array(':news_id' => $news_id)
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
