<?php

/**
 * This is the model class for table "am_sections_transfer".
 *
 * The followings are the available columns in table 'am_sections_transfer':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $language_id
 * @property string $name
 * @property string $menu_name
 * @property string $text
 * @property string $page_title
 * @property string $meta_description
 * @property string $meta_keywords
 *
 * The followings are the available model relations:
 * @property AmSections $section
 */
class SectionTransfer extends BaseModel {
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SectionsTransfer the static model class
	 */
	public static function model($className=__CLASS__)	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()	{
		return parent::tablePrefix().'sections_transfer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id', 'required'),
			array('parent_id, language_id', 'numerical', 'integerOnly'=>true),
			array('name, menu_name, page_title, meta_description, meta_keywords', 'length', 'max'=>255), 
			array('id, parent_id, language_id, description, name, menu_name, content, seo_text, page_title, meta_description, meta_keywords', 'safe' ),
		);
	}
	 

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' 				=> 'ID',
			'parent_id' 		=> 'Section',
			'language_id' 		=> 'Language',
			'name' 				=> 'Name',
			'menu_name' 		=> 'Menu Name',
			'content' 			=> 'Content',
			'page_title' 		=> 'Page Title',
			'meta_description' 	=> 'Meta Description',
			'meta_keywords' 	=> 'Meta Keywords'
		);
	}
	/*
	protected function beforeSave(){

		parent::beforeSave();  


		$search  = "|<img[^>]+>|si";
		$search2 = "/<img[\s]+[^>]*?((alt*?[\s]?=[\s\"\']+(.*?)[\"\']+.*?))(|(alt*?[\s]?=[\s\"\']+(.*?)[\"\']+.*?>)|>)/";
		$preg = preg_match_all($search, $this->content, $matches);
		if($preg > 0){
			foreach ($matches[0] as $str) {
			 	$_preg = preg_match($search2, $str, $match);  

			 	if($match[3] == ""){  
			 		$new_str = preg_replace('/alt(="")?/', 'alt="'.$this->name.'"', $str); 
			 		$this->content = preg_replace('|'.$str.'|', $new_str, $this->content);
			 	} 
			}
		} 
		return true;
	}  */
}