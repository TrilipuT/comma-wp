<?php  
 
class Settings extends BaseModel {

	private $_itemList = array();

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Constant the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return $this->tablePrefix().'settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parameter, value', 'required', 'length', 'max'=>255, 'safe'), 
 
		);
	} 

	public function attributeLabels()	{
		return array(
			'id' 				=> 'ID', 
			'feedback_email' 	=> 'Почта обратной связи',
			'form_email' 		=> 'Почта',
			'facebook_id' 		=> 'facebook_id',
			'facebook_secret' 	=> 'facebook_secret',
			'mail-reply-to' 	=> 'обратный адрес'
		);
	}


	public function getItemByKey($key) { 

		if(!empty($this->_itemList[$key]))
			return $this->_itemList[$key]; 
		
		$Settings = Settings::model()->find('parameter = :key', array(':key' => $key));

		if($Settings){
			$this->_itemList[$key] = $Settings->value;
		}
 
		return $this->_itemList[$key];
	}
}