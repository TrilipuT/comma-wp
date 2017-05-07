<?php

function searchColum($name, $columns){
	if($columns){
		foreach($columns as $column){
			if($name == $column->name){
				return true;
			}
		}
	}

	return false;
}


/**
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */
?>
<?php echo "<?php\n"; ?>

/**
 * This is the model class for table "<?php echo $tableName; ?>".
 *
 * The followings are the available columns in table '<?php echo $tableName; ?>':
<?php foreach($columns as $column): ?>
 * @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php endforeach; ?>
<?php if(!empty($relations)): ?>
 *
 * The followings are the available model relations:
<?php foreach($relations as $name=>$relation): ?>
 * @property <?php
	if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            default:
                echo 'mixed $'.$name."\n";
        }
	}
    ?>
<?php endforeach; ?>
<?php endif; ?>
 */
 
class <?php echo $modelClass; ?> extends <?php echo $this->baseClass; ?> {
	
<?php if(searchColum('code_name', $columns)):?>
	public static $SectionUrl;
<?php endif;?>

<?php if(searchColum('image_filename', $columns)):?>
	const 	PATH_IMAGE 	= '/graphics/<?php echo $tableName; ?>/';

	public 	$image_delete = 0,
			$image;
<?php endif;?>

	//public $transfer_type = true;

	/**
	 * @return string the associated database table name
	 */  
	public function tableName(){
		return parent::tablePrefix().'<?php echo $tableName; ?>';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
<?php foreach($rules as $rule): ?>
			<?php echo $rule.",\n"; ?>
<?php endforeach; ?>
			//array('image_delete, image, SectionUrl', 'default'), 
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe'), //, 'on'=>'search'
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
<?php foreach($relations as $name=>$relation): ?>
			<?php echo "'$name' => $relation,\n"; ?>
<?php endforeach; ?>
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
<?php foreach($labels as $name=>$label): ?>
			<?php echo "'$name' => '$label',\n"; ?>
<?php endforeach; ?>
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

<?php
foreach($columns as $name=>$column)
{
	if($column->type==='string')
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name,true);\n";
	}
	else
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name);\n";
	}
}
?>

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	} */

<?php if($connectionId!='db'):?>
	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection(){
		return Yii::app()-><?php echo $connectionId ?>;
	}

<?php endif?>
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return <?php echo $modelClass; ?> the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

<?php if(searchColum('code_name', $columns)):?>
 	//---------------------------------------------------------------------------------------- 
  	public function getSectionUrl(){

  		if(self::$SectionUrl == ''){
  			self::$SectionUrl = '/'.Yii::app()->language.'/'.Base::findControllerAlias('#'); 
  		}
  		return self::$SectionUrl;
  	}

    public function getItemUrl(){  

        return self::getSectionUrl().'/'.$this->code_name.'.html';
    } 
  	//----------------------------------------------------------------------------------------
<?php endif;?>

<?php if(searchColum('image_filename', $columns)):?>
	protected function beforeSave(){

		parent::beforeSave(); 

		if($this->image_delete == 1 && $this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->image_filename = '';
  		} 

  		return true;
	}


	protected function afterSave(){ 

  		parent::afterSave();  
  		
  		//удаление маркера новой записи 
  		if($this->isNewRecord)
  			unset($this->isNewRecord); 
 
  		if($_FILES[__CLASS__] != NULL){

  	  		$doc = CUploadedFile::getInstance($this,'image');
			if($doc){
				//$type 	= $doc->getType();
				//$doc->getSize();
				
				Yii::import('application.components.Image');

				$tmp_image = $doc->getTempName();  

				$Image 					= new Image();  
				 
				$this->image_filename 	= basename($Image->load($tmp_image)
															//->scale(array('w',450))
															->save($_SERVER['DOCUMENT_ROOT'].self::PATH_IMAGE.$this->id));  
				 
			 	unset($_FILES[__CLASS__]);
				$this->update(array('image_filename'));  
	 		}
  	  	}	  
  	  	 

		return true;
  	}
 
	protected function afterDelete(){

  		parent::afterDelete();   

  		if($this->image_filename != ''){ 
  			$this->fileDelete(self::PATH_IMAGE.$this->image_filename);
  			$this->image_filename = '';
  		}  
  		 
        return true;
  	}
<?php endif;?>

<?php if(searchColum('datetime', $columns)):?>
	public function getDate(){
    	
        $time = strtotime($this->datetime); 
        
        $date = date('j.m', $time);

        if(date('Y', $time) < date('Y'))
        	$date .= ' '.date('Y', $time);
         
        return $date;
    } 
<?php endif;?>	
}
