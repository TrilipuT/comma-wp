<?php echo "<?php"; ?>

class <?php echo $modelClass; ?> extends <?php echo $this->baseClass; ?> { 
  
	public function tableName(){
		return parent::tablePrefix().'<?php echo $tableName; ?>';
	}
 
	public function rules()	{ 
		return array( 
			array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe'),
		);
	}
<?  

	$_tmp  = explode('Has', $modelClass); 
	$array = array_keys($columns);

	foreach ($_tmp as $key => $value): ?>
	
	public function with<?=$value;?>($<?=$array[$key+1]?>){  
		$this->getDbCriteria()->mergeWith(array(
			'condition'	=> $this->tableAlias.'.<?=$array[$key+1]?> = :<?=$array[$key+1]?>',
			'params'	=> array(':<?=$array[$key+1]?>' => $<?=$array[$key+1]?>)
		));
		return $this;
	}
<?php endforeach;?>
  
	public static function model($className=__CLASS__){
		return parent::model($className);
	} 
}
