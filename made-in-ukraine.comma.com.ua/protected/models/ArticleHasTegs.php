<?php
class ArticleHasTegs extends BaseModel { 
  
	public function tableName(){
		return parent::tablePrefix().'article_has_tegs';
	}
 
	public function rules()	{ 
		return array( 
			array('id, article_id, teg_id', 'safe'),
		);
	}
	
	public function withArticle($article_id){  
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.article_id = :article_id',
			'params'	=> array(':article_id' => $article_id)
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
