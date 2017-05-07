<?php

/**
 * This is the model class for table "comma_tags".
 *
 * The followings are the available columns in table 'comma_tags':
 * @property integer $id
 * @property string $code_name
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
 class Tags extends BaseModel {
	
	public static $SectionUrl;


	public $transfer_type = true;

	/**
	 * @return string the associated database table name
	 */  
	public function tableName(){
		return parent::tablePrefix().'tags';
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
			array('id, code_name, order_num, changefreq, priority, created_by, modified_by, blocked, added_time, edited_time, active', 'safe'), //, 'on'=>'search'
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
			'order_num' => 'порядковый номер',
			'changefreq' => 'changefreq (sitemap)',
			'priority' => 'priority (sitemap)',
			'created_by' => 'кто создал',
			'modified_by' => 'кто последний редактировал',
			'blocked' => 'Blocked',
			'added_time' => 'Added Time',
			'edited_time' => 'Edited Time',
			'active' => 'Active',
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
	 * @return Tags the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

 	//---------------------------------------------------------------------------------------- 
  	public static function getSectionUrl(){

  		if(self::$SectionUrl == ''){

  			if(Yii::app()->language != 'ru')
  				self::$SectionUrl = '/'.Yii::app()->language;

  			self::$SectionUrl .= '/'.Base::findControllerAlias('C_tag');      
  		}
  		return self::$SectionUrl;
  	}

    public function getItemUrl(){  

        return self::getSectionUrl().'/'.$this->transfer->name; //.'.html'
    } 
  	//----------------------------------------------------------------------------------------
    public static function findByName($name){
 

		$TagsTransfer = TagsTransfer::model()->find('t.name = :value AND t.language_id = :lang_id', 
													array(':value' => trim($name), ':lang_id' => Language::getActiveLanguageId())); 
		return $TagsTransfer;
    }

    protected function beforeDelete(){

  		parent::beforeDelete();   

  		NewsHasTegs::model()->deleteAll('teg_id = :teg_id',array(':teg_id' => $this->id)); 
  		GalleryHasTegs::model()->deleteAll('teg_id = :teg_id',array(':teg_id' => $this->id));
  		ArticleHasTegs::model()->deleteAll('teg_id = :teg_id',array(':teg_id' => $this->id));
  		VideosHasTegs::model()->deleteAll('teg_id = :teg_id',array(':teg_id' => $this->id));
	 	
        return true;
  	}
	
}
