<?php

/**
 * This is the model class for table "comma_tags_transfer".
 *
 * The followings are the available columns in table 'comma_tags_transfer':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $language_id
 * @property string $name
 */
  
class TagsTransfer extends BaseModel {
 
	/**
	 * @return string the associated database table name
	 */  
	public function tableName(){
		return parent::tablePrefix().'tags_transfer';
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
			array('id, parent_id, language_id, name', 'safe'), //, 'on'=>'search'
		);
	}
 

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'parent_id' => 'Parent',
			'language_id' => 'Language',
			'name' => 'название',
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
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('language_id',$this->language_id);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	} */

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TagsTransfer the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
 
}
