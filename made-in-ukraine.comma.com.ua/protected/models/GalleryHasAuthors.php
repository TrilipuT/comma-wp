<?php
class GalleryHasAuthors extends BaseModel { 
  
	public function tableName(){
		return parent::tablePrefix().'gallery_has_authors';
	}
 
	public function rules()	{ 
		return array( 
			array('id, gallery_id, authors_id', 'safe'),
		);
	}
	
	public function withGallery($gallery_id){  
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.gallery_id = :gallery_id',
			'params'	=> array(':gallery_id' => $gallery_id)
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
