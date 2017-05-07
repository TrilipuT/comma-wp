<?php
class ArticleHasAuthors extends BaseModel { 
  
	public function tableName(){
		return parent::tablePrefix().'article_has_authors';
	}
 
	public function rules()	{ 
		return array( 
			array('id, article_id, authors_id', 'safe'),
		);
	}
	
	public function withArticle($article_id){  
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.article_id = :article_id',
			'params'	=> array(':article_id' => $article_id)
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
