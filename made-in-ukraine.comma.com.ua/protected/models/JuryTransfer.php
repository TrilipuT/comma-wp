<?php

/**
 * This is the model class for table "comma_jury_transfer".
 *
 * The followings are the available columns in table 'comma_jury_transfer':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $language_id
 * @property string $code_name
 * @property string $name
 * @property string $description
 * @property string $text
 * @property string $page_title
 * @property string $meta_description
 * @property string $meta_keywords
 */
  
class JuryTransfer extends BaseModel {
 
	/**
	 * @return string the associated database table name
	 */  
	public function tableName(){
		return parent::tablePrefix().'jury_transfer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, parent_id, language_id, code_name, name, description,
					text, page_title, meta_description, meta_keywords', 'safe'), //, 'on'=>'search'
		);
	}
 	
 	protected function beforeSave(){

		parent::beforeSave(); 

		$this->name = trim($this->name);
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
			'description' 		=> 'краткое описание',
			'page_title' 		=> 'Page Title',
			'meta_description' 	=> 'Meta Description',
			'meta_keywords' 	=> 'Meta Keywords',
		);
	}

  
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}
