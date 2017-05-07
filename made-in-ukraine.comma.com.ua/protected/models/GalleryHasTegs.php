<?php
class GalleryHasTegs extends BaseModel { 
  
	public function tableName(){
		return parent::tablePrefix().'gallery_has_tegs';
	}
 
	public function rules()	{ 
		return array( 
			array('id, gallery_id, teg_id', 'safe'),
		);
	}
	
	public function withGallery($gallery_id){  
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.gallery_id = :gallery_id',
			'params'	=> array(':gallery_id' => $gallery_id)
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
