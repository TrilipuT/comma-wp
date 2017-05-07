<?php

/**
 * This is the model class for table "sf_constants".
 *
 * The followings are the available columns in table 'sf_constants':
 * @property integer $id
 * @property string $key
 * @property integer $active
 */
class Constants extends BaseModel {

	public $transfer_type = true;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()	{
		return parent::tablePrefix().'constants';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('active', 'numerical', 'integerOnly'=>true),
			array('key', 'length', 'max'=>255), 
			array('id, key, active', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
 
			'transfer' => array(self::HAS_ONE, 
										__CLASS__.'Transfer', 
										'parent_id',
										'condition' => 'transfer.language_id = :lang_id',
										'params' 	=> array(':lang_id' => Language::getActiveLanguageId())) 
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'key' => 'Key',
			'active' => 'Active',
		);
	} 

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Constants the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}


	public static function getItemByKey($key) {    
		
		$Settings = self::model()->find('t.key = :key', array(':key' => $key));   
		return $Settings->transfer->name;
	}

}
