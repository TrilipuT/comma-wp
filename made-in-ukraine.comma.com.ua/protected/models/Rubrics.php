<?php

/**
 * This is the model class for table "comma_rubrics".
 *
 * The followings are the available columns in table 'comma_rubrics':
 * @property integer $id
 * @property string $code_name
 * @property string $color
 * @property integer $order_num
 * @property string $changefreq
 * @property string $priority
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $blocked
 * @property string $added_time
 * @property string $edited_time
 * @property integer $active
 */
 class Rubrics extends BaseModel {
	
	public static $SectionUrl;


	public $transfer_type = true;

	private $_itemList = array();

	/**
	 * @return string the associated database table name
	 */  
	public function tableName(){
		return parent::tablePrefix().'rubrics';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code_name', 'required'),
			array('order_num, created_by, modified_by, blocked, active', 'numerical', 'integerOnly'=>true),
			array('code_name', 'length', 'max'=>255), 
			array('priority', 'length', 'max'=>3),
			array('added_time, edited_time', 'safe'),
			//array('image_delete, image, SectionUrl', 'default'), 
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, color_class, code_name, color, parent_id, order_num, changefreq, priority, created_by, modified_by, blocked, added_time, edited_time, active', 'safe'), //, 'on'=>'search'
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()	{
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
			'code_name' => 'Code Name',
			'color' => 'цвет',
			'order_num' => 'порядковый номер',
			'changefreq' => 'changefreq (sitemap)',
			'priority' => 'priority (sitemap)',
			'created_by' => 'кто создал',
			'modified_by' => 'кто последний редактировал',
			'blocked' => 'Blocked',
			'added_time' => 'Added Time',
			'edited_time' => 'Edited Time',
			'active' => 'Active',
			'color_class' => 'клас цвета (соц сети,иконки)',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */

	 /*
	public function search(){
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('code_name',$this->code_name,true);
		$criteria->compare('color',$this->color,true);
		$criteria->compare('order_num',$this->order_num);
		$criteria->compare('changefreq',$this->changefreq,true);
		$criteria->compare('priority',$this->priority,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('blocked',$this->blocked);
		$criteria->compare('added_time',$this->added_time,true);
		$criteria->compare('edited_time',$this->edited_time,true);
		$criteria->compare('active',$this->active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	} */

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rubrics the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

 	//---------------------------------------------------------------------------------------- 
  	public static function getSectionUrl(){

  		if(self::$SectionUrl == ''){

  			if(Yii::app()->language != 'ru')
  				self::$SectionUrl = '/'.Yii::app()->language;

  			self::$SectionUrl .= '/'.Base::findControllerAlias('C_articles');     
  		}
  		return self::$SectionUrl;
  	}

    public function getItemUrl(){  

    	if($this->parent_id == 0){
    		$this->_itemList[$this->id] = $this->code_name;

    		return self::getSectionUrl().'/'.$this->code_name;
    	} else {
    		if(!empty($this->_itemList[$this->parent_id])){
    			$parentCodeName = $this->_itemList[$this->parent_id];
    		} else {
    			$Rubrics = Rubrics::model()->findByPk($this->parent_id);
    			if($Rubrics){
    				$this->_itemList[$this->id] = $Rubrics->code_name;
    				$parentCodeName = $this->_itemList[$this->id];
    			}
    		}
    		if($parentCodeName != ''){
    			return self::getSectionUrl().'/'.$parentCodeName.'/'.$this->code_name; 	
    		} else {
    			return false;
    		}
					
    	}  
    } 
  	//----------------------------------------------------------------------------------------

    public static function getItemsList(){

    	$rubricsArray = array();
    	$rubricsItems = Rubrics::model()->published()->orderByOrderNum()->findAll('parent_id = 0');
    	if(count($rubricsItems) > 0){
    		foreach ($rubricsItems as $Rubrics) {
    			
    			$subRubricsItems = Rubrics::model()->published()->orderByOrderNum()->findAll('parent_id = :parent_id', 
    																						 array(':parent_id' => $Rubrics->id));
    			$rubricsArray[$Rubrics->transfer->name] = array();
    			if(count($subRubricsItems) > 0){
    				$_array = array();
    				foreach ($subRubricsItems as $_Rubrics) {
    					$_array[$_Rubrics->id] = $_Rubrics->transfer->name; 
    				}

    				$rubricsArray[$Rubrics->transfer->name] = $_array;
    			}  
    		} //endforeach
    	}

    	return $rubricsArray;
    }

    /**
    * так как может быть 2 уровня, то рекурсию не применяем
    */
    public static function getMainRubric($rubric_id){
  
    	$items = Yii::app()->db->createCommand()
				    ->select('t.id, t.parent_id')
				    ->from(Rubrics::model()->tableName().' AS t')
				    ->where('t.active = 1 AND t.id = :id', array(':id' => $rubric_id)) 
				    ->queryRow();

	    if($items['parent_id'] > 0){
	    	return $items['parent_id'];
	    } else {
	    	return $items['id'];
	    } 
	    return false;
    }


    public static function getAllRubrics($rubric_id){

    	$ids       = array();
    	$rubric_id = Rubrics::getMainRubric($rubric_id);
    	if($rubric_id > 0){
			$items = Yii::app()->db->createCommand()
					    ->select('t.id')
					    ->from(Rubrics::model()->tableName().' AS t')
					    ->where('t.active = 1 AND t.parent_id = :parent_id',
					    		array(':parent_id' => $rubric_id)) 
					    ->order('t.order_num DESC')
					    ->queryAll(); 

			if(count($items) > 0){  
		    	foreach ($items as $value) {
		    		$ids[] = $value['id'];
		    	}
		    } 
		}
		return $ids;
    }
	
}
