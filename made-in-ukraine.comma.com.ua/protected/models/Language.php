<?php 
class Language extends BaseModel{

	public static $language_id = 1;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Language the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return parent::tablePrefix().'language';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, code_name', 'required'),
			array('order_num, active', 'numerical', 'integerOnly'=>true),
			array('name, code_name', 'length', 'max'=>255),
			array('added_time, title, edited_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, code_name, added_time, edited_time, order_num, active', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	protected function beforeSave(){

		parent::beforeSave(); 

		//русский нельзя удалять и деактивировать
 		if($this->id == 1 && $this->code_name == 'ru' && $this->active == 0)
  			return false;

  		return true;
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){

		return array(
			'id' => 'ID',
			'name' => 'Name',
			'code_name' => 'Code Name',
			'added_time' => 'Added Time',
			'edited_time' => 'Edited Time',
			'order_num' => 'Order Num',
            'default' => 'Default',
			'active' => 'Active',
		);
	}


	public static function  getActiveLanguageId(){
		$Language = Language::model()->find('code_name = :code_name', array(':code_name' => Yii::app()->language));
		return $Language->id;
	}
    
    public function getDefaultLanguageId(){ 

		$Language = Language::model()->find('default = :default', array(':default' => Yii::app()->language)); 
		return $Language->id;
	}

	public function getActiveLanguage(){ 


		$Language = Language::model()->find('code_name = :code_name', array(':code_name' => Yii::app()->language)); 
		return $Language;
	}

	public function getLanguageList($row='name',$active = 0){

		if($active == 1) 
			$items = Language::model()->orderByOrderNum()->published()->findAll();
		else
			$items = Language::model()->orderByOrderNum()->findAll();

		return CHtml::listData($items, 'id', $row );
	}  

  	protected function beforeDelete(){

 		parent::beforeDelete(); 
 		
 		//русский нельзя удалять и деактивировать
 		if($this->id == 1 || $this->code_name == 'ru')
  			return false; 

 		return true;	
 	}
}