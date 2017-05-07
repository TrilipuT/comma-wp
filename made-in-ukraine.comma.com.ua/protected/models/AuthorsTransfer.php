<?php

/**
 * This is the model class for table "comma_authors_transfer".
 *
 * The followings are the available columns in table 'comma_authors_transfer':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $language_id
 * @property string $code_name
 * @property string $name
 * @property string $description
 * @property string $page_title
 * @property string $meta_description
 * @property string $meta_keywords
 */
  
class AuthorsTransfer extends BaseModel {
 
	/**
	 * @return string the associated database table name
	 */  
	public function tableName(){
		return parent::tablePrefix().'authors_transfer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
 
			//array('image_delete, image, SectionUrl', 'default'), 
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parent_id, language_id, code_name, name, last_name, post, description, page_title, meta_description, meta_keywords', 'safe'), //, 'on'=>'search'
		);
	}
 	
 	protected function beforeSave(){

		parent::beforeSave(); 

		$this->name = trim($this->name);
		$this->last_name = trim($this->last_name); 

  		return true;
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' 				=> 'ID',
			'parent_id' 		=> 'Parent',
			'language_id' 		=> 'Language',
			'code_name' 		=> 'Code Name',
			'name' 				=> 'Имя',
			'last_name' 		=> 'Фамилия',
			'post' 				=> 'Должность',
			'description' 		=> 'краткое описание',
			'page_title' 		=> 'Page Title',
			'meta_description' 	=> 'Meta Description',
			'meta_keywords' 	=> 'Meta Keywords',
		);
	}

  
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
 	
 	public function getName(){
 		return $this->name.' '.$this->last_name;
 	}
}
