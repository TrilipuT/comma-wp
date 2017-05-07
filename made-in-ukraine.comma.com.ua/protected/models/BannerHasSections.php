<?php
 
class BannerHasSections extends BaseModel{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SeriesTransfer the static model class
	 */
	public static function model($className=__CLASS__)	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()	{
		return $this->tablePrefix().'banner_has_sections';   
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array( 
			array('id, banner_id, section_id', 'safe' ),
		);
	} 


	public function withBanner($banner_id){ 

		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.banner_id = :banner_id',
			'params'	=> array(':banner_id' => $banner_id)
		));
		return $this;
	}

	public function withSection($section_id){ 

		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.section_id = :section_id',
			'params'	=> array(':section_id' => $section_id)
		));
		return $this;
	}

 
	
}