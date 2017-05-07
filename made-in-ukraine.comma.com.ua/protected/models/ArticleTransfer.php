<?php

/**
 * This is the model class for table "comma_article_transfer".
 *
 * The followings are the available columns in table 'comma_article_transfer':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $language_id
 * @property string $code_name
 * @property string $name
 * @property string $description
 * @property string $annotation
 * @property string $content
 * @property string $page_title
 * @property string $meta_description
 * @property string $meta_keywords
 */
  
class ArticleTransfer extends BaseModel {
 
	/**
	 * @return string the associated database table name
	 */  
	public function tableName(){
		return parent::tablePrefix().'article_transfer';
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
			array('id, parent_id, language_id, code_name, name, description, annotation, content, page_title, meta_description, meta_keywords', 'safe'), //, 'on'=>'search'
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
			'code_name' => 'Code Name',
			'name' => 'название',
			'description' => 'краткое описание (на странице статьи)',
			'annotation' => 'анотация',
			'content' => 'Content',
			'page_title' => 'Page Title',
			'meta_description' => 'Meta Description',
			'meta_keywords' => 'Meta Keywords',
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
		$criteria->compare('code_name',$this->code_name,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('annotation',$this->annotation,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('page_title',$this->page_title,true);
		$criteria->compare('meta_description',$this->meta_description,true);
		$criteria->compare('meta_keywords',$this->meta_keywords,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	} */

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArticleTransfer the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
 
}
